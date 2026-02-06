<?php

namespace App\Filament\Resources\CategoryBlogs\Pages;

use App\Filament\Resources\CategoryBlogs\CategoryBlogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategoryBlog extends ViewRecord
{
    protected static string $resource = CategoryBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}



