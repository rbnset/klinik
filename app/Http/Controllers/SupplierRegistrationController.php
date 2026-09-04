<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SupplierRegistrationController extends Controller
{
    public function create(): View
    {
        return view('supplier.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:150'],
            'nama_pic' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'nama_supplier.required' => 'Nama perusahaan / supplier wajib diisi.',
            'nama_pic.required' => 'Nama penanggung jawab wajib diisi.',
            'email.unique' => 'Email tersebut sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'terms.accepted' => 'Anda harus menyetujui pernyataan pengajuan akun.',
        ]);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['nama_pic'],
                'email' => $data['email'],
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
