<?php

namespace App\Filament\Admin\Resources\PembelianObats;

use App\Filament\Admin\Resources\PembelianObats\Pages\CreatePembelianObat;
use App\Filament\Admin\Resources\PembelianObats\Pages\EditPembelianObat;
use App\Filament\Admin\Resources\PembelianObats\Pages\ListPembelianObats;
use App\Filament\Admin\Resources\PembelianObats\Pages\ViewPembelianObat;
use App\Filament\Admin\Resources\PembelianObats\Schemas\PembelianObatForm;
use App\Filament\Admin\Resources\PembelianObats\Schemas\PembelianObatInfolist;
use App\Filament\Admin\Resources\PembelianObats\Tables\PembelianObatsTable;
use App\Models\PembelianObat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\PembelianObats\RelationManagers\PenerimaanObatRelationManager;
use App\Filament\Admin\Resources\PembelianObats\RelationManagers\PembayaranRelationManager;

class PembelianObatResource extends Resource
{
    protected static ?string $model = PembelianObat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string|\UnitEnum|null $navigationGroup = 'PENGADAAN BARANG';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Pemesanan (PO)';
    protected static ?string $pluralModelLabel = 'Data Pemesanan';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->role === 'supplier') {
            $supplierId = $user->supplier?->id;

            if (! $supplierId) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('id_supplier', $supplierId);
            }
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return PembelianObatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PembelianObatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PembelianObatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        if (auth()->user()?->role === 'supplier') {
            return [];
        }

        return [PenerimaanObatRelationManager::class, PembayaranRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPembelianObats::route('/'),
            'create' => CreatePembelianObat::route('/create'),
            'view' => ViewPembelianObat::route('/{record}'),
            'edit' => EditPembelianObat::route('/{record}/edit'),
        ];
    }
}
