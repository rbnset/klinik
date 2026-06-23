<?php

namespace App\Filament\Admin\Resources\PermintaanObats;

use App\Filament\Admin\Resources\PermintaanObats\Pages\CreatePermintaanObat;
use App\Filament\Admin\Resources\PermintaanObats\Pages\EditPermintaanObat;
use App\Filament\Admin\Resources\PermintaanObats\Pages\ListPermintaanObats;
use App\Filament\Admin\Resources\PermintaanObats\Pages\ViewPermintaanObat;
use App\Filament\Admin\Resources\PermintaanObats\Schemas\PermintaanObatForm;
use App\Filament\Admin\Resources\PermintaanObats\Schemas\PermintaanObatInfolist;
use App\Filament\Admin\Resources\PermintaanObats\Tables\PermintaanObatsTable;
use App\Models\PermintaanObat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PermintaanObatResource extends Resource
{
    protected static ?string $model = PermintaanObat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static string|\UnitEnum|null $navigationGroup = 'SIRKULASI INTERNAL';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Permintaan Obat';
    protected static ?string $pluralModelLabel = 'Permintaan Internal';

    public static function form(Schema $schema): Schema
    {
        return PermintaanObatForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return PermintaanObatInfolist::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return PermintaanObatsTable::configure($table);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPermintaanObats::route('/'),
            'create' => CreatePermintaanObat::route('/create'),
            'view' => ViewPermintaanObat::route('/{record}'),
            'edit' => EditPermintaanObat::route('/{record}/edit'),
        ];
    }
}
