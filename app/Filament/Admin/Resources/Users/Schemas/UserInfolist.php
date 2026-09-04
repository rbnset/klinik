<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Akun')
                ->description('Identitas pengguna dan hak akses yang diberikan dalam sistem.')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    TextEntry::make('name')->label('Nama Lengkap')->weight('bold')->size('lg'),
                    TextEntry::make('email')->label('Alamat Email')->copyable(),
                    TextEntry::make('role')
                        ->label('Hak Akses / Role')
                        ->badge()
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'admin' => 'Administrator',
                            'karyawan' => 'Karyawan',
                            'bidan' => 'Bidan / Tenaga Medis',
                            'pemilik' => 'Pemilik Klinik',
                            'supplier' => 'Akun Portal Supplier',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'admin' => 'danger',
                            'karyawan' => 'info',
                            'bidan' => 'success',
                            'pemilik' => 'warning',
                            'supplier' => 'gray',
                            default => 'gray',
                        }),
                ])->columns(2),

            Section::make('Informasi Sistem')
                ->icon('heroicon-o-clock')
                ->collapsed()
                ->schema([
                    TextEntry::make('created_at')->label('Akun Dibuat')->dateTime('d M Y, H:i')->placeholder('-'),
                    TextEntry::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(2),
        ]);
    }
}
