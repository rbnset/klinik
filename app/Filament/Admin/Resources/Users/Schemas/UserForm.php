<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Akun')
                    ->description('Kelola identitas pengguna, email, dan hak akses sistem.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255),
                        TextInput::make('email')->label('Alamat Email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                        Select::make('role')
                            ->label('Hak Akses / Role')
                            ->options([
                                'admin' => 'Administrator',
                                'karyawan' => 'Karyawan (Gudang/Apotek)',
                                'bidan' => 'Bidan / Tenaga Medis',
                                'pemilik' => 'Pemilik Klinik (Owner)',
                                'supplier' => 'Akun Portal Supplier',
                            ])->required(),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->helperText('Kosongkan saat Edit jika kata sandi tidak ingin diubah.'),
                    ])->columns(2),
            ]);
    }
}
