<?php

namespace App\Filament\Admin\Resources\PenerimaanObats\Schemas;

use App\Models\DetailPembelianObat;
use App\Models\PembelianObat;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PenerimaanObatForm
{
    /**
     * Mengambil HANYA item PO yang masih memiliki sisa penerimaan.
     * Perhitungan dilakukan langsung dari database agar tidak bergantung
     * pada accessor/relation cache Livewire.
     */
    public static function buildRows(?int $poId): array
    {
        if (! $poId) {
            return [];
        }

        return DetailPembelianObat::query()
            ->where('id_pembelian_obat', $poId)
            ->with('obat')
            ->get()
            ->map(function (DetailPembelianObat $detail): ?array {
                $sudahDiterima = (int) $detail->detail_penerimaan()->sum('jumlah_diterima');
                $jumlahPesan = (int) $detail->jumlah_pesan;
                $sisa = max(0, $jumlahPesan - $sudahDiterima);

                if ($sisa <= 0) {
                    return null;
                }

                return [
                    'id_detail_pembelian' => (int) $detail->id,
                    'nama_obat' => $detail->obat?->nama_obat ?? '-',
                    'satuan' => $detail->obat?->satuan ?? '-',
                    'jumlah_pesan' => $jumlahPesan,
                    'jumlah_diterima_sebelumnya' => $sudahDiterima,
                    'sisa_diterima' => $sisa,
                    'terima_sesuai_sisa' => false,
                    'jumlah_diterima' => 0,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    public static function poQueryWithSisa(): Builder
    {
        return PembelianObat::query()
            ->where('status', 'diproses')
            ->whereHas('detail_pembelian', function (Builder $query): void {
                $query->whereRaw(
                    'jumlah_pesan > COALESCE((SELECT SUM(dpo.jumlah_diterima) FROM detail_penerimaan_obat AS dpo WHERE dpo.id_detail_pembelian = detail_pembelian_obat.id), 0)'
                );
            })
            ->with('supplier')
            ->orderByDesc('id');
    }

    public static function configure(Schema $schema): Schema
    {
        $poId = request()->query('pembelian');

        return $schema->components([
            Section::make('Informasi Penerimaan')
                ->description('Pilih PO dan tanggal penerimaan. Nomor faktur boleh dikosongkan, terutama bila supplier belum memberikan faktur.')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    Select::make('id_pembelian_obat')
                        ->label('Nomor PO')
                        ->options(fn () => self::poQueryWithSisa()
                            ->get()
                            ->mapWithKeys(fn (PembelianObat $record) => [
                                $record->id => 'PO-' . str_pad((string) $record->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($record->supplier?->nama_supplier ?? '-'),
                            ])
                            ->all())
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default($poId)
                        ->disabled(fn () => $poId !== null)
                        ->dehydrated()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set): void {
                            $set('detail_penerimaan', self::buildRows($state ? (int) $state : null));
                        })
                        ->helperText(fn () => $poId
                            ? 'PO dipertahankan sama. Hanya item yang masih kurang yang ditampilkan.'
                            : 'Hanya PO Diproses yang masih memiliki sisa barang yang dapat dipilih.'),

                    TextInput::make('nomor_faktur')
                        ->label('Nomor Faktur')
                        ->unique(ignoreRecord: true)
                        ->nullable()
                        ->placeholder('Kosongkan jika belum ada'),

                    DatePicker::make('tanggal_terima')
                        ->label('Tanggal Terima')
                        ->default(now())
                        ->required(),
                ])->columns(3)
                ->columnSpanFull(),

            Section::make('Pengecekan Fisik')
                ->description('Daftar di bawah hanya berisi obat yang masih kurang dari PO. Jika datang sebagian, isi Qty Aktual sesuai jumlah fisik. Sisa akan otomatis terbawa ke penerimaan berikutnya.')
                ->icon('heroicon-o-clipboard-document-check')
                ->schema([
                    Repeater::make('detail_penerimaan')
                        // Sengaja TANPA ->relationship(). Penyimpanan detail dilakukan
                        // secara atomik di CreatePenerimaanObat agar tombol Buat tidak
                        // bergantung pada mekanisme relationship repeater Filament.
                        ->schema([
                            TextInput::make('nama_obat')
                                ->label('Obat')
                                ->disabled()
                                ->dehydrated(false),

                            TextInput::make('jumlah_pesan')
                                ->label('Dipesan')
                                ->numeric()
                                ->suffix(fn (Get $get) => $get('satuan') ?: '')
                                ->disabled()
                                ->dehydrated(false),

                            TextInput::make('jumlah_diterima_sebelumnya')
                                ->label('Sudah Diterima')
                                ->numeric()
                                ->suffix(fn (Get $get) => $get('satuan') ?: '')
                                ->disabled()
                                ->dehydrated(false),

                            TextInput::make('sisa_diterima')
                                ->label('Sisa PO')
                                ->numeric()
                                ->suffix(fn (Get $get) => $get('satuan') ?: '')
                                ->disabled()
                                ->dehydrated(false),

                            Checkbox::make('terima_sesuai_sisa')
                                ->label('Terima sesuai sisa')
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, Get $get): void {
                                    $set('jumlah_diterima', $state ? (int) ($get('sisa_diterima') ?? 0) : 0);
                                })
                                ->helperText('Centang jika seluruh sisa datang.'),

                            TextInput::make('jumlah_diterima')
                                ->label('Qty Aktual')
                                ->numeric()
                                ->integer()
                                ->minValue(0)
                                ->required()
                                ->live(onBlur: true)
                                ->rules([
                                    function (Get $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get): void {
                                            $qty = (int) ($value ?? 0);
                                            $sisa = (int) ($get('sisa_diterima') ?? 0);

                                            if ($qty > $sisa) {
                                                $fail("Qty aktual tidak boleh melebihi sisa PO ({$sisa}).");
                                            }
                                        };
                                    },
                                ])
                                ->helperText(function (Get $get): string {
                                    $qty = (int) ($get('jumlah_diterima') ?? 0);
                                    $sisa = (int) ($get('sisa_diterima') ?? 0);
                                    $satuan = $get('satuan') ?: 'unit';

                                    if ($qty > $sisa) {
                                        return "Melebihi sisa PO: {$sisa} {$satuan}.";
                                    }

                                    if ($qty > 0 && $qty < $sisa) {
                                        return 'Setelah disimpan, sisa berikutnya: ' . ($sisa - $qty) . ' ' . $satuan . '.';
                                    }

                                    if ($qty === $sisa && $sisa > 0) {
                                        return 'Item ini akan selesai diterima.';
                                    }

                                    return 'Isi 0 jika item belum datang.';
                                }),

                            // ID detail dipakai saat proses simpan, tetapi tidak tampil.
                            \Filament\Forms\Components\Hidden::make('id_detail_pembelian'),
                            \Filament\Forms\Components\Hidden::make('satuan')->dehydrated(false),
                        ])
                        ->default(self::buildRows($poId ? (int) $poId : null))
                        ->columns(6)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->itemLabel(fn (array $state): ?string => ($state['nama_obat'] ?? 'Item PO') . ' · Sisa ' . ($state['sisa_diterima'] ?? 0) . ' ' . ($state['satuan'] ?? ''))
                        ->visible(fn (Get $get): bool => filled($get('id_pembelian_obat')) || filled(request()->query('pembelian'))),
                ])
                ->columnSpanFull(),
        ]);
    }
}
