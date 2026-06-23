<?php

namespace App\Filament\Admin\Resources\Pembayarans;

use App\Filament\Admin\Resources\Pembayarans\Pages\CreatePembayaran;
use App\Filament\Admin\Resources\Pembayarans\Pages\EditPembayaran;
use App\Filament\Admin\Resources\Pembayarans\Pages\ListPembayarans;
use App\Filament\Admin\Resources\Pembayarans\Pages\ViewPembayaran;
use App\Filament\Admin\Resources\Pembayarans\Schemas\PembayaranForm;
use App\Filament\Admin\Resources\Pembayarans\Schemas\PembayaranInfolist;
use App\Filament\Admin\Resources\Pembayarans\Tables\PembayaransTable;
use App\Models\Pembayaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static string|\UnitEnum|null $navigationGroup = 'PENGADAAN BARANG';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Pembayaran Tagihan';

    public static function form(Schema $schema): Schema
    {
        return PembayaranForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return PembayaranInfolist::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return PembayaransTable::configure($table);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPembayarans::route('/'),
            'create' => CreatePembayaran::route('/create'),
            'view' => ViewPembayaran::route('/{record}'),
            'edit' => EditPembayaran::route('/{record}/edit'),
        ];
    }
}
