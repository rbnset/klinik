<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $email = Str::lower(trim($credentials['email']));
        $throttleKey = Str::transliterate($email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan masuk. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $account = User::where('email', $email)->with('supplier')->first();

        if ($account?->role === 'supplier') {
            $supplier = $account->supplier;

            if ($supplier?->status_pengajuan === 'menunggu') {
                throw ValidationException::withMessages([
                    'email' => 'Pengajuan akun supplier Anda masih menunggu verifikasi admin. Silakan coba lagi setelah mendapatkan persetujuan.',
                ]);
            }

            if ($supplier?->status_pengajuan === 'ditolak') {
                $reason = $supplier->alasan_penolakan ?: 'Alasan penolakan belum dicatat oleh admin.';
                $allowedAt = $supplier->pengajuan_dapat_diajukan_lagi_at;
                $followUp = $allowedAt && now()->lt($allowedAt)
                    ? ' Anda dapat mengajukan kembali mulai ' . $allowedAt->translatedFormat('d F Y, H:i') . '.'
                    : ' Anda sudah dapat mengajukan kembali melalui halaman pendaftaran supplier.';

                throw ValidationException::withMessages([
                    'email' => 'Pengajuan akun supplier ditolak. Alasan: ' . $reason . $followUp,
                ]);
            }
        }

        $remember = $request->boolean('remember');

        if (! Filament::auth()->attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ], $remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Alamat email atau kata sandi yang dimasukkan tidak sesuai.',
            ]);
        }

        $user = Filament::auth()->user();
        $panel = Filament::getCurrentOrDefaultPanel();

        if (! $user || ! $user->canAccessPanel($panel)) {
            Filament::auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda belum memiliki akses ke sistem. Jika Anda supplier, tunggu persetujuan admin terlebih dahulu.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended(Filament::getUrl());
    }
}
