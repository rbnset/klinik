<?php

namespace App\Filament\Admin\Resources\PenerimaanObats;

use App\Filament\Admin\Resources\PenerimaanObats\Pages\CreatePenerimaanObat;
use App\Filament\Admin\Resources\PenerimaanObats\Pages\EditPenerimaanObat;
use App\Filament\Admin\Resources\PenerimaanObats\Pages\ListPenerimaanObats;
use App\Filament\Admin\Resources\PenerimaanObats\Pages\ViewPenerimaanObat;
use App\Filament\Admin\Resources\PenerimaanObats\Schemas\PenerimaanObatForm;
use App\Filament\Admin\Resources\PenerimaanObats\Schemas\PenerimaanObatInfolist;
use App\Filament\Admin\Resources\PenerimaanObats\Tables\PenerimaanObatsTable;
use App\Models\PenerimaanObat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PenerimaanObatResource extends Resource
{
    protected static ?string $model = PenerimaanObat::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string|\UnitEnum|null $navigationGroup = 'PENGADAAN BARANG';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Penerimaan & Faktur';

    public static function form(Schema $schema): Schema
    {
        return PenerimaanObatForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return PenerimaanObatInfolist::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return PenerimaanObatsTable::configure($table);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPenerimaanObats::route('/'),
            'create' => CreatePenerimaanObat::route('/create'),
            'view' => ViewPenerimaanObat::route('/{record}'),
            'edit' => EditPenerimaanObat::route('/{record}/edit'),
        ];
    }
}
