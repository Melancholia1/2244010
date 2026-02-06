<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul'),
                TextEntry::make('slug')
                    ->label('Slug'),
                TextEntry::make('content')
                    ->label('Konten')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('featured_image')
                    ->label('Gambar Utama')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('meta_title')
                    ->label('Meta Title (SEO)')
                    ->placeholder('-'),
                TextEntry::make('meta_description')
                    ->label('Meta Description (SEO)')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('meta_keywords')
                    ->label('Meta Keywords (SEO)')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('is_published')
                    ->label('Diterbitkan')
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Diterbitkan' : 'Draft'),
                TextEntry::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->placeholder('-'),
                TextEntry::make('updater.name')
                    ->label('Diperbarui Oleh')
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



