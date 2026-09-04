<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Tables;

use App\Models\PenerimaanObat;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PenerimaanObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('No. GR')->formatStateUsing(fn ($state) => 'GR-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->fontFamily('mono')->weight('bold'),
                TextColumn::make('id_pembelian_obat')->label('No. PO')->formatStateUsing(fn ($state) => 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->sortable(),
                TextColumn::make('nomor_faktur')->label('No. Faktur')->searchable()->weight('bold'),
                TextColumn::make('tanggal_terima')->label('Tanggal Terima')->date('d M Y')->sortable(),
                TextColumn::make('stok_diposting_at')->label('Status Stok')->badge()->formatStateUsing(fn ($state) => $state ? 'Diposting' : 'Belum Diposting')->color(fn ($state) => $state ? 'success' : 'warning'),
            ])
            ->defaultSort('tanggal_terima', 'desc')
            ->filters([SelectFilter::make('stok_diposting_at')->label('Status Stok')->options(['1'=>'Sudah Diposting','0'=>'Belum Diposting'])->query(function ($query, array $data) { if (($data['value'] ?? null) === '1') $query->whereNotNull('stok_diposting_at'); if (($data['value'] ?? null) === '0') $query->whereNull('stok_diposting_at'); })])
            ->emptyStateHeading('Belum ada penerimaan')
            ->emptyStateDescription('Penerimaan barang dari supplier akan tampil di sini.')
            ->striped()
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('cetakPdf')->label('Cetak PDF')->icon('heroicon-o-printer')->url(fn (PenerimaanObat $record) => route('admin.cetak.penerimaan', ['penerimaan' => $record]))->openUrlInNewTab(),
                    EditAction::make()->visible(fn (PenerimaanObat $record) => ! $record->stok_diposting_at),
                ])->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([]);
    }
}
