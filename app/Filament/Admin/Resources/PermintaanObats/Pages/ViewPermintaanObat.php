<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Pages;

use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Models\PermintaanObat;
use App\Services\PermintaanObatWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Gate;

class ViewPermintaanObat extends ViewRecord
{
    protected static string $resource = PermintaanObatResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        return [
            Action::make('setujui')
                ->label('Setujui & Siapkan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => in_array($user?->role, ['admin', 'karyawan'], true) && $this->record->status === 'pending')
                ->modalHeading('Periksa & Siapkan Obat')
                ->modalDescription('Centang obat yang benar-benar disiapkan. Jumlah dapat dikurangi dari jumlah yang diminta. Stok akan berkurang hanya untuk item yang disetujui.')
                ->modalSubmitActionLabel('Setujui & Siapkan')
                ->modalWidth(Width::FiveExtraLarge)
                ->schema([
                    Repeater::make('items')
                        ->label('Rincian Keputusan Gudang')
                        ->schema([
                            Hidden::make('id'),
                            TextInput::make('nama_obat')
                                ->label('Obat')
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('jumlah_diminta')
                                ->label('Diminta')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('stok_tersedia')
                                ->label('Stok Tersedia')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(false),
                            Checkbox::make('siapkan')
                                ->label('Siapkan obat ini')
                                ->live()
                                ->default(true)
                                ->afterStateUpdated(function (Set $set, $state, Get $get): void {
                                    if (! $state) {
                                        $set('jumlah_disetujui', 0);
                                    } elseif ((int) $get('jumlah_disetujui') <= 0) {
                                        $set('jumlah_disetujui', (int) $get('jumlah_diminta'));
                                    }
                                }),
                            TextInput::make('jumlah_disetujui')
                                ->label('Jumlah Disetujui')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->maxValue(fn (Get $get) => (int) $get('jumlah_diminta'))
                                ->required(fn (Get $get) => (bool) $get('siapkan'))
                                ->disabled(fn (Get $get) => ! (bool) $get('siapkan'))
                                ->helperText(fn (Get $get) => 'Maks. ' . (int) $get('jumlah_diminta') . ' ' . ($get('satuan') ?: 'unit')),
                            Hidden::make('satuan'),
                        ])
                        ->columns(5)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false),
                    Textarea::make('catatan')
                        ->label('Catatan Gudang')
                        ->rows(3)
                        ->maxLength(1000)
                        ->placeholder('Opsional. Contoh: obat disiapkan lengkap / sebagian karena stok terbatas.'),
                ])
                ->fillForm(fn (PermintaanObat $record): array => [
                    'items' => $record->load('detail_permintaan.obat')->detail_permintaan->map(fn ($detail) => [
                        'id' => $detail->id,
                        'nama_obat' => $detail->obat?->nama_obat ?? 'Obat tidak ditemukan',
                        'jumlah_diminta' => (int) $detail->jumlah_diminta,
                        'stok_tersedia' => (int) ($detail->obat?->stok ?? 0),
                        'satuan' => $detail->obat?->satuan ?? 'unit',
                        'siapkan' => (int) ($detail->jumlah_disetujui ?? 0) > 0 || $detail->jumlah_disetujui === null,
                        'jumlah_disetujui' => (int) ($detail->jumlah_disetujui ?: $detail->jumlah_diminta),
                    ])->values()->all(),
                ])
                ->action(function (array $data): void {
                    Gate::authorize('approve', $this->record);

                    app(PermintaanObatWorkflowService::class)->approve(
                        $this->record,
                        auth()->id(),
                        $data['items'] ?? [],
                        $data['catatan'] ?? null,
                    );

                    Notification::make()
                        ->title('Permintaan disetujui & obat disiapkan')
                        ->body('Jumlah yang dicentang telah diposting sebagai pengeluaran stok. Permintaan siap diserahkan kepada bidan.')
                        ->success()
                        ->send();

                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('tolak')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => in_array($user?->role, ['admin', 'karyawan'], true) && $this->record->status === 'pending')
                ->schema([
                    Textarea::make('alasan')->label('Alasan Penolakan')->required()->rows(4)->maxLength(1000),
                ])
                ->modalHeading('Tolak Permintaan')
                ->modalSubmitActionLabel('Tolak Permintaan')
                ->action(function (array $data): void {
                    Gate::authorize('reject', $this->record);
                    app(PermintaanObatWorkflowService::class)->reject($this->record, auth()->id(), $data['alasan']);
                    Notification::make()->title('Permintaan ditolak')->body('Bidan pemohon telah diberi tahu beserta alasan penolakan.')->warning()->send();
                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('serahkan')
                ->label('Tandai Sudah Diserahkan')
                ->icon('heroicon-o-hand-thumb-up')
                ->color('info')
                ->visible(fn () => in_array($user?->role, ['admin', 'karyawan'], true) && $this->record->status === 'disetujui')
                ->schema([
                    Textarea::make('catatan')->label('Catatan Serah Terima')->rows(3)->maxLength(1000)->placeholder('Opsional.'),
                ])
                ->action(function (array $data): void {
                    Gate::authorize('handover', $this->record);
                    app(PermintaanObatWorkflowService::class)->handover($this->record, auth()->id(), $data['catatan'] ?? null);
                    Notification::make()->title('Serah terima dicatat')->body('Bidan telah diberi notifikasi untuk mengonfirmasi penerimaan.')->success()->send();
                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('konfirmasi')
                ->label('Konfirmasi Obat Diterima')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $user?->role === 'bidan' && $this->record->status === 'diserahkan' && (int) $this->record->id_pengguna === (int) $user->id)
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penerimaan Obat')
                ->modalDescription('Pastikan obat sudah diterima secara fisik. Setelah dikonfirmasi, permintaan ditutup sebagai Selesai.')
                ->action(function (): void {
                    Gate::authorize('confirmReceived', $this->record);
                    app(PermintaanObatWorkflowService::class)->confirmReceived($this->record, auth()->id());
                    Notification::make()->title('Penerimaan dikonfirmasi')->body('Permintaan internal selesai.')->success()->send();
                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            Action::make('batalkan')
                ->label('Batalkan Permintaan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $user?->role === 'bidan' && $this->record->status === 'pending' && (int) $this->record->id_pengguna === (int) $user->id)
                ->requiresConfirmation()
                ->action(function (): void {
                    Gate::authorize('update', $this->record);
                    app(PermintaanObatWorkflowService::class)->cancel($this->record, auth()->id());
                    Notification::make()->title('Permintaan dibatalkan')->success()->send();
                    $this->redirect(PermintaanObatResource::getUrl('view', ['record' => $this->record->getKey()]));
                }),

            EditAction::make()
                ->visible(fn () => $user?->role === 'bidan' && (int) $this->record->id_pengguna === (int) $user->id && $this->record->status === 'pending'),
        ];
    }
}
