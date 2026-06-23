<?php

namespace App\Filament\Admin\Resources\RiwayatStoks;

use App\Filament\Admin\Resources\RiwayatStoks\Pages\ListRiwayatStoks;
use App\Filament\Admin\Resources\RiwayatStoks\Pages\ViewRiwayatStok;
use App\Filament\Admin\Resources\RiwayatStoks\Schemas\RiwayatStokForm;
use App\Filament\Admin\Resources\RiwayatStoks\Schemas\RiwayatStokInfolist;
use App\Filament\Admin\Resources\RiwayatStoks\Tables\RiwayatStoksTable;
use App\Models\RiwayatStok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RiwayatStokResource extends Resource
{
    protected static ?string $model = RiwayatStok::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup =  'LAPORAN & AUDIT';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Buku Besar Stok';
    protected static ?string $pluralModelLabel = 'Riwayat Mutasi Stok';

    public static function form(Schema $schema): Schema
    {
        return RiwayatStokForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return RiwayatStokInfolist::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return RiwayatStoksTable::configure($table);
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRiwayatStoks::route('/'),
            // Halaman Create dan Edit sengaja dihapus agar tabel ini menjadi Read-Only
            'view' => ViewRiwayatStok::route('/{record}'),
        ];
    }

    // UX: Mencegah tombol "Create" muncul di UI
    public static function canCreate(): bool
    {
        return false;
    }
}
