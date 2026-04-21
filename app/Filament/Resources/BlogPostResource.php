<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static \UnitEnum|string|null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true),

            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            TextInput::make('category')
                ->maxLength(255),

            FileUpload::make('image')
                ->image()
                ->directory('blog')
                ->columnSpanFull(),

            Textarea::make('excerpt')
                ->rows(3)
                ->columnSpanFull(),

            RichEditor::make('content')
                ->columnSpanFull(),

            DateTimePicker::make('published_at'),

            Toggle::make('is_published')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category'),
                IconColumn::make('is_published')->boolean(),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
