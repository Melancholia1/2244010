<?php

namespace App\Filament\Resources\About;

use App\Filament\Resources\About\Pages\CreateAbout;
use App\Filament\Resources\About\Pages\EditAbout;
use App\Filament\Resources\About\Pages\ListAbout;
use App\Filament\Resources\About\Schemas\AboutForm;
use App\Filament\Resources\About\Tables\AboutTable;
use App\Models\SocialMedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AboutResource extends Resource
{
    protected static ?string $model = SocialMedia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'About - Social Media';

    public static function form(Schema $schema): Schema
    {
        return AboutForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AboutTable::configure($table);
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
            'index' => ListAbout::route('/'),
            'create' => CreateAbout::route('/create'),
            'edit' => EditAbout::route('/{record}/edit'),
        ];
    }
}

