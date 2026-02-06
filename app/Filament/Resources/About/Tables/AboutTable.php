<?php

namespace App\Filament\Resources\About\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AboutTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Medsos')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('link')
                    ->label('Link')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->link)
                    ->copyable()
                    ->copyMessage('Link disalin!'),
                TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_replace('bi-', '', $state)),
                TextColumn::make('order_index')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order_index', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

