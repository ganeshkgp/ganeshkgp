<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput\Mask;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->required()
                    ->maxLength(1000)
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('icon')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('Enter an emoji icon')
                    ->helperText('Use an emoji to represent this service'),

                ColorPicker::make('color')
                    ->required()
                    ->default('#00ffff'),

                KeyValue::make('features')
                    ->required()
                    ->keyLabel('Feature')
                    ->valueLabel('Description')
                    ->reorderable()
                    ->addable()
                    ->deletable()
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }
}
