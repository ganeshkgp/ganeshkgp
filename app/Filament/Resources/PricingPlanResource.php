<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingPlanResource\Pages;
use App\Models\PricingPlan;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static \UnitEnum|string|null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('$'),

            TextInput::make('period')
                ->default('month')
                ->maxLength(50),

            Repeater::make('features')
                ->simple(
                    TextInput::make('feature')->required(),
                )
                ->columnSpanFull(),

            TextInput::make('sort_order')
                ->integer()
                ->default(0),

            Toggle::make('is_featured')
                ->default(false),

            Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->money('USD'),
                TextColumn::make('period'),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricingPlans::route('/'),
            'create' => Pages\CreatePricingPlan::route('/create'),
            'edit' => Pages\EditPricingPlan::route('/{record}/edit'),
        ];
    }
}
