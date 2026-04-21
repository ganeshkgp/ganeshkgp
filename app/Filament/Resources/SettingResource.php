<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static \UnitEnum|string|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Hero Section')
                ->description('Content shown in the top hero banner.')
                ->icon('heroicon-o-home')
                ->columns(2)
                ->schema([
                    TextInput::make('hero_greeting')
                        ->label('Greeting line')
                        ->placeholder('Namaste, I\'m')
                        ->maxLength(100)
                        ->required(),

                    TextInput::make('hero_name')
                        ->label('Your name')
                        ->placeholder('Arjun Sharma')
                        ->maxLength(150)
                        ->required(),

                    TextInput::make('hero_tagline')
                        ->label('Tagline / role')
                        ->placeholder('Freelance UX/UI Designer & Full-Stack Developer')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('hero_bio')
                        ->label('Short bio')
                        ->rows(3)
                        ->columnSpanFull(),

                    FileUpload::make('hero_image')
                        ->label('Hero photo')
                        ->image()
                        ->directory('settings')
                        ->columnSpanFull(),
                ]),

            Section::make('About Section')
                ->description('Content for the About Me section.')
                ->icon('heroicon-o-user')
                ->columns(2)
                ->schema([
                    TextInput::make('about_title')
                        ->label('Section heading')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('about_bio')
                        ->label('About description')
                        ->rows(4)
                        ->columnSpanFull(),

                    TextInput::make('about_cv_url')
                        ->label('CV / resume URL')
                        ->url()
                        ->placeholder('https://...')
                        ->maxLength(500),

                    FileUpload::make('about_photo')
                        ->label('About photo')
                        ->image()
                        ->directory('settings'),

                    Repeater::make('skills')
                        ->label('Skills')
                        ->schema([
                            TextInput::make('name')
                                ->label('Skill name')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('level')
                                ->label('Level (%)')
                                ->integer()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add Skill')
                        ->columnSpanFull(),
                ]),

            Section::make('Contact Details')
                ->description('Displayed in the Contact section and footer.')
                ->icon('heroicon-o-phone')
                ->columns(2)
                ->schema([
                    TextInput::make('contact_email')
                        ->label('Email address')
                        ->email()
                        ->maxLength(255),

                    TextInput::make('contact_phone')
                        ->label('Phone number')
                        ->tel()
                        ->placeholder('+91 98765 43210')
                        ->maxLength(50),

                    TextInput::make('contact_address')
                        ->label('Address / location')
                        ->placeholder('Bengaluru, Karnataka, India')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            Section::make('Social Links')
                ->description('Leave blank to hide a social icon.')
                ->icon('heroicon-o-share')
                ->columns(2)
                ->schema([
                    TextInput::make('social_facebook')->label('Facebook URL')->url()->maxLength(500),
                    TextInput::make('social_twitter')->label('Twitter / X URL')->url()->maxLength(500),
                    TextInput::make('social_instagram')->label('Instagram URL')->url()->maxLength(500),
                    TextInput::make('social_linkedin')->label('LinkedIn URL')->url()->maxLength(500),
                    TextInput::make('social_github')->label('GitHub URL')->url()->maxLength(500),
                ]),

            Section::make('Site & Footer')
                ->description('General site identity and footer content.')
                ->icon('heroicon-o-globe-alt')
                ->columns(2)
                ->schema([
                    TextInput::make('site_name')
                        ->label('Site / brand name')
                        ->placeholder('Portfo.')
                        ->maxLength(100)
                        ->required(),

                    Textarea::make('footer_description')
                        ->label('Footer tagline')
                        ->rows(2)
                        ->columnSpanFull(),

                    TagsInput::make('brands')
                        ->label('Client / partner brand names')
                        ->placeholder('Add brand name and press Enter')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('edit', ['record' => Setting::current()->id]);
    }
}
