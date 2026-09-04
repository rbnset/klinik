<?php

namespace App\Filament\Admin\Resources\Pembayarans\Tables;

use App\Models\Pembayaran;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('No. Pembayaran')->formatStateUsing(fn ($state) => 'PAY-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->fontFamily('mono')->weight('bold'),
                TextColumn::make('pembelian_obat.id')->label('No. PO')->formatStateUsing(fn ($state) => 'PO-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))->sortable(),
                TextColumn::make('pembelian_obat.supplier.nama_supplier')->label('Supplier')->searchable(),
                TextColumn::make('tanggal_bayar')->label('Tanggal Bayar')->date('d M Y')->sortable(),
                TextColumn::make('metode_pembayaran')->label('Metode')->badge()->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('total_bayar')->label('Jumlah Bayar')->money('IDR', locale: 'id')->sortable(),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn ($state) => match ($state) { 'menunggu_supplier' => 'Menunggu Supplier', 'disetujui_supplier' => 'Disetujui Supplier', 'ditolak_supplier' => 'Ditolak Supplier', default => ucfirst((string) $state), })->color(fn ($state) => $state === 'disetujui_supplier' ? 'success' : ($state === 'ditolak_supplier' ? 'danger' : 'warning')),
            ])
            ->defaultSort('tanggal_bayar', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Status')->options([
                    'menunggu_supplier'=>'Menunggu Supplier','disetujui_supplier'=>'Disetujui','ditolak_supplier'=>'Ditolak',
                ]),
                SelectFilter::make('metode_pembayaran')->label('Metode')->options(['tunai'=>'Tunai','transfer'=>'Transfer']),
                SelectFilter::make('id_pembelian_obat')->label('PO')->relationship('pembelian_obat', 'id')->searchable()->preload(),
                Filter::make('periode')->label('Periode Pembayaran')->form([DatePicker::make('dari')->label('Dari'), DatePicker::make('sampai')->label('Sampai')])->columns(2)
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['dari'] ?? null, fn ($q, $date) => $q->whereDate('tanggal_bayar', '>=', $date))
                        ->when($data['sampai'] ?? null, fn ($q, $date) => $q->whereDate('tanggal_bayar', '<=', $date))),
                Filter::make('jumlah')->label('Rentang Jumlah')->form([
                    TextInput::make('min')->label('Minimal')->numeric()->prefix('Rp'),
                    TextInput::make('max')->label('Maksimal')->numeric()->prefix('Rp'),
                ])->columns(2)->query(fn (Builder $query, array $data) => $query
                    ->when($data['min'] ?? null, fn ($q, $value) => $q->where('total_bayar', '>=', $value))
                    ->when($data['max'] ?? null, fn ($q, $value) => $q->where('total_bayar', '<=', $value))),
            ])
            ->emptyStateHeading('Belum ada pembayaran')
            ->emptyStateDescription('Pembayaran yang dicatat terhadap PO akan tampil di sini.')
            ->striped()
            ->recordActions([ActionGroup::make([ViewAction::make(), Action::make('cetakPdf')->label('Cetak PDF')->icon('heroicon-o-printer')->url(fn (Pembayaran $record) => route('admin.cetak.pembayaran', ['pembayaran' => $record]))->openUrlInNewTab(), EditAction::make()->visible(fn (Pembayaran $record) => auth()->user()?->role !== 'supplier' && $record->status !== 'disetujui_supplier')])->icon('heroicon-m-ellipsis-vertical')])
            ->toolbarActions([]);
    }
}
