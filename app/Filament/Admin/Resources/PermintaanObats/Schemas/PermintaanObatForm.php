<?php

namespace App\Filament\Admin\Resources\PermintaanObats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
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
        $isCreate = $schema->getRecord() === null;

        return $schema
            ->columns(1)
            ->components([

                // ── Status Card (hanya tampil saat Edit/View) ──────────────────
                Section::make()
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('status_info')
                            ->label('')
                            ->content(function ($record) {
                                if (! $record) return '';

                                $map = [
                                    'pending'   => [
                                        'label' => 'Pending',
                                        'desc'  => 'Permintaan telah diajukan dan sedang menunggu persetujuan dari petugas gudang.',
                                        'color' => '#b45309',   // amber-700
                                        'bg'    => '#fef3c7',   // amber-100
                                        'border' => '#fcd34d',   // amber-300
                                        'icon'  => '🕐',
                                    ],
                                    'disetujui' => [
                                        'label' => 'Disetujui',
                                        'desc'  => 'Permintaan telah disetujui dan barang siap atau sudah diberikan oleh gudang.',
                                        'color' => '#065f46',   // emerald-800
                                        'bg'    => '#d1fae5',   // emerald-100
                                        'border' => '#6ee7b7',   // emerald-300
                                        'icon'  => '✅',
                                    ],
                                    'ditolak'   => [
                                        'label' => 'Ditolak',
                                        'desc'  => 'Permintaan ditolak oleh petugas gudang. Silakan buat pengajuan baru atau hubungi gudang untuk informasi lebih lanjut.',
                                        'color' => '#991b1b',   // red-800
                                        'bg'    => '#fee2e2',   // red-100
                                        'border' => '#fca5a5',   // red-300
                                        'icon'  => '❌',
                                    ],
                                ];

                                $s = $map[$record->status] ?? [
                                    'label' => ucfirst($record->status),
                                    'desc'  => '-',
                                    'color' => '#374151',
                                    'bg'    => '#f3f4f6',
                                    'border' => '#d1d5db',
                                    'icon'  => 'ℹ️',
                                ];

                                return new \Illuminate\Support\HtmlString(
                                    '<div style="
                                        display:flex;
                                        align-items:flex-start;
                                        gap:14px;
                                        padding:16px 20px;
                                        background:' . $s['bg'] . ';
                                        border:1.5px solid ' . $s['border'] . ';
                                        border-radius:10px;
                                    ">
                                        <span style="font-size:1.6rem;line-height:1;">' . $s['icon'] . '</span>
                                        <div>
                                            <div style="
                                                font-size:0.7rem;
                                                font-weight:600;
                                                letter-spacing:0.08em;
                                                text-transform:uppercase;
                                                color:' . $s['color'] . ';
                                                margin-bottom:2px;
                                            ">Status Saat Ini</div>
                                            <div style="
                                                font-size:1rem;
                                                font-weight:700;
                                                color:' . $s['color'] . ';
                                                margin-bottom:4px;
                                            ">' . $s['label'] . '</div>
                                            <div style="
                                                font-size:0.875rem;
                                                color:' . $s['color'] . ';
                                                opacity:0.85;
                                            ">' . $s['desc'] . '</div>
                                        </div>
                                    </div>'
                                );
                            })
                            ->columnSpanFull(),
                    ])
                    ->hiddenOn('create'),

                // ── Informasi Pengajuan ────────────────────────────────────────
                Section::make('Informasi Pengajuan')
                    ->description('Isi rincian pengajuan kebutuhan obat operasional.')
                    ->schema([
                        // Tampilan nama (tidak disimpan langsung)
                        TextInput::make('diajukan_oleh_display')
                            ->label('Diajukan Oleh')
                            ->default(fn() => auth()->user()?->name)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Hidden::make('id_pengguna')
                            ->default(fn() => auth()->id()),

                        Hidden::make('status')
                            ->default('pending'),

                        Select::make('status')
                            ->label('Keputusan Gudang')
                            ->options([
                                'pending' => 'Pending',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->visible(
                                fn() =>
                                auth()->user()->role === 'karyawan'
                            )
                            ->disabled(
                                fn($record) =>
                                $record?->status === 'disetujui'
                                    || $record?->status === 'ditolak'
                            ),

                        DatePicker::make('tanggal_permintaan')
                            ->label('Tanggal Pengajuan')
                            ->default(now())
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('keterangan')
                            ->label('Keterangan / Urgensi')
                            ->placeholder('Contoh: Kebutuhan poli KIA minggu ini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // ── Rincian Barang ─────────────────────────────────────────────
                Section::make('Rincian Barang yang Diminta')
                    ->schema([
                        Repeater::make('detail_permintaan')
                            ->relationship()
                            ->schema([
                                Select::make('id_obat')
                                    ->relationship('obat', 'nama_obat')
                                    ->label('Pilih Obat')
                                    ->searchable()
                                    ->preload()           // ← tampilkan opsi awal tanpa mengetik
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems() // ← tambah ini
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('jumlah_diminta')
                                    ->label('Jumlah Diminta')
                                    ->numeric()
                                    ->minValue(1)
                                    ->step(1)
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('jumlah_disetujui')
                                    ->label('Jumlah Disetujui (Oleh Gudang)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->step(1)
                                    ->maxValue(fn($record) => $record?->jumlah_diminta)
                                    ->visible(
                                        fn() => auth()->user()->role === 'karyawan'
                                    )
                                    ->required(
                                        fn() => auth()->user()->role === 'karyawan'
                                    )
                                    ->helperText('Wajib diisi sebelum permintaan dapat disetujui.')
                            ])
                            ->columns(4)
                            ->addActionLabel('Tambah Obat Lain'),
                    ]),
            ]);
    }
}
