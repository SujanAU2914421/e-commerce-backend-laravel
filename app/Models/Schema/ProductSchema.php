<?php

namespace App\Models\Schema;

use Filament\Forms;

trait ProductSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('category_id')
                ->relationship('category', 'title')
                ->createOptionForm([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('description')
                        ->required()
                        ->maxLength(255),
                ])
                ->required(),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\RichEditor::make('description')
                ->required()
                ->columnSpanFull(), // Add a field for sizes
            Forms\Components\Select::make('sizes')
                ->multiple() // Allow the selection of multiple sizes
                ->options([
                    'S' => 'Small',
                    'M' => 'Medium',
                    'L' => 'Large',
                    'XL' => 'Extra Large',
                    'XXL' => 'Double Extra Large',
                    // Add more sizes if necessary
                ])
                ->required()
                ->label('Available Sizes')
                ->helperText('Select the available sizes for this product.'),
            Forms\Components\TextInput::make('currency')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('discount')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('$'),
            Forms\Components\TextInput::make('stock')
                ->required()
                ->numeric(),
            Forms\Components\Repeater::make('colors')
                ->relationship('colors')
                ->columnSpanFull()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Color Name'),

                    Forms\Components\FileUpload::make('images')
                        ->image()
                        ->multiple()
                        ->panelLayout('grid')
                        ->directory('color/images')
                        ->label('Color Image'),
                ])
                ->label('Colors and Images')
                ->minItems(1),

        ];
    }
}
