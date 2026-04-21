<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static \UnitEnum|string|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Messages';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = ContactMessage::where('is_read', false)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('phone')
                    ->placeholder('—'),

                TextColumn::make('message')
                    ->limit(60)
                    ->tooltip(fn (ContactMessage $record): string => $record->message),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                \Filament\Actions\Action::make('toggle_read')
                    ->label(fn (ContactMessage $record): string => $record->is_read ? 'Mark Unread' : 'Mark Read')
                    ->icon(fn (ContactMessage $record): string => $record->is_read ? 'heroicon-o-envelope' : 'heroicon-o-check-circle')
                    ->action(fn (ContactMessage $record) => $record->update(['is_read' => ! $record->is_read])),

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
            'index' => Pages\ListContactMessages::route('/'),
        ];
    }
}
