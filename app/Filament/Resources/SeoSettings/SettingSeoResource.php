<?php

namespace App\Filament\Resources\SeoSettings;

use App\Filament\Resources\SeoSettings\Pages\CreateSettingSeo;
use App\Filament\Resources\SeoSettings\Pages\EditSettingSeo;
use App\Filament\Resources\SeoSettings\Pages\ListSettingSeos;
use App\Filament\Resources\SeoSettings\Pages\ViewSettingSeo;
use App\Filament\Resources\SeoSettings\Schemas\SettingSeoForm;
use App\Filament\Resources\SeoSettings\Schemas\SettingSeoInfolist;
use App\Filament\Resources\SeoSettings\Tables\SettingSeosTable;
use App\Models\SettingSeo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SettingSeoResource extends Resource
{
    protected static ?string $model = SettingSeo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $recordTitleAttribute = 'meta_title';

    public static function form(Schema $schema): Schema
    {
        return SettingSeoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SettingSeoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingSeosTable::configure($table);
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
            'index' => ListSettingSeos::route('/'),
            'create' => CreateSettingSeo::route('/create'),
            'view' => ViewSettingSeo::route('/{record}'),
            'edit' => EditSettingSeo::route('/{record}/edit'),
        ];
    }
}


