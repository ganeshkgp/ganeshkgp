<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPostResource;
use App\Models\BlogPost;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBlogPostsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Blog Posts';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BlogPost::query()
                    ->orderByDesc('created_at')
                    ->limit(5)
            )
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->width(56)
                    ->height(40)
                    ->defaultImageUrl('https://placehold.co/56x40/1a1a1a/f0a500?text=Post')
                    ->extraImgAttributes(['class' => 'rounded']),

                TextColumn::make('title')
                    ->label('Title')
                    ->limit(55)
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('primary'),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('published_at')
                    ->label('Date')
                    ->date('d M Y')
                    ->color('gray'),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (BlogPost $record): string => BlogPostResource::getUrl('edit', ['record' => $record]))
                    ->color('gray'),
            ])
            ->paginated(false)
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No blog posts yet')
            ->emptyStateDescription('Posts created by you or the BlogBot will appear here.');
    }
}
