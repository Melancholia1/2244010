<?php

namespace App\Filament\Resources\CategoryBlogs;

use App\Filament\Resources\CategoryBlogs\Pages\CreateCategoryBlog;
use App\Filament\Resources\CategoryBlogs\Pages\EditCategoryBlog;
use App\Filament\Resources\CategoryBlogs\Pages\ListCategoryBlogs;
use App\Filament\Resources\CategoryBlogs\Pages\ViewCategoryBlog;
use App\Filament\Resources\CategoryBlogs\Schemas\CategoryBlogForm;
use App\Filament\Resources\CategoryBlogs\Schemas\CategoryBlogInfolist;
use App\Filament\Resources\CategoryBlogs\Tables\CategoryBlogsTable;
use App\Models\CategoryBlog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoryBlogResource extends Resource
{
    protected static ?string $model = CategoryBlog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CategoryBlogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryBlogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryBlogsTable::configure($table);
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
            'index' => ListCategoryBlogs::route('/'),
            'create' => CreateCategoryBlog::route('/create'),
            'view' => ViewCategoryBlog::route('/{record}'),
            'edit' => EditCategoryBlog::route('/{record}/edit'),
        ];
    }
}


