<?php
namespace App\Filament\Admin\Resources\PenerimaanObats\Pages;
use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Services\StokObatService;
use Filament\Resources\Pages\CreateRecord;
class CreatePenerimaanObat extends CreateRecord
{
 protected static string $resource=PenerimaanObatResource::class;
 protected function afterCreate(): void { app(StokObatService::class)->postingPenerimaan($this->record); }
 protected function getRedirectUrl(): string { return \App\Filament\Admin\Resources\PembelianObats\PembelianObatResource::getUrl('view',['record'=>$this->record->id_pembelian_obat]); }
}
