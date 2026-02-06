<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema as FilamentSchema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(FilamentSchema $schema): FilamentSchema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(150)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, ?string $state): void {
                    $set('slug', Str::slug((string) $state));
                }),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(150)
                ->rules(['alpha_dash'])
                ->unique('pages', 'slug', ignoreRecord: true)
                ->helperText('Contoh: about-us, privacy-policy'),

            Section::make('Footer Settings')
                ->description('Pengaturan untuk menampilkan halaman di footer')
                ->schema([
                    Select::make('section')
                        ->label('Section Footer')
                        ->options([
                            'company' => 'Company',
                            'services' => 'Services',
                            'support' => 'Support',
                        ])
                        ->nullable()
                        ->helperText('Pilih section footer untuk menampilkan halaman ini'),

                    TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0)
                        ->helperText('Urutan tampil di footer (angka lebih kecil tampil lebih dulu)'),

                    TextInput::make('link_url')
                        ->label('Link URL (Opsional)')
                        ->url()
                        ->maxLength(255)
                        ->helperText('URL eksternal (jika kosong akan menggunakan slug halaman)'),
                ])
                ->collapsible()
                ->collapsed(),

            RichEditor::make('content')
                ->label('Konten')
                ->columnSpanFull(),

            FileUpload::make('featured_image')
                ->label('Gambar Utama')
                ->image()
                ->disk('public')
                ->directory('pages')
                ->visibility('public'),

            TextInput::make('meta_title')
                ->label('Meta Title (SEO)')
                ->maxLength(150)
                ->helperText('Judul untuk SEO'),

            Textarea::make('meta_description')
                ->label('Meta Description (SEO)')
                ->maxLength(255)
                ->rows(3)
                ->helperText('Deskripsi untuk SEO'),

            TextInput::make('meta_keywords')
                ->label('Meta Keywords (SEO)')
                ->maxLength(255)
                ->helperText('Kata kunci untuk SEO (pisahkan dengan koma)'),

            Toggle::make('is_published')
                ->label('Diterbitkan')
                ->default(false)
                ->inline(false),

            DateTimePicker::make('published_at')
                ->label('Tanggal Terbit')
                ->native(false)
                ->seconds(false)
                ->displayFormat('d/m/Y H:i'),
        ]);
    }
}

