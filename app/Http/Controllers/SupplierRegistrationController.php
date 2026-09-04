<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SupplierRegistrationController extends Controller
{
    private const RESUBMISSION_WAIT_DAYS = 3;

    public function create(): View
    {
        return view('supplier.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:150'],
            'nama_pic' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'nama_supplier.required' => 'Nama perusahaan / supplier wajib diisi.',
            'nama_pic.required' => 'Nama penanggung jawab wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'terms.accepted' => 'Anda harus menyetujui pernyataan pengajuan akun.',
        ]);

        $email = Str::lower(trim($data['email']));
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $existingSupplier = $existingUser->supplier;

            if (! $existingSupplier || $existingUser->role !== 'supplier') {
                return back()->withInput()->withErrors([
                    'email' => 'Email tersebut sudah digunakan oleh akun lain. Gunakan email yang berbeda.',
                ]);
            }

            if ($existingSupplier->status_pengajuan === 'menunggu') {
                return back()->withInput()->withErrors([
                    'email' => 'Pengajuan dengan email ini masih dalam proses verifikasi. Silakan tunggu keputusan admin.',
                ]);
            }

            if ($existingSupplier->status_pengajuan === 'disetujui') {
                return back()->withInput()->withErrors([
                    'email' => 'Email tersebut sudah memiliki akun supplier yang aktif. Silakan masuk ke sistem.',
                ]);
            }

            if ($existingSupplier->status_pengajuan === 'ditolak') {
                $allowedAt = $existingSupplier->pengajuan_dapat_diajukan_lagi_at;

                if ($allowedAt && now()->lt($allowedAt)) {
                    return back()->withInput()->withErrors([
                        'email' => 'Pengajuan sebelumnya ditolak. Anda dapat mengajukan kembali mulai ' . $allowedAt->translatedFormat('d F Y, H:i') . '.',
                    ]);
                }

                DB::transaction(function () use ($data, $existingUser, $existingSupplier, $email): void {
                    $existingUser->update([
                        'name' => $data['nama_pic'],
                        'email' => $email,
                        'password' => Hash::make($data['password']),
                    ]);

                    $existingSupplier->update([
                        'nama_supplier' => $data['nama_supplier'],
                        'alamat' => $data['alamat'],
                        'no_telp' => $data['no_telp'],
                        'status_pengajuan' => 'menunggu',
                        'alasan_penolakan' => null,
                        'ditolak_at' => null,
                        'pengajuan_dapat_diajukan_lagi_at' => null,
                    ]);
                });

                return redirect()->route('supplier.register')
                    ->with('supplier_application_success', true)
                    ->with('supplier_application_resubmitted', true);
            }
        }

        DB::transaction(function () use ($data, $email): void {
            $user = User::create([
                'name' => $data['nama_pic'],
                'email' => $email,
                'password' => Hash::make($data['password']),
                'role' => 'supplier',
            ]);

            Supplier::create([
                'id_pengguna' => $user->id,
                'nama_supplier' => $data['nama_supplier'],
                'alamat' => $data['alamat'],
                'no_telp' => $data['no_telp'],
                'status_pengajuan' => 'menunggu',
            ]);
        });

        return redirect()->route('supplier.register')
            ->with('supplier_application_success', true);
    }
}
