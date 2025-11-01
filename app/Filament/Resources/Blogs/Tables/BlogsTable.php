<?php

namespace App\Filament\Resources\Blogs\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->size(60)
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->title) . '&color=7F9CF5&background=EBF4FF')
                    ->label('Image'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->title),
                TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('-', ' ', $state))),
                BadgeColumn::make('reading_time')
                    ->label('Read Time')
                    ->suffix(' min')
                    ->color('info'),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('published_at')
                    ->label('Published')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return 'Never';
                        }
                        try {
                            return \Carbon\Carbon::parse($state)->format('M j, Y');
                        } catch (\Exception $e) {
                            return 'Invalid Date';
                        }
                    })
                    ->sortable()
                    ->default('Never'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'technology' => 'Technology',
                        'programming' => 'Programming',
                        'web-development' => 'Web Development',
                        'mobile-development' => 'Mobile Development',
                        'ai-ml' => 'AI & Machine Learning',
                        'devops' => 'DevOps',
                        'cybersecurity' => 'Cybersecurity',
                        'tutorial' => 'Tutorial',
                        'opinion' => 'Opinion',
                        'news' => 'News',
                    ]),
                TernaryFilter::make('is_published')
                    ->label('Published')
                    ->placeholder('All blogs')
                    ->trueLabel('Published only')
                    ->falseLabel('Drafts only')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_published', true),
                        false: fn (Builder $query) => $query->where('is_published', false),
                        blank: fn (Builder $query) => $query,
                    ),
                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All blogs')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_featured', true),
                        false: fn (Builder $query) => $query->where('is_featured', false),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
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
}
