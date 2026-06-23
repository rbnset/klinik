<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Profil Pengguna')
                    ->schema([
                        TextEntry::make('name')->label('Nama Lengkap')->weight('bold')->size('lg'),
                        TextEntry::make('email')->label('Alamat Email')->icon('heroicon-m-envelope'),

                        TextEntry::make('role')->label('Hak Akses / Otoritas')->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'admin' => 'danger',
                                'karyawan' => 'info',
                                'bidan' => 'success',
                                'pemilik' => 'warning',
                                'supplier' => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => strtoupper($state)),

                        TextEntry::make('created_at')->label('Akun Dibuat Pada')->dateTime('d F Y - H:i'),
                    ])->columns(2)
            ]);
    }
}
