<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Pages;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditPenerimaanObat extends EditRecord
{
    protected static string $resource = PenerimaanObatResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->stok_diposting_at) {
            abort(403, 'Penerimaan yang sudah diposting tidak dapat diubah.');
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = collect($data['detail_penerimaan'] ?? [])
            ->filter(fn (array $item): bool => (int) ($item['jumlah_diterima'] ?? 0) > 0)
            ->map(fn (array $item): array => [
                'id_detail_pembelian' => (int) $item['id_detail_pembelian'],
                'jumlah_diterima' => (int) $item['jumlah_diterima'],
            ])
            ->values()
            ->all();

        if ($items === []) {
            throw ValidationException::withMessages([
                'detail_penerimaan' => 'Minimal satu obat harus diterima.',
            ]);
        }

        $data['detail_penerimaan'] = $items;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }
}
