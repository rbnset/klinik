<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Schemas;

use App\Models\Obat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PermintaanObatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Permintaan')
                ->description('Isi identitas pengajuan kebutuhan obat dan, bila berwenang, keputusan gudang.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    TextInput::make('diajukan_oleh_display')
                        ->label('Diajukan Oleh')
                        ->default(fn () => auth()->user()?->name)
                        ->disabled()
                        ->dehydrated(false),
                    Hidden::make('id_pengguna')->default(fn () => auth()->id()),
                    Select::make('status')
                        ->label('Keputusan Gudang')
                        ->options([
                            'pending' => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                        ])
                        ->visible(fn () => auth()->user()->role === 'karyawan')
                        ->disabled(true),
                    DatePicker::make('tanggal_permintaan')
                        ->label('Tanggal Pengajuan')
                        ->default(now())
                        ->required(),
                    Textarea::make('keterangan')
                        ->label('Keterangan / Urgensi')
                        ->placeholder('Contoh: Kebutuhan poli KIA minggu ini...')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Rincian Obat')
                ->description('Pilih obat dan jumlah yang dibutuhkan. Stok tersedia divalidasi sebelum permintaan disimpan.')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    Repeater::make('detail_permintaan')
                        ->relationship()
                        ->schema([
                            Select::make('id_obat')
                                ->label('Pilih Obat')
                                ->searchable()
                                ->preload()
                                ->options(
                                    Obat::query()
                                        ->get()
                                        ->mapWithKeys(fn ($obat) => [
                                            $obat->id => $obat->nama_obat . ' | Stok: ' . $obat->stok . ' | Pending: ' . $obat->total_pending . ' | Tersedia: ' . $obat->stok_tersedia,
                                        ])
                                )
                                ->disableOptionWhen(fn ($value) => ($obat = Obat::find($value)) && $obat->stok_tersedia <= 0)
                                ->required(),
                            TextInput::make('jumlah_diminta')
                                ->label('Jumlah Diminta')
                                ->numeric()
                                ->minValue(1)
                                ->step(1)
                                ->required()
                                ->rules([
                                    function (Get $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                                            $obatId = $get('id_obat');
                                            if (! $obatId) return;
                                            $obat = Obat::find($obatId);
                                            if (! $obat) return;
                                            if ($value > $obat->stok_tersedia) {
                                                $fail("Jumlah melebihi stok tersedia ({$obat->stok_tersedia}).");
                                            }
                                        };
                                    },
                                ]),
                            TextInput::make('jumlah_disetujui')
                                ->label('Jumlah Disetujui')
                                ->numeric()
                                ->minValue(1)
                                ->step(1)
                                ->maxValue(fn ($record) => $record?->jumlah_diminta)
                                ->visible(fn () => auth()->user()->role === 'karyawan')
                                ->required(fn () => auth()->user()->role === 'karyawan')
                                ->helperText('Diisi oleh gudang sebelum permintaan disetujui.'),
                        ])
                        ->columns(3)
                        ->addActionLabel('Tambah Obat'),
                ]),
        ]);
    }
}
