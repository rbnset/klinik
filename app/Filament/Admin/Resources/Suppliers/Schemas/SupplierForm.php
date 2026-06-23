<?php

namespace App\Filament\Admin\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Pemasok')
                    ->description('Informasi detail mengenai perusahaan atau pihak supplier.')
                    ->schema([
                        TextInput::make('nama_supplier')
                            ->label('Nama Perusahaan / Supplier')
                            ->placeholder('Contoh: PT. Kimia Farma')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('no_telp')
                            ->label('Nomor Telepon / WhatsApp')
                            ->placeholder('Contoh: 081234567890')
                            ->tel() // Memunculkan keyboard angka di HP
                            ->required()
                            ->maxLength(15),

                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Masukkan alamat lengkap gudang atau kantor supplier...')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Akses Portal Digital (Opsional)')
                    ->description('Pilih akun pengguna jika supplier ini diizinkan untuk login ke dalam sistem.')
                    ->schema([
                        Select::make('id_pengguna')
                            ->label('Tautkan ke Akun Login')
                            ->relationship('pengguna', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih akun (Kosongkan jika supplier konvensional)'),
                    ])->collapsed(), // Dibuat tertutup (collapse) secara default agar UI minimalis
            ]);
    }
}
