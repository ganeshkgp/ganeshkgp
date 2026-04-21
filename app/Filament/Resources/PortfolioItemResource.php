<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioItemResource\Pages;
use App\Models\PortfolioItem;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PortfolioItemResource extends Resource
{
    protected static ?string $model = PortfolioItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static \UnitEnum|string|null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            TextInput::make('category')
                ->maxLength(255),

            FileUpload::make('image')
                ->image()
                ->directory('portfolio')
                ->columnSpanFull(),

            Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),

            TextInput::make('url')
                ->url()
                ->maxLength(255),

            TextInput::make('sort_order')
                ->integer()
                ->default(0),

            Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->square(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category'),
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
            'index' => Pages\ListPortfolioItems::route('/'),
            'create' => Pages\CreatePortfolioItem::route('/create'),
            'edit' => Pages\EditPortfolioItem::route('/{record}/edit'),
        ];
    }
}
