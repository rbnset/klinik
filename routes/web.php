<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin/cetak')->name('admin.cetak.')->group(function () {
    Route::get('/pembelian/{pembelian}', [PdfController::class, 'pembelian'])->name('pembelian');
    Route::get('/penerimaan/{penerimaan}', [PdfController::class, 'penerimaan'])->name('penerimaan');
    Route::get('/pembayaran/{pembayaran}', [PdfController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/penyesuaian/{penyesuaian}', [PdfController::class, 'penyesuaian'])->name('penyesuaian');
    Route::get('/riwayat-stok/{riwayat}', [PdfController::class, 'riwayatStok'])->name('riwayat-stok');
});
