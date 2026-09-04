<?php

namespace App\Filament\Admin\Resources\PembelianObats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
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
        ])->defaultSort('created_at','desc')->striped()->recordActions([ViewAction::make(), EditAction::make()])->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
