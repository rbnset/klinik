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
use Filament\Notifications\Notification;

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
                        ->visible(fn ($record): bool => $record->status_pengajuan === 'menunggu')
                        ->action(function ($record): void {
                            $record->update(['status_pengajuan' => 'disetujui']);
                            Notification::make()->title('Pengajuan supplier disetujui')->success()->send();
                        }),
                    Action::make('tolak')
                        ->label('Tolak Pengajuan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak pengajuan supplier?')
                        ->modalDescription('Akun supplier tidak dapat masuk selama status pengajuan ditolak.')
                        ->visible(fn ($record): bool => $record->status_pengajuan === 'menunggu')
                        ->action(function ($record): void {
                            $record->update(['status_pengajuan' => 'ditolak']);
                            Notification::make()->title('Pengajuan supplier ditolak')->danger()->send();
                        }),
                ])->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
