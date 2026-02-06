<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(150),

                TextInput::make('subtitle')
                    ->label('Subjudul')
                    ->maxLength(255),

                FileUpload::make('image_url')
                    ->label('Gambar Banner')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->visibility('public')
                    ->required(),

                TextInput::make('link_url')
                    ->label('Tautan Tujuan')
                    ->url()
                    ->maxLength(255),

                Select::make('position')
                    ->options([
                        'hero' => 'Hero (Homepage Top Slider)',
                        'top' => 'Top (Featured Posts Section)',
                        'sidebar' => 'Sidebar',
                        'footer' => 'Footer',
                        'popup' => 'Popup',
                    ])
                    ->default('top')
                    ->required()
                    ->helperText('Hero: untuk slider di bagian atas homepage. Top: untuk banner di Featured Posts section.'),

                TextInput::make('order_index')
                    ->numeric()
                    ->default(0)
                    ->label('Urutan Tampil'),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

                DateTimePicker::make('start_date')
                    ->label('Mulai Tayang')
                    ->native(false)
                    ->seconds(false),

                DateTimePicker::make('end_date')
                    ->label('Selesai Tayang')
                    ->native(false)
                    ->seconds(false),
            ]);
    }
}


