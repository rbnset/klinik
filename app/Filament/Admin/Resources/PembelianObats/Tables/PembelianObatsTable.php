<?php

namespace App\Filament\Admin\Resources\PembelianObats\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PembelianObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('No. PO')->formatStateUsing(fn($state)=>'PO-'.str_pad((string)$state,5,'0',STR_PAD_LEFT))->sortable()->weight('bold'),
            TextColumn::make('supplier.nama_supplier')->label('Supplier')->searchable()->weight('medium'),
            TextColumn::make('tanggal_pesan')->label('Tanggal Pesan')->date('d M Y')->sortable(),
            TextColumn::make('total_pesanan')->label('Nilai PO')->money('IDR', locale:'id')->state(fn($record)=>$record->total_pesanan),
            TextColumn::make('status')->label('Status PO')->badge()->formatStateUsing(fn($state)=>match($state){'pending'=>'Menunggu','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan',default=>$state})->color(fn($state)=>match($state){'pending'=>'warning','diproses'=>'info','selesai'=>'success','dibatalkan'=>'danger',default=>'gray'}),
            TextColumn::make('status_pembayaran')->label('Pembayaran')->badge()->formatStateUsing(fn($state)=>match($state){'belum_dibayar'=>'Belum Dibayar','sebagian'=>'Sebagian','lunas'=>'Lunas',default=>$state})->color(fn($state)=>match($state){'belum_dibayar'=>'danger','sebagian'=>'warning','lunas'=>'success',default=>'gray'}),
        ])->defaultSort('created_at','desc')->striped()
            ->filters([SelectFilter::make('status')->label('Status PO')->options(['pending'=>'Menunggu','diproses'=>'Diproses Supplier','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan']), SelectFilter::make('status_pembayaran')->label('Pembayaran')->options(['belum_dibayar'=>'Belum Dibayar','sebagian'=>'Sebagian','lunas'=>'Lunas'])])
            ->emptyStateHeading('Belum ada pemesanan')
            ->emptyStateDescription('Buat PO pertama untuk memulai proses pengadaan.')
            ->recordActions([ActionGroup::make([ViewAction::make(), Action::make('cetakPdf')->label('Cetak PDF')->icon('heroicon-o-printer')->url(fn ($record) => route('admin.cetak.pembelian', ['pembelian' => $record]))->openUrlInNewTab(), EditAction::make()->visible(fn ($record) => ! $record->penerimaan_obat()->exists() && ! $record->pembayaran()->exists())])->icon('heroicon-m-ellipsis-vertical')])
            ->toolbarActions([]);
    }
}
