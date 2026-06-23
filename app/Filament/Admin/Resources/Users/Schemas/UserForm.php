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
            ->components([
                Section::make('Informasi Akun')
                    ->description('Pastikan alamat email valid dan kata sandi disimpan dengan aman.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('role')
                            ->label('Hak Akses (Role)')
                            ->options([
                                'admin' => 'Administrator',
                                'karyawan' => 'Karyawan (Gudang/Apotek)',
                                'bidan' => 'Bidan / Tenaga Medis',
                                'pemilik' => 'Pemilik Klinik (Owner)',
                                'supplier' => 'Akun Portal Supplier',
                            ])
                            ->required(),

                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password() // Sensor teks menjadi titik-titik
                            ->revealable() // Fitur ikon mata untuk melihat sandi sementara
                            ->dehydrateStateUsing(fn($state) => Hash::make($state)) // Enkripsi sandi sebelum masuk database
                            ->dehydrated(fn($state) => filled($state)) // Hanya dikirim ke DB jika ada isinya
                            ->required(fn(string $operation): bool => $operation === 'create') // Wajib saat bikin baru, opsional saat edit
                            ->helperText('Kosongkan kolom ini jika Anda tidak ingin mengubah kata sandi saat mode Edit.'),
                    ])->columns(2),
            ]);
    }
}
