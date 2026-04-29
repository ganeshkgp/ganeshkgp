<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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
        return $schema->schema([
            Section::make('Message Details')
                ->icon('heroicon-o-envelope')
                ->columns(2)
                ->schema([
                    Placeholder::make('name')
                        ->label('From')
                        ->content(fn (ContactMessage $record): string => $record->name),

                    Placeholder::make('email')
                        ->label('Email')
                        ->content(fn (ContactMessage $record): string => $record->email),

                    Placeholder::make('phone')
                        ->label('Phone')
                        ->content(fn (ContactMessage $record): string => $record->phone ?? '—'),

                    Placeholder::make('created_at')
                        ->label('Received')
                        ->content(fn (ContactMessage $record): string => $record->created_at->format('d M Y, h:i A')),

                    Placeholder::make('message')
                        ->label('Message')
                        ->content(fn (ContactMessage $record): string => $record->message)
                        ->columnSpanFull(),

                    Toggle::make('is_read')
                        ->label('Mark as read')
                        ->columnSpanFull(),
                ]),
        ]);
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
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (ContactMessage $record): string => static::getUrl('edit', ['record' => $record])),

                Action::make('toggle_read')
                    ->label(fn (ContactMessage $record): string => $record->is_read ? 'Mark Unread' : 'Mark Read')
                    ->icon(fn (ContactMessage $record): string => $record->is_read ? 'heroicon-o-envelope' : 'heroicon-o-check-circle')
                    ->color(fn (ContactMessage $record): string => $record->is_read ? 'gray' : 'success')
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
            'edit'  => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
