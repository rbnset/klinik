<?php

namespace App\Filament\Admin\Resources\Obats;

use App\Filament\Admin\Resources\Obats\Pages\CreateObat;
use App\Filament\Admin\Resources\Obats\Pages\EditObat;
use App\Filament\Admin\Resources\Obats\Pages\ListObats;
use App\Filament\Admin\Resources\Obats\Pages\ViewObat;
use App\Filament\Admin\Resources\Obats\Schemas\ObatForm;
use App\Filament\Admin\Resources\Obats\Schemas\ObatInfolist;
use App\Filament\Admin\Resources\Obats\Tables\ObatsTable;
use App\Models\Obat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ObatResource extends Resource
{
    protected static ?string $model = Obat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $modelLabel = 'Obat & Persediaan';
    protected static ?string $pluralModelLabel = 'Katalog Obat';

    // Mengelompokkan menu di Sidebar
    protected static string|\UnitEnum|null $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nama_obat';

    public static function form(Schema $schema): Schema
    {
        return ObatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ObatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ObatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListObats::route('/'),
            'create' => CreateObat::route('/create'),
            'view' => ViewObat::route('/{record}'),
            'edit' => EditObat::route('/{record}/edit'),
        ];
    }
}
