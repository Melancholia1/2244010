<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kodebarang')
                    ->required(),
                TextInput::make('namabarang')
                    ->required(),
                TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
