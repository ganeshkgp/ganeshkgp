<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessagesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Contact Messages';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactMessage::query()
                    ->orderByRaw('is_read ASC')
                    ->orderByDesc('created_at')
                    ->limit(5)
            )
            ->columns([
                IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('warning'),

                TextColumn::make('name')
                    ->label('From')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->color('gray'),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(80)
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->color('gray'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (ContactMessage $record): string => ContactMessageResource::getUrl('edit', ['record' => $record]))
                    ->color('gray'),
            ])
            ->paginated(false)
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateHeading('No messages yet')
            ->emptyStateDescription('Contact form submissions will appear here.');
    }
}
