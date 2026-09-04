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
        return $schema->components([
            Section::make('Profil Supplier')
                ->description('Lengkapi identitas dan informasi kontak pemasok obat.')
                ->icon('heroicon-o-truck')
                ->schema([
                    TextInput::make('nama_supplier')->label('Nama Perusahaan / Supplier')->placeholder('Contoh: PT. Kimia Farma')->required()->maxLength(100),
                    TextInput::make('no_telp')->label('Nomor Telepon / WhatsApp')->placeholder('Contoh: 081234567890')->tel()->required()->maxLength(15),
                    Textarea::make('alamat')->label('Alamat Lengkap')->placeholder('Masukkan alamat lengkap supplier...')->required()->rows(3)->columnSpanFull(),
                ])->columns(2),

            Section::make('Status Pengajuan')
                ->description('Kelola status akses supplier setelah data pengajuan diperiksa.')
                ->icon('heroicon-o-shield-check')
                ->schema([
                    Select::make('status_pengajuan')
                        ->label('Status Akses')
                        ->options([
                            'menunggu' => 'Menunggu Verifikasi',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                        ])
                        ->required()
                        ->native(false),
                ]),

            Section::make('Akun Portal')
                ->description('Opsional. Tautkan akun pengguna jika supplier membutuhkan akses portal.')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    Select::make('id_pengguna')
                        ->label('Tautkan ke Akun Login')
                        ->relationship('pengguna', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Pilih akun pengguna (opsional)'),
                ])->columns(2),
        ]);
    }
}
