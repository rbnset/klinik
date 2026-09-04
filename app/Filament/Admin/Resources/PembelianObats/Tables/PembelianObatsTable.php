<?php

namespace App\Filament\Admin\Resources\PembelianObats\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PembelianObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('No. PO')->formatStateUsing(fn($state)=>'PO-'.str_pad((string)$state,5,'0',STR_PAD_LEFT))->sortable()->weight('bold'),
            TextColumn::make('supplier.nama_supplier')->label('Supplier')->searchable()->weight('medium'),
            TextColumn::make('tanggal_pesan')->label('Tanggal Pesan')->date('d M Y')->sortable(),
            TextColumn::make('total_pesanan')->label('Nilai PO')->money('IDR', locale:'id')->state(fn($record)=>$record->total_pesanan),
            TextColumn::make('status')->label('Status PO')->badge()
                ->formatStateUsing(fn($state)=>match($state){'pending'=>'Menunggu Supplier','diproses'=>'Diproses','menunggu_konfirmasi_gudang'=>'Menunggu Konfirmasi Gudang','ditolak_supplier'=>'Ditolak Supplier','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan',default=>$state})
                ->color(fn($state)=>match($state){'pending'=>'warning','diproses'=>'info','menunggu_konfirmasi_gudang'=>'warning','ditolak_supplier'=>'danger','selesai'=>'success','dibatalkan'=>'danger',default=>'gray'}),
            TextColumn::make('progress_penerimaan')
                ->label('Penerimaan')
                ->state(fn ($record) => $record->total_item > 0 ? $record->total_item_diterima . ' / ' . $record->total_item . ' item' : '—')
                ->badge()
                ->color(fn ($record) => match ($record->status_penerimaan) { 'lengkap' => 'success', 'sebagian' => 'warning', default => 'gray' }),
            TextColumn::make('status_pembayaran')->label('Pembayaran')->badge()->visible(fn () => auth()->user()?->role !== 'supplier')->formatStateUsing(fn($state)=>match($state){'belum_dibayar'=>'Belum Dibayar','sebagian'=>'Sebagian','lunas'=>'Lunas',default=>$state})->color(fn($state)=>match($state){'belum_dibayar'=>'danger','sebagian'=>'warning','lunas'=>'success',default=>'gray'}),
        ])->defaultSort('created_at','desc')->striped()
            ->filters([
                SelectFilter::make('status')->label('Status PO')->options([
                    'pending'=>'Menunggu Supplier','diproses'=>'Diproses','menunggu_konfirmasi_gudang'=>'Menunggu Konfirmasi Gudang','ditolak_supplier'=>'Ditolak Supplier','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'
                ]),
                SelectFilter::make('id_supplier')->label('Supplier')->relationship('supplier', 'nama_supplier')->searchable()->preload(),
                Filter::make('periode')->label('Periode Pemesanan')->form([
                    DatePicker::make('dari')->label('Dari'),
                    DatePicker::make('sampai')->label('Sampai'),
                ])->columns(2)->query(fn (Builder $query, array $data) => $query
                    ->when($data['dari'] ?? null, fn ($q, $date) => $q->whereDate('tanggal_pesan', '>=', $date))
                    ->when($data['sampai'] ?? null, fn ($q, $date) => $q->whereDate('tanggal_pesan', '<=', $date))),
                SelectFilter::make('status_pembayaran')->label('Pembayaran')->options([
                    'belum_dibayar'=>'Belum Dibayar','sebagian'=>'Sebagian','lunas'=>'Lunas'
                ])->query(function (Builder $query, array $data) {
                    $value = $data['value'] ?? null;
                    if ($value === 'lunas') {
                        return $query->whereRaw("(SELECT COALESCE(SUM(pb.total_bayar), 0) FROM pembayaran pb WHERE pb.id_pembelian_obat = pembelian_obat.id AND pb.status = 'disetujui_supplier') >= (SELECT COALESCE(SUM(dp.jumlah_pesan * dp.harga_satuan), 0) FROM detail_pembelian_obat dp WHERE dp.id_pembelian_obat = pembelian_obat.id)");
                    }
                    if ($value === 'sebagian') {
                        return $query->whereRaw("(SELECT COALESCE(SUM(pb.total_bayar), 0) FROM pembayaran pb WHERE pb.id_pembelian_obat = pembelian_obat.id AND pb.status = 'disetujui_supplier') > 0")
                            ->whereRaw("(SELECT COALESCE(SUM(pb.total_bayar), 0) FROM pembayaran pb WHERE pb.id_pembelian_obat = pembelian_obat.id AND pb.status = 'disetujui_supplier') < (SELECT COALESCE(SUM(dp.jumlah_pesan * dp.harga_satuan), 0) FROM detail_pembelian_obat dp WHERE dp.id_pembelian_obat = pembelian_obat.id)");
                    }
                    if ($value === 'belum_dibayar') {
                        return $query->whereRaw("(SELECT COALESCE(SUM(pb.total_bayar), 0) FROM pembayaran pb WHERE pb.id_pembelian_obat = pembelian_obat.id AND pb.status = 'disetujui_supplier') = 0");
                    }
                    return $query;
                }),
            ])
            ->emptyStateHeading(fn (): string => auth()->user()?->role === 'supplier' ? 'Belum ada PO untuk Anda' : 'Belum ada pemesanan')
            ->emptyStateDescription(fn (): string => auth()->user()?->role === 'supplier' ? 'PO yang ditujukan kepada perusahaan Anda akan muncul di sini.' : 'Buat PO pertama untuk memulai proses pengadaan.')
            ->recordActions([ActionGroup::make([
                ViewAction::make(),
                Action::make('cetakPdf')->label('Cetak PDF')->icon('heroicon-o-printer')->url(fn ($record) => route('admin.cetak.pembelian', ['pembelian' => $record]))->openUrlInNewTab(),
                EditAction::make()->visible(fn ($record) => auth()->user()?->role !== 'supplier' && $record->status === 'pending' && ! $record->supplier_dikonfirmasi_at && ! $record->penerimaan_obat()->exists() && ! $record->pembayaran()->exists()),
            ])->icon('heroicon-m-ellipsis-vertical')])
            ->toolbarActions([]);
    }
}
