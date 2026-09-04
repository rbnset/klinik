<?php

namespace App\Filament\Admin\Resources\PembelianObats\RelationManagers;

use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use App\Models\Pembayaran;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';
    protected static ?string $title = 'Pembayaran Tagihan';
    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (Model $record): string => 'Pembayaran #' . $record->getKey())
            ->columns([
                TextColumn::make('id')->label('No. Pembayaran')->formatStateUsing(fn ($state) => 'PAY-' . str_pad((string)$state, 5, '0', STR_PAD_LEFT))->weight('bold'),
                TextColumn::make('tanggal_bayar')->label('Tanggal Bayar')->date('d M Y')->sortable(),
                TextColumn::make('metode_pembayaran')->label('Metode')->badge()->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('total_bayar')->label('Jumlah Bayar')->money('IDR', locale: 'id')->sortable(),
            ])
            ->headerActions([
                Action::make('catatPembayaran')->label('Catat Pembayaran')->icon('heroicon-o-banknotes')->url(fn () => PembayaranResource::getUrl('create', ['pembelian' => $this->getOwnerRecord()->getKey()])),
            ])
            ->recordActions([
                ViewAction::make()->url(fn (Pembayaran $record) => PembayaranResource::getUrl('view', ['record' => $record])),
                EditAction::make()->url(fn (Pembayaran $record) => PembayaranResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
