<?php

namespace App\Models\Columns;

use Filament\Tables;

trait ProductColumns
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->rowIndex()->suffix('.'),
            Tables\Columns\TextColumn::make('title')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->words(4)
                ->html(),
            Tables\Columns\TextColumn::make('category.title')
                ->searchable()->badge()->sortable(),
            Tables\Columns\TextColumn::make('currency')
                ->searchable(),
            Tables\Columns\TextColumn::make('discount')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('price')
                ->prefix(fn($record) => $record->currency . ' ')
                ->sortable(),
            Tables\Columns\TextColumn::make('stock')
                ->numeric()
                ->sortable(),
            // Sizes Column
            Tables\Columns\TextColumn::make('sizes')
                ->label('Sizes')
                ->tooltip('Available Sizes')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('deleted_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->since()
                ->label('Last Updated')
                ->tooltip(fn($record) => $record->updated_at)
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
        ];
    }
}
