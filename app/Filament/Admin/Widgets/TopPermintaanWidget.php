<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Obat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopPermintaanWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = '5 Obat Terbanyak Diminta (Distribusi Internal)';

    public function table(Table $table): Table
    {
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        return $table
            ->query(
                // PERBAIKAN: Menggunakan model Obat sebagai pondasi query utama
                Obat::query()
                    ->select('obat.id', 'obat.nama_obat', 'obat.kode_obat')
                    ->selectRaw('SUM(detail_permintaan_obat.jumlah_disetujui) as total_keluar')
                    ->join('detail_permintaan_obat', 'obat.id', '=', 'detail_permintaan_obat.id_obat')
                    ->join('permintaan_obat', 'detail_permintaan_obat.id_permintaan_obat', '=', 'permintaan_obat.id')
                    ->whereMonth('permintaan_obat.tanggal_permintaan', $bulan)
                    ->whereYear('permintaan_obat.tanggal_permintaan', $tahun)
                    ->where('permintaan_obat.status', 'disetujui')
                    // Karena obat.id ikut digrup, Filament tidak akan memicu error strict mode
                    ->groupBy('obat.id', 'obat.nama_obat', 'obat.kode_obat')
                    ->orderByDesc('total_keluar')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('kode_obat')
                    ->label('SKU')
                    ->fontFamily('mono')
                    ->color('gray'),

                TextColumn::make('nama_obat')
                    ->label('Nama Obat')
                    ->weight('bold'),

                TextColumn::make('total_keluar')
                    ->label('Total Distribusi')
                    ->badge()
                    ->color('success')
                    ->suffix(' Unit'),
            ])
            ->paginated(false);
    }
}
