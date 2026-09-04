<?php

namespace App\Filament\Admin\Resources\Obats\RelationManagers;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RiwayatHargaBeliRelationManager extends RelationManager
{
    protected static string $relationship = 'detail_pembelian';
    protected static ?string $title = 'Riwayat Harga Beli';
    protected static ?string $label = 'Riwayat Harga Beli';
    protected static ?string $pluralLabel = 'Riwayat Harga Beli';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('pembelian_obat.id')
                    ->label('Nomor PO')
                    ->formatStateUsing(fn ($state) => 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                    ->sortable(),
                TextColumn::make('pembelian_obat.supplier.nama_supplier')
                    ->label('Supplier')
                    ->searchable(),
                TextColumn::make('pembelian_obat.tanggal_pesan')
                    ->label('Tanggal Pembelian')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('jumlah_pesan')
                    ->label('Qty')
                    ->numeric(),
                TextColumn::make('harga_satuan')
                    ->label('Harga Beli / Satuan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('pembelian_obat.status')
                    ->label('Status PO')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Menunggu',
                        'diproses' => 'Diproses Supplier',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn ($state): string => match ($state) {
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('pembelian_obat.tanggal_pesan', 'desc')
            ->filters([])
            ->headerActions([])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Model $record): string => PembelianObatResource::getUrl('view', ['record' => $record->id_pembelian_obat])),
            ])
            ->toolbarActions([]);
    }
}
