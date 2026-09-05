<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Obat;
use App\Support\DashboardPeriod;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class PemilikTopObatWidget extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Obat Paling Banyak Didistribusikan';

    public static function canView(): bool { return auth()->user()?->role === 'pemilik'; }

    public function table(Table $table): Table
    {
        $p = DashboardPeriod::resolve($this->filters);

        return $table
            ->query(Obat::query()
                ->select('obat.id', 'obat.kode_obat', 'obat.nama_obat', 'obat.satuan')
                ->join('detail_permintaan_obat', 'obat.id', '=', 'detail_permintaan_obat.id_obat')
                ->join('permintaan_obat', 'detail_permintaan_obat.id_permintaan_obat', '=', 'permintaan_obat.id')
                ->whereNotNull('permintaan_obat.disetujui_at')
                ->whereBetween('permintaan_obat.disetujui_at', [$p['start'], $p['end']])
                ->selectRaw('SUM(detail_permintaan_obat.jumlah_disetujui) as total_distribusi')
                ->groupBy('obat.id', 'obat.kode_obat', 'obat.nama_obat', 'obat.satuan')
                ->orderByDesc('total_distribusi')->limit(5))
            ->columns([
                TextColumn::make('kode_obat')->label('SKU')->fontFamily('mono')->color('gray'),
                TextColumn::make('nama_obat')->label('Nama Obat')->weight('bold'),
                TextColumn::make('total_distribusi')->label('Distribusi')->numeric()->suffix(' unit')->badge()->color('primary'),
            ])->paginated(false);
    }
}
