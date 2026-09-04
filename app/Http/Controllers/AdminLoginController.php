<?php

namespace App\Http\Controllers;

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

        // Pastikan user yang berhasil autentikasi memang berhak masuk panel admin.
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

        // Redirect HTTP biasa, bukan Livewire redirect.
        return redirect()->intended(Filament::getUrl());
    }
}
