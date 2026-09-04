<?php

namespace App\Filament\Admin\Resources\Obats\RelationManagers;

use App\Filament\Admin\Resources\PenerimaanObats\PenerimaanObatResource;
use App\Filament\Admin\Resources\PermintaanObats\PermintaanObatResource;
use App\Filament\Admin\Resources\PenyesuaianStoks\PenyesuaianStokResource;
use App\Filament\Admin\Resources\RiwayatStoks\RiwayatStokResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RiwayatStokRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayat_stok';

    protected static ?string $title = 'Riwayat Stok';
    protected static ?string $label = 'Riwayat Stok';
    protected static ?string $pluralLabel = 'Riwayat Stok';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referensi_transaksi')
            ->defaultSort('tanggal_mutasi', 'desc')
            ->columns([
                TextColumn::make('tanggal_mutasi')
                    ->label('Tanggal')
                    ->state(fn (Model $record) => $record->tanggal_mutasi ?: $record->created_at)
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('sumber_label')
                    ->label('Aktivitas')
                    ->state(fn (Model $record): string => $this->activityLabel($record))
                    ->badge()
                    ->color(fn (Model $record): string => match ($this->referenceType($record)) {
                        'penerimaan' => 'success',
                        'permintaan' => 'danger',
                        'penyesuaian' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('referensi_transaksi')
                    ->label('Referensi')
                    ->placeholder('-')
                    ->formatStateUsing(fn (?string $state): string => $state ?: '-')
                    ->fontFamily('mono')
                    ->weight('bold'),

                TextColumn::make('jenis_transaksi')
                    ->label('Mutasi')
                    ->formatStateUsing(
                        fn (?string $state, Model $record): string =>
                            ($state === 'masuk' ? '+' : '-') .
                            number_format((int) $record->jumlah) .
                            ' ' .
                            ($record->obat?->satuan ?? 'unit')
                    )
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'masuk' ? 'success' : 'danger'),

                TextColumn::make('stok_sebelum')
                    ->label('Sebelum')
                    ->numeric(),

                TextColumn::make('stok_sesudah')
                    ->label('Sesudah')
                    ->numeric()
                    ->weight('bold'),

                TextColumn::make('keterangan')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateHeading('Belum ada riwayat stok')
            ->emptyStateDescription('Riwayat akan tercatat otomatis saat penerimaan, permintaan internal, atau penyesuaian stok diproses.')
            ->headerActions([])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Model $record): string => $this->referenceUrl($record)),
            ])
            ->toolbarActions([]);
    }

    /**
     * Aktivitas yang dicatat dalam histori stok hanya terdiri dari:
     * - Penerimaan Obat
     * - Permintaan Internal
     * - Penyesuaian Stok
     *
     * Data lama yang belum memiliki referensi_tipe tetap dikenali
     * berdasarkan prefix dokumennya (GR / REQ / ADJ).
     */
    private function activityLabel(Model $record): string
    {
        return match ($this->referenceType($record)) {
            'penerimaan' => 'Penerimaan Obat',
            'permintaan' => 'Permintaan Internal',
            'penyesuaian' => 'Penyesuaian Stok',
            default => 'Penyesuaian Stok',
        };
    }

    private function referenceUrl(Model $record): string
    {
        $type = $this->referenceType($record);
        $referenceId = $record->referensi_id ?: $this->inferReferenceId($record->referensi_transaksi);

        return match ($type) {
            'penerimaan' => $referenceId
                ? PenerimaanObatResource::getUrl('view', ['record' => $referenceId])
                : RiwayatStokResource::getUrl('view', ['record' => $record]),

            'permintaan' => $referenceId
                ? PermintaanObatResource::getUrl('view', ['record' => $referenceId])
                : RiwayatStokResource::getUrl('view', ['record' => $record]),

            'penyesuaian' => $referenceId
                ? PenyesuaianStokResource::getUrl('view', ['record' => $referenceId])
                : RiwayatStokResource::getUrl('view', ['record' => $record]),

            default => RiwayatStokResource::getUrl('view', ['record' => $record]),
        };
    }

    private function referenceType(Model $record): ?string
    {
        if ($record->referensi_tipe) {
            return $record->referensi_tipe;
        }

        $reference = strtoupper(trim((string) $record->referensi_transaksi));

        return match (true) {
            str_starts_with($reference, 'GR-') => 'penerimaan',
            str_starts_with($reference, 'REQ-') => 'permintaan',
            str_starts_with($reference, 'ADJ-') => 'penyesuaian',
            default => null,
        };
    }

    private function inferReferenceId(?string $reference): ?int
    {
        if (! $reference) {
            return null;
        }

        return preg_match('/\b(?:GR|REQ|ADJ)-0*(\d+)\b/i', trim($reference), $matches)
            ? (int) $matches[1]
            : null;
    }
}
