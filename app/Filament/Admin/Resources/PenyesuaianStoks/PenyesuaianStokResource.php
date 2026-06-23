<?php

namespace App\Filament\Admin\Resources\PenyesuaianStoks;

use App\Filament\Admin\Resources\PenyesuaianStoks\Pages\CreatePenyesuaianStok;
use App\Filament\Admin\Resources\PenyesuaianStoks\Pages\EditPenyesuaianStok;
use App\Filament\Admin\Resources\PenyesuaianStoks\Pages\ListPenyesuaianStoks;
use App\Filament\Admin\Resources\PenyesuaianStoks\Pages\ViewPenyesuaianStok;
use App\Filament\Admin\Resources\PenyesuaianStoks\Schemas\PenyesuaianStokForm;
use App\Filament\Admin\Resources\PenyesuaianStoks\Schemas\PenyesuaianStokInfolist;
use App\Filament\Admin\Resources\PenyesuaianStoks\Tables\PenyesuaianStoksTable;
use App\Models\PenyesuaianStok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PenyesuaianStokResource extends Resource
{
    protected static ?string $model = PenyesuaianStok::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static string|\UnitEnum|null $navigationGroup = 'SIRKULASI INTERNAL';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Stok Opname / Penyesuaian';
    protected static ?string $pluralModelLabel = 'Riwayat Stok Opname';

    public static function form(Schema $schema): Schema
    {
        return PenyesuaianStokForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return PenyesuaianStokInfolist::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return PenyesuaianStoksTable::configure($table);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPenyesuaianStoks::route('/'),
            'create' => CreatePenyesuaianStok::route('/create'),
            'view' => ViewPenyesuaianStok::route('/{record}'),
            'edit' => EditPenyesuaianStok::route('/{record}/edit'),
        ];
    }
}
