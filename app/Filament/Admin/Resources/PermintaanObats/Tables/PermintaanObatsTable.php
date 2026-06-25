<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Tables;

use App\Models\PermintaanObat;
use App\Notifications\PermintaanObatDiperbarui;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PermintaanObatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->prefix('REQ-')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('pengguna.name')
                    ->label('Pemohon')
                    ->searchable(),

                TextColumn::make('tanggal_permintaan')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending'   => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                        default     => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'disetujui' => 'success',
                        'ditolak'   => 'danger',
                        default     => 'gray',
                    }),

                TextColumn::make('detail_permintaan_count')
                    ->label('Jml. Item')
                    ->counts('detail_permintaan')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(function ($record) {

                        $user = auth()->user();

                        if ($user->role === 'karyawan') {
                            return true;
                        }

                        return $record->id_pengguna === $user->id
                            && $record->status === 'pending';
                    }),
            ])
            ->toolbarActions([]);
    }
}
