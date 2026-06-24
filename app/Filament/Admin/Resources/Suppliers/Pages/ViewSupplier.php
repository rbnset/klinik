<?php

namespace App\Filament\Admin\Resources\Suppliers\Pages;

use App\Filament\Admin\Resources\Suppliers\SupplierResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplier extends ViewRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getView(): string
    {
        return 'admin.suppliers.show';
    }
}
