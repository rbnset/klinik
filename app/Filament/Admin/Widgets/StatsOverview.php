<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Obats\ObatResource;
use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Models\Obat;
use App\Models\PembelianObat;
use App\Models\PermintaanObat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $stokKritis = Obat::where('stok', '<=', 10)->count();
        $permintaanPending = PermintaanObat::where('status', 'pending')->count();
        $poBelumSelesai = PembelianObat::whereIn('status', ['pending', 'diproses'])
            ->where('status', '!=', 'dibatalkan')->count();
        $poBelumLunas = PembelianObat::query()->get()->filter(fn ($po) => $po->sisa_tagihan > 0 && $po->status !== 'dibatalkan')->count();

        return [
            Stat::make('Stok Kritis', $stokKritis . ' Obat')
                ->description('Stok ≤ 10 unit')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($stokKritis > 0 ? 'danger' : 'success')
                ->url(ObatResource::getUrl()),
            Stat::make('Permintaan Menunggu', $permintaanPending . ' Dokumen')
                ->description('Perlu diproses gudang')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($permintaanPending > 0 ? 'warning' : 'success')
                ->url(PermintaanObatResource::getUrl()),
            Stat::make('PO Berjalan', $poBelumSelesai . ' PO')
                ->description('Menunggu / diproses supplier')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info')
                ->url(PembelianObatResource::getUrl()),
            Stat::make('PO Belum Lunas', $poBelumLunas . ' PO')
                ->description('Masih memiliki sisa tagihan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($poBelumLunas > 0 ? 'danger' : 'success')
                ->url(PembelianObatResource::getUrl()),
        ];
    }
}
