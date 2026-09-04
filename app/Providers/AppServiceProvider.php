<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Models\KategoriObat;
use App\Models\Obat;
use App\Models\PembelianObat;
use App\Models\PenerimaanObat;
use App\Models\Pembayaran;
use App\Models\PermintaanObat;
use App\Models\PenyesuaianStok;
use App\Models\RiwayatStok;
use App\Models\Supplier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        // Endpoint PDF pengadaan. Didaftarkan terpusat agar action Filament
        // dapat menggunakan named route yang konsisten tanpa bergantung pada
        // file routes/web.php yang berbeda antar instalasi.
        Route::middleware(['web', 'auth'])->group(function (): void {
            Route::get('/admin/cetak/pembelian/{pembelian}', [\App\Http\Controllers\PdfController::class, 'pembelian'])
                ->name('admin.cetak.pembelian');
            Route::get('/admin/cetak/pembelian/{pembelian}/ringkasan', [\App\Http\Controllers\PdfController::class, 'ringkasanPembelian'])
                ->name('admin.cetak.pembelian.ringkasan');
        });

        // Daftarkan policy secara eksplisit agar hak akses Filament
        // selalu konsisten pada setiap environment/deployment.
        Gate::policy(KategoriObat::class, \App\Policies\KategoriObatPolicy::class);
        Gate::policy(Obat::class, \App\Policies\ObatPolicy::class);
        Gate::policy(Supplier::class, \App\Policies\SupplierPolicy::class);
        Gate::policy(PembelianObat::class, \App\Policies\PembelianObatPolicy::class);
        Gate::policy(PenerimaanObat::class, \App\Policies\PenerimaanObatPolicy::class);
        Gate::policy(Pembayaran::class, \App\Policies\PembayaranPolicy::class);
        Gate::policy(PermintaanObat::class, \App\Policies\PermintaanObatPolicy::class);
        Gate::policy(PenyesuaianStok::class, \App\Policies\PenyesuaianStokPolicy::class);
        Gate::policy(RiwayatStok::class, \App\Policies\RiwayatStokPolicy::class);
        Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
    }
}
