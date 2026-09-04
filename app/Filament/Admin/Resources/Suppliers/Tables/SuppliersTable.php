<?php

namespace App\Filament\Admin\Resources\Suppliers\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use App\Notifications\PengajuanSupplierDiperbarui;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Gate;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('no_telp')
                    ->label('Nomor Kontak')
                    ->icon('heroicon-m-phone') // Menambahkan ikon kecil di samping nomor
                    ->searchable(),

                TextColumn::make('pengguna.name')
                    ->label('Akun Portal')
                    ->badge() // Ditampilkan dalam bentuk kotak lencana (badge)
                    ->color('success')
                    ->placeholder('Tanpa Akun (Manual)'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                SelectFilter::make('status_pengajuan')
                    ->label('Status Pengajuan')
                    ->options([
                        'menunggu' => 'Menunggu Verifikasi',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->emptyStateHeading('Belum ada supplier')
            ->emptyStateDescription('Tambahkan supplier untuk membuat pemesanan obat.')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('setujui')
                        ->label('Setujui Pengajuan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui akun supplier?')
                        ->modalDescription('Supplier akan dapat mengakses sistem setelah pengajuan disetujui.')
                        ->visible(fn ($record): bool => auth()->user()?->role === 'admin' && $record->status_pengajuan === 'menunggu')
                        ->action(function ($record): void {
                            Gate::authorize('update', $record);
                            $record->update([
                                'status_pengajuan' => 'disetujui',
                                'alasan_penolakan' => null,
                                'ditolak_at' => null,
                                'pengajuan_dapat_diajukan_lagi_at' => null,
                            ]);
                            $record->pengguna?->notify(new PengajuanSupplierDiperbarui($record, 'disetujui'));
                            Notification::make()->title('Pengajuan supplier disetujui')->success()->send();
                        }),
                    Action::make('tolak')
                        ->label('Tolak Pengajuan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak pengajuan supplier?')
                        ->modalDescription('Tuliskan alasan yang dapat dipahami supplier. Alasan ini akan ditampilkan saat supplier mencoba masuk.')
                        ->form([
                            Textarea::make('alasan_penolakan')
                                ->label('Alasan Penolakan')
                                ->placeholder('Contoh: Dokumen legalitas perusahaan belum lengkap.')
                                ->required()
                                ->rows(4)
                                ->maxLength(1000),
                        ])
                        ->visible(fn ($record): bool => auth()->user()?->role === 'admin' && $record->status_pengajuan === 'menunggu')
                        ->action(function ($record, array $data): void {
                            Gate::authorize('update', $record);
                            $reason = trim((string) ($data['alasan_penolakan'] ?? ''));
                            $record->update([
                                'status_pengajuan' => 'ditolak',
                                'alasan_penolakan' => $reason,
                                'ditolak_at' => now(),
                                'pengajuan_dapat_diajukan_lagi_at' => now()->addDays(3),
                            ]);
                            $record->pengguna?->notify(new PengajuanSupplierDiperbarui($record, 'ditolak'));
                            Notification::make()->title('Pengajuan supplier ditolak')->body('Supplier dapat melihat alasan penolakan dan waktu pengajuan ulang saat login.')->danger()->send();
                        }),
                ])->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn (): bool => auth()->user()?->role === 'admin'),
            ]);
    }
}
