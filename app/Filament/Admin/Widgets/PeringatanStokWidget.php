<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Obat;
use Filament\Widgets\Widget;

class PeringatanStokWidget extends Widget
{
    // ✅ Hapus "static" agar kompatibel dengan parent class
    protected string $view = 'filament.admin.widgets.peringatan-stok-widget';

    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'obatKritis' => Obat::with('kategori_obat')
                ->where('stok', '<=', 10)
                ->orderBy('stok', 'asc')
                ->get(),
        ];
    }
}
