<?php

namespace App\Filament\Resources\About\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AboutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Medsos')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Contoh: Facebook, Instagram, LinkedIn')
                    ->helperText('Nama platform media sosial (akan ditampilkan di frontend)'),

                TextInput::make('link')
                    ->label('Link Medsos')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://www.facebook.com/yourpage')
                    ->helperText('URL lengkap ke halaman media sosial Anda'),

                Select::make('icon')
                    ->label('Icon')
                    ->required()
                    ->options([
                        'bi-facebook' => 'Facebook',
                        'bi-instagram' => 'Instagram',
                        'bi-twitter-x' => 'Twitter/X',
                        'bi-linkedin' => 'LinkedIn',
                        'bi-youtube' => 'YouTube',
                        'bi-tiktok' => 'TikTok',
                        'bi-dribbble' => 'Dribbble',
                        'bi-pinterest' => 'Pinterest',
                        'bi-github' => 'GitHub',
                        'bi-whatsapp' => 'WhatsApp',
                        'bi-telegram' => 'Telegram',
                        'bi-discord' => 'Discord',
                    ])
                    ->searchable()
                    ->helperText('Pilih icon Bootstrap Icons yang akan ditampilkan'),

                TextInput::make('order_index')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0)
                    ->helperText('Urutan tampil di navbar dan footer (0 = pertama)'),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->helperText('Hanya medsos yang aktif yang akan ditampilkan di frontend'),
            ]);
    }
}

