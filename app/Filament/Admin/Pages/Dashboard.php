<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    // ✅ Hapus "string" dari return type agar kompatibel dengan parent
    public function getColumns(): int | array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\StatsOverview::class,
            \App\Filament\Admin\Widgets\TrenPembelianChart::class,
            \App\Filament\Admin\Widgets\ProporsiStokChart::class,
            \App\Filament\Admin\Widgets\PeringatanStokWidget::class,
            \App\Filament\Admin\Widgets\TopPermintaanWidget::class,
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('bulan')
                    ->label('Filter Bulan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->default(now()->format('m')),

                Select::make('tahun')
                    ->label('Filter Tahun')
                    ->options(array_combine(
                        range(now()->subYears(2)->format('Y'), now()->format('Y')),
                        range(now()->subYears(2)->format('Y'), now()->format('Y'))
                    ))
                    ->default(now()->format('Y')),
            ])
            ->columns(2);
    }
}
