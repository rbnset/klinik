<?php

namespace App\Filament\Admin\Resources\PembelianObats\RelationManagers;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Models\PenerimaanObat;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PenerimaanObatRelationManager extends RelationManager
{
    protected static string $relationship = 'penerimaan_obat';
    protected static ?string $title = 'Penerimaan & Faktur';
    protected static ?string $recordTitleAttribute = 'nomor_faktur';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (Model $record): string => $record->nomor_faktur)
            ->columns([
                TextColumn::make('id')->label('No. GR')->formatStateUsing(fn ($state) => 'GR-' . str_pad((string)$state, 5, '0', STR_PAD_LEFT))->weight('bold'),
                TextColumn::make('nomor_faktur')->label('No. Faktur')->searchable()->weight('bold'),
                TextColumn::make('tanggal_terima')->label('Tanggal Terima')->date('d M Y')->sortable(),
                TextColumn::make('detail_penerimaan_sum_jumlah_diterima')->label('Qty Diterima')->state(fn (PenerimaanObat $record) => $record->detail_penerimaan()->sum('jumlah_diterima'))->numeric(),
                TextColumn::make('stok_diposting_at')->label('Status Stok')->badge()->formatStateUsing(fn ($state) => $state ? 'Sudah Diposting' : 'Belum Diposting')->color(fn ($state) => $state ? 'success' : 'warning'),
            ])
            ->emptyStateHeading('Belum ada penerimaan')
            ->emptyStateDescription('Catat penerimaan langsung dari PO ini setelah barang datang.')
            ->headerActions([
                Action::make('catatPenerimaan')->label('Catat Penerimaan')->icon('heroicon-o-clipboard-document-check')->url(fn () => PenerimaanObatResource::getUrl('create', ['pembelian' => $this->getOwnerRecord()->getKey()]))->visible(fn () => $this->getOwnerRecord()->status !== 'dibatalkan' && $this->getOwnerRecord()->status_penerimaan !== 'lengkap'),
            ])
            ->recordActions([ActionGroup::make([
                ViewAction::make()->url(fn (PenerimaanObat $record) => PenerimaanObatResource::getUrl('view', ['record' => $record])),
                EditAction::make()->url(fn (PenerimaanObat $record) => PenerimaanObatResource::getUrl('edit', ['record' => $record]))->visible(fn (PenerimaanObat $record) => ! $record->stok_diposting_at),
            ])->icon('heroicon-m-ellipsis-vertical')]);
    }
}
