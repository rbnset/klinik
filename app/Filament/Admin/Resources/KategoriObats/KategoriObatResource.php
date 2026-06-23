<?php

namespace App\Filament\Admin\Resources\KategoriObats;

use App\Filament\Admin\Resources\KategoriObats\Pages\CreateKategoriObat;
use App\Filament\Admin\Resources\KategoriObats\Pages\EditKategoriObat;
use App\Filament\Admin\Resources\KategoriObats\Pages\ListKategoriObats;
use App\Filament\Admin\Resources\KategoriObats\Pages\ViewKategoriObat;
use App\Filament\Admin\Resources\KategoriObats\Schemas\KategoriObatForm;
use App\Filament\Admin\Resources\KategoriObats\Schemas\KategoriObatInfolist;
use App\Filament\Admin\Resources\KategoriObats\Tables\KategoriObatsTable;
use App\Models\KategoriObat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class KategoriObatResource extends Resource
{
    protected static ?string $model = KategoriObat::class;

    // Mengganti ikon agar lebih relevan (Tag/Label)
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    // Merapikan nama di Sidebar dan Judul
    protected static ?string $modelLabel = 'Kategori Obat';
    protected static ?string $pluralModelLabel = 'Kategori Obat';

    // Mengelompokkan menu di Sidebar
    protected static string|\UnitEnum|null $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama_kategori';

    public static function form(Schema $schema): Schema
    {
        return KategoriObatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KategoriObatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriObatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKategoriObats::route('/'),
            'create' => CreateKategoriObat::route('/create'),
            'view' => ViewKategoriObat::route('/{record}'),
            'edit' => EditKategoriObat::route('/{record}/edit'),
        ];
    }
}
