<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BannerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul'),
                TextEntry::make('subtitle')
                    ->label('Subjudul')
                    ->placeholder('-'),
                ImageEntry::make('image_url')
                    ->label('Gambar Banner')
                    ->disk('public')
                    ->height(200)
                    ->placeholder('-'),
                TextEntry::make('link_url')
                    ->label('Tautan Tujuan')
                    ->url(fn (?string $state): ?string => $state ?: null)
                    ->openUrlInNewTab()
                    ->placeholder('-'),
                TextEntry::make('position')
                    ->label('Posisi'),
                TextEntry::make('order_index')
                    ->label('Urutan Tampil'),
                TextEntry::make('is_active')
                    ->label('Aktif')
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Aktif' : 'Tidak Aktif'),
                TextEntry::make('start_date')
                    ->label('Mulai Tayang')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->label('Selesai Tayang')
                    ->dateTime('d/m/Y H:i')
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

