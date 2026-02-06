<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Schemas\Schema as FilamentSchema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use App\Models\CategoryBlog;

class ArticleForm
{
    public static function configure(FilamentSchema $schema): FilamentSchema // ✅ signature pakai alias
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Judul')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Set $set, ?string $state): void {
                    $set('slug', Str::slug((string) $state));
                }),

            TextInput::make('slug')
                ->required()
                ->rules(['alpha_dash'])
                ->unique(ignoreRecord: true),

            TextInput::make('excerpt')
                ->label('Ringkasan')
                ->maxLength(255),

            Textarea::make('table_of_contents')
                ->label('Daftar Isi')
                ->required()
                ->rows(5)
                ->helperText('Masukkan daftar isi artikel. Setiap baris akan menjadi item daftar isi. Untuk menghubungkan dengan heading di konten, gunakan format: "Judul Bagian #id-heading" atau cukup "Judul Bagian" (akan otomatis dicocokkan dengan heading di konten). Contoh:\n1. Pendahuluan\n2. Pembahasan #pembahasan\n3. Kesimpulan')
                ->columnSpanFull(),

            Select::make('category_blog_id')
                ->label('Kategori')
                ->relationship('categoryBlog', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nama Kategori')
                        ->required(),
                    Textarea::make('description')
                        ->label('Deskripsi'),
                    Select::make('is_active')
                        ->label('Aktif')
                        ->options([
                            true => 'Ya',
                            false => 'Tidak',
                        ])
                        ->default(true),
                ])
                ->nullable(),

            FileUpload::make('featured_image')
                ->label('Gambar Utama')
                ->image()
                ->disk('public')
                ->directory('articles')
                ->visibility('public'),

            RichEditor::make('content')
                ->label('Konten')
                ->columnSpanFull(),

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

            Select::make('status')
                ->options(['draft' => 'Draft', 'published' => 'Published'])
                ->default('draft'),

            DateTimePicker::make('published_at')
                ->label('Tanggal Terbit')
                ->native(false)
                ->seconds(false),
        ]);
    }
}
