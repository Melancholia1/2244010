<?php

namespace App\Filament\Resources\SeoSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SettingSeoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('meta_title')
                    ->label('Meta Title')
                    ->placeholder('-'),
                TextEntry::make('meta_description')
                    ->label('Meta Description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('robots')
                    ->label('Robots')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i'),
                TextEntry::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}



