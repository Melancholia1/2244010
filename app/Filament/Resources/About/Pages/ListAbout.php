<?php

namespace App\Filament\Resources\About\Pages;

use App\Filament\Resources\About\AboutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAbout extends ListRecords
{
    protected static string $resource = AboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

