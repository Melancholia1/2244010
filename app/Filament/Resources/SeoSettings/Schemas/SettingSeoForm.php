<?php

namespace App\Filament\Resources\SeoSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingSeoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(150),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->maxLength(255),

                TextInput::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->maxLength(255)
                    ->helperText('Pisahkan dengan koma'),

                Select::make('robots')
                    ->label('Robots')
                    ->options([
                        'index, follow' => 'index, follow',
                        'index, nofollow' => 'index, nofollow',
                        'noindex, follow' => 'noindex, follow',
                        'noindex, nofollow' => 'noindex, nofollow',
                    ])
                    ->searchable()
                    ->preload(),
            ]);
    }
}




