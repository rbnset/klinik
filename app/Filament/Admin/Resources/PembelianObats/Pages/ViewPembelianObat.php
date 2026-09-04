<?php

namespace App\Filament\Admin\Resources\PembelianObats\Pages;

use App\Filament\Admin\Resources\PembelianObats\PembelianObatResource;
use App\Filament\Admin\Resources\Pembayarans\PembayaranResource;
use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Models\PembelianObat;
use App\Services\PurchaseOrderWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Gate;

class ViewPembelianObat extends ViewRecord
{
    protected static string $resource = PembelianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('terimaPesanan')
                ->label('Terima & Konfirmasi Harga')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => auth()->user()?->role === 'supplier' && $this->record->status === 'pending')
                ->modalHeading('Konfirmasi Pesanan dan Harga')
                ->modalDescription('Periksa seluruh harga. Centang “Harga sesuai” jika harga PO sudah benar. Jika berbeda, hapus centang lalu masukkan harga yang Anda tawarkan.')
                ->modalSubmitActionLabel('Kirim Konfirmasi')
                ->modalWidth(Width::SevenExtraLarge)
                ->fillForm(fn (PembelianObat $record): array => [
                    'items' => $record->detail_pembelian()->with('obat')->get()->map(fn ($detail) => [
                        'id' => (int) $detail->id,
                        'nama_obat' => $detail->obat?->nama_obat ?? '-',
                        'harga_awal' => (int) $detail->harga_satuan,
                        'harga_sesuai' => true,
                        'harga_supplier' => (int) $detail->harga_satuan,
                        'catatan_harga_supplier' => null,
                    ])->values()->all(),
                    'catatan' => $record->supplier_catatan,
                ])
                ->schema([
                    Repeater::make('items')
                        ->label('Rincian Harga Obat')
                        ->schema([
                            Hidden::make('id')->required(),
                            TextInput::make('nama_obat')->label('Obat')->disabled()->dehydrated(false),
                            TextInput::make('harga_awal')->label('Harga PO')->prefix('Rp')->disabled()->dehydrated(false),
                            Checkbox::make('harga_sesuai')
                                ->label('Harga sesuai')
                                ->default(true)
                                ->live(),
                            TextInput::make('harga_supplier')
                                ->label('Harga yang Ditawarkan')
                                ->prefix('Rp')
                                ->numeric()
                                ->minValue(0)
                                ->required(fn (Get $get): bool => ! (bool) $get('harga_sesuai'))
                                ->disabled(fn (Get $get): bool => (bool) $get('harga_sesuai')),
                            Textarea::make('catatan_harga_supplier')
                                ->label('Catatan Harga')
                                ->rows(2)
                                ->maxLength(500)
                                ->placeholder('Opsional'),
                        ])
                        ->columns(5)
                        ->reorderable(false)
                        ->addable(false)
                        ->deletable(false),
                    Textarea::make('catatan')
                        ->label('Catatan untuk Gudang')
                        ->rows(3)
                        ->maxLength(1000)
                        ->placeholder('Opsional: tuliskan informasi pengiriman, ketersediaan, atau hal penting lainnya.'),
                ])
                ->action(function (array $data, Action $action): void {
                    $record = $this->record;
                    Gate::authorize('respondAsSupplier', $record);
                    app(PurchaseOrderWorkflowService::class)->supplierConfirm($record, $data['items'] ?? [], $data['catatan'] ?? null);
                    $record->refresh();
                    Notification::make()
                        ->title('Konfirmasi PO berhasil dikirim')
                        ->body($record->status === 'menunggu_konfirmasi_gudang'
                            ? 'Ada perubahan harga. PO menunggu persetujuan gudang.'
                            : 'Harga disetujui dan PO diteruskan ke proses berikutnya.')
                        ->success()
                        ->send();
                    $action->success();
                }),

            Action::make('tolakPesanan')
                ->label('Tolak Pesanan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => auth()->user()?->role === 'supplier' && $this->record->status === 'pending')
                ->requiresConfirmation()
                ->modalHeading('Tolak Purchase Order?')
                ->form([
                    Textarea::make('alasan')->label('Alasan Penolakan')->placeholder('Contoh: stok tidak tersedia atau ketentuan pengiriman tidak dapat dipenuhi.')->required()->rows(4)->maxLength(1000),
                ])
                ->action(function (array $data): void {
                    Gate::authorize('respondAsSupplier', $this->record);
                    app(PurchaseOrderWorkflowService::class)->supplierReject($this->record, $data['alasan']);
                    Notification::make()->title('PO ditolak')->body('Gudang telah diberi tahu mengenai penolakan PO.')->warning()->send();
                    $this->redirect(PembelianObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('setujuiHarga')
                ->label('Setujui Perubahan Harga')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => auth()->user()?->role !== 'supplier' && $this->record->status === 'menunggu_konfirmasi_gudang')
                ->requiresConfirmation()
                ->modalHeading('Setujui perubahan harga supplier?')
                ->modalDescription('Harga supplier akan menjadi harga PO yang disepakati dan status PO berubah menjadi Diproses.')
                ->action(function (): void {
                    Gate::authorize('confirmPrice', $this->record);
                    app(PurchaseOrderWorkflowService::class)->approvePriceChanges($this->record, auth()->id());
                    Notification::make()->title('Perubahan harga disetujui')->success()->send();
                    $this->redirect(PembelianObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('tolakHarga')
                ->label('Tolak Perubahan Harga')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->visible(fn () => auth()->user()?->role !== 'supplier' && $this->record->status === 'menunggu_konfirmasi_gudang')
                ->form([
                    Textarea::make('reason')->label('Alasan')->required()->rows(4)->placeholder('Contoh: harga di luar anggaran atau tidak sesuai kesepakatan.'),
                ])
                ->action(function (array $data): void {
                    Gate::authorize('confirmPrice', $this->record);
                    app(PurchaseOrderWorkflowService::class)->rejectPriceChanges($this->record, auth()->id(), $data['reason']);
                    Notification::make()->title('Perubahan harga ditolak')->body('Supplier akan menerima pemberitahuan untuk meninjau kembali harga.')->warning()->send();
                    $this->redirect(PembelianObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('catatPenerimaan')
                ->label('Catat Penerimaan')->icon('heroicon-o-inbox-arrow-down')->color('success')
                ->url(fn () => PenerimaanObatResource::getUrl('create', ['pembelian' => $this->record->getKey()]))
                ->visible(fn () => auth()->user()?->role !== 'supplier' && $this->record->status === 'diproses' && $this->record->status_penerimaan !== 'lengkap'),
            Action::make('catatPembayaran')
                ->label('Catat Pembayaran')->icon('heroicon-o-banknotes')
                ->url(fn () => PembayaranResource::getUrl('create', ['pembelian' => $this->record->getKey()]))
                ->visible(fn () => auth()->user()?->role !== 'supplier' && in_array($this->record->status, ['diproses', 'selesai'], true) && $this->record->sisa_tagihan > 0),
            Action::make('cetakPdf')->label('Cetak PO')->icon('heroicon-o-printer')
                ->url(fn () => route('admin.cetak.pembelian', ['pembelian' => $this->record]))->openUrlInNewTab(),
            Action::make('cetakRingkasanLengkap')
                ->label('Cetak Ringkasan Pengadaan Lengkap')
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->visible(fn () => $this->record->status === 'selesai' && $this->record->status_pembayaran === 'lunas')
                ->url(fn () => route('admin.cetak.pembelian.ringkasan', ['pembelian' => $this->record]))
                ->openUrlInNewTab(),
            EditAction::make()
                ->visible(fn () => auth()->user()?->role !== 'supplier' && $this->record->status === 'pending' && ! $this->record->supplier_dikonfirmasi_at && ! $this->record->penerimaan_obat()->exists() && ! $this->record->pembayaran()->exists()),
        ];
    }
}
