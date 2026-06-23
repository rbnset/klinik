<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenyesuaianStokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pencatatan Anomali Barang')
                    ->description('Gunakan formulir ini jika terjadi selisih stok fisik dengan sistem (hilang, rusak, dll).')
                    ->schema([
                        Select::make('id_obat')
                            ->relationship('obat', 'nama_obat')
                            ->label('Pilih Obat')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('id_pengguna')
                            ->relationship('pengguna', 'name')
                            ->label('Petugas Pemeriksa')
                            ->default(auth()->id())
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Opname')
                            ->default(now())
                            ->required(),
                    ])->columns(3),

                Section::make('Tindakan Penyesuaian')
                    ->schema([
                        Select::make('jenis')
                            ->label('Tindakan Sistem')
                            ->options([
                                'penambahan' => 'Tambah Stok Sistem (+)',
                                'pengurangan' => 'Kurangi Stok Sistem (-)',
                            ])
                            ->required(),

                        Select::make('alasan')
                            ->label('Alasan Penyesuaian')
                            ->options([
                                'kadaluwarsa' => 'Barang Kadaluwarsa (Expired)',
                                'rusak' => 'Barang Rusak / Pecah',
                                'hilang' => 'Barang Hilang / Dicuri',
                                'selisih_hitung' => 'Selisih Hitung Manusia',
                            ])
                            ->required(),

                        TextInput::make('jumlah')
                            ->label('Kuantitas Penyesuaian')
                            ->numeric()
                            ->suffix('Unit')
                            ->required(),

                        Textarea::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->placeholder('Jelaskan detail temuan fisik...')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}
