<?php

namespace App\Filament\Resources\CategoryBlogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryBlogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama'),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('is_active')
                    ->label('Aktif')
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Aktif' : 'Tidak Aktif'),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i'),
                TextEntry::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}



