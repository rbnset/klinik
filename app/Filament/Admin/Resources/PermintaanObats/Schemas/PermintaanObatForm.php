<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermintaanObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan')
                    ->description('Isi rincian pengajuan kebutuhan obat operasional.')
                    ->schema([
                        Select::make('id_pengguna')
                            ->relationship('pengguna', 'name')
                            ->label('Diajukan Oleh')
                            ->default(auth()->id())
                            ->required(),

                        DatePicker::make('tanggal_permintaan')
                            ->label('Tanggal Pengajuan')
                            ->default(now())
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending (Menunggu Persetujuan)',
                                'disetujui' => 'Disetujui (Barang Diberikan)',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required(),

                        Textarea::make('keterangan')
                            ->label('Keterangan / Urgensi')
                            ->placeholder('Contoh: Kebutuhan poli KIA minggu ini...')
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Rincian Barang yang Diminta')
                    ->schema([
                        Repeater::make('detail_permintaan')
                            ->relationship()
                            ->schema([
                                Select::make('id_obat')
                                    ->relationship('obat', 'nama_obat')
                                    ->label('Pilih Obat')
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('jumlah_diminta')
                                    ->label('Jumlah Diminta')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(1),

                                // UX: Karyawan gudang bisa mengisi bagian ini jika barang yang ada kurang dari yang diminta
                                TextInput::make('jumlah_disetujui')
                                    ->label('Jumlah Disetujui (Oleh Gudang)')
                                    ->numeric()
                                    ->helperText('Kosongkan jika status masih pending')
                                    ->columnSpan(1),
                            ])
                            ->columns(4)
                            ->addActionLabel('Tambah Obat Lain')
                    ]),
            ]);
    }
}
