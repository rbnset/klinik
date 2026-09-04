<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\SupplierRegistrationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('landing');
Route::get('/daftar-supplier', [SupplierRegistrationController::class, 'create'])->name('supplier.register');
Route::post('/daftar-supplier', [SupplierRegistrationController::class, 'store'])->name('supplier.register.store');

Route::middleware(['web', 'auth'])->prefix('admin/cetak')->name('admin.cetak.')->group(function () {
    Route::get('/pembelian/{pembelian}', [PdfController::class, 'pembelian'])->name('pembelian');
    Route::get('/penerimaan/{penerimaan}', [PdfController::class, 'penerimaan'])->name('penerimaan');
    Route::get('/pembayaran/{pembayaran}', [PdfController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/penyesuaian/{penyesuaian}', [PdfController::class, 'penyesuaian'])->name('penyesuaian');
    Route::get('/riwayat-stok/{riwayat}', [PdfController::class, 'riwayatStok'])->name('riwayat-stok');
});
