<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Blog::class, 'slug', ignoreRecord: true),
                Select::make('category')
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
                    ])
                    ->searchable(),
                Textarea::make('excerpt')
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('blog-images')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'blockquote',
                        'codeBlock',
                        'h1',
                        'h2',
                        'h3',
                        'link',
                        'undo',
                        'redo',
                    ]),
                FileUpload::make('featured_image')
                    ->image()
                    ->imageEditor()
                    ->directory('blog-images')
                    ->columnSpanFull(),
                TextInput::make('tags')
                    ->helperText('Enter tags separated by commas')
                    ->separator(',')
                    ->columnSpanFull(),
                TextInput::make('reading_time')
                    ->numeric()
                    ->default(5)
                    ->helperText('Estimated reading time in minutes'),
                Toggle::make('is_published')
                    ->label('Published')
                    ->helperText('Uncheck to save as draft'),
                Toggle::make('is_featured')
                    ->label('Featured')
                    ->helperText('Show in featured section'),
                DateTimePicker::make('published_at')
                    ->label('Published Date & Time')
                    ->default(now())
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),
            ]);
    }
}
