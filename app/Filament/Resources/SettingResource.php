<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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

            // ── Site Identity ────────────────────────────────────────────
            Section::make('Site Identity')
                ->description('Brand name, logo, and favicon shown across the entire site.')
                ->icon('heroicon-o-identification')
                ->columns(2)
                ->schema([
                    TextInput::make('site_name')
                        ->label('Site / brand name')
                        ->placeholder('Portfo.')
                        ->maxLength(100)
                        ->required(),

                    TextInput::make('site_tagline')
                        ->label('Site tagline')
                        ->placeholder('Full-Stack Developer for Hire')
                        ->maxLength(200),

                    FileUpload::make('site_favicon')
                        ->label('Favicon')
                        ->disk('public')
                        ->directory('settings/identity')
                        ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml'])
                        ->maxSize(128)
                        ->helperText('Recommended: 32×32 or 48×48 .ico / .png / .svg. Max 128 KB.')
                        ->image(),

                    FileUpload::make('site_logo')
                        ->label('Logo (optional)')
                        ->disk('public')
                        ->directory('settings/identity')
                        ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp'])
                        ->maxSize(512)
                        ->helperText('Transparent PNG or SVG, any size. Max 512 KB.')
                        ->image(),
                ]),

            // ── SEO — Meta Tags ──────────────────────────────────────────
            Section::make('SEO — Meta Tags')
                ->description('Controls what search engines and link previews show for your site.')
                ->icon('heroicon-o-magnifying-glass')
                ->columns(2)
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Page title (meta <title>)')
                        ->placeholder('Ganesh — Full-Stack Developer · Laravel · Flutter')
                        ->maxLength(70)
                        ->helperText('Leave blank to use the Site Name. Recommended: 50–60 characters.')
                        ->columnSpanFull(),

                    Textarea::make('meta_description')
                        ->label('Meta description')
                        ->placeholder('Hire a Kharagpur-based full-stack developer for web apps, Flutter mobile apps, and Unity games.')
                        ->rows(3)
                        ->maxLength(160)
                        ->helperText('Recommended: 120–160 characters. Shown in Google search results.')
                        ->columnSpanFull(),

                    TextInput::make('meta_keywords')
                        ->label('Meta keywords')
                        ->placeholder('laravel developer, flutter developer, freelance developer india')
                        ->maxLength(500)
                        ->helperText('Comma-separated keywords. Not heavily weighted by Google but useful for Bing.')
                        ->columnSpanFull(),
                ]),

            // ── Open Graph / Social Sharing ──────────────────────────────
            Section::make('Open Graph — Social Sharing')
                ->description('Controls how your site appears when shared on WhatsApp, LinkedIn, Twitter, Facebook, etc.')
                ->icon('heroicon-o-share')
                ->columns(2)
                ->schema([
                    Select::make('meta_og_type')
                        ->label('OG type')
                        ->options([
                            'website' => 'website',
                            'profile' => 'profile',
                            'article' => 'article',
                        ])
                        ->default('website')
                        ->required(),

                    Placeholder::make('og_preview_hint')
                        ->label('')
                        ->content('The title and description from the SEO section above are also used as og:title and og:description.'),

                    FileUpload::make('meta_og_image')
                        ->label('OG share image')
                        ->disk('public')
                        ->directory('settings/seo')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->helperText('Recommended: 1200×630 px, JPEG/PNG/WebP. Max 2 MB.')
                        ->image()
                        ->columnSpanFull(),
                ]),

            // ── Hero Section ─────────────────────────────────────────────
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
                        ->placeholder('Full-Stack Developer · Laravel · MEVN · Flutter')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('hero_bio')
                        ->label('Short bio')
                        ->rows(3)
                        ->columnSpanFull(),

                    FileUpload::make('hero_image')
                        ->label('Hero photo')
                        ->disk('public')
                        ->directory('settings/hero')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->image()
                        ->columnSpanFull(),
                ]),

            // ── About Section ────────────────────────────────────────────
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
                        ->disk('public')
                        ->directory('settings/about')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->image(),

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

            // ── Contact Details ──────────────────────────────────────────
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

            // ── Social Links ─────────────────────────────────────────────
            Section::make('Social Links')
                ->description('Leave blank to hide a social icon.')
                ->icon('heroicon-o-link')
                ->columns(2)
                ->schema([
                    TextInput::make('social_github')->label('GitHub URL')->url()->maxLength(500),
                    TextInput::make('social_linkedin')->label('LinkedIn URL')->url()->maxLength(500),
                    TextInput::make('social_twitter')->label('Twitter / X URL')->url()->maxLength(500),
                    TextInput::make('social_instagram')->label('Instagram URL')->url()->maxLength(500),
                    TextInput::make('social_facebook')->label('Facebook URL')->url()->maxLength(500),
                ]),

            // ── Footer ───────────────────────────────────────────────────
            Section::make('Footer')
                ->description('Footer tagline and marquee brand logos.')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->columns(1)
                ->schema([
                    Textarea::make('footer_description')
                        ->label('Footer tagline')
                        ->rows(2),

                    TagsInput::make('brands')
                        ->label('Client / partner brand names (marquee)')
                        ->placeholder('Add brand name and press Enter'),
                ]),

            // ── Analytics ───────────────────────────────────────────────
            Section::make('Analytics & Tracking')
                ->description('Paste your tracking IDs to enable analytics. Leave blank to disable.')
                ->icon('heroicon-o-chart-bar')
                ->columns(2)
                ->schema([
                    TextInput::make('google_analytics_id')
                        ->label('Google Analytics 4 Measurement ID')
                        ->placeholder('G-XXXXXXXXXX')
                        ->maxLength(50)
                        ->helperText('Found in GA4 → Admin → Data Streams → your stream.'),

                    TextInput::make('google_tag_manager_id')
                        ->label('Google Tag Manager Container ID')
                        ->placeholder('GTM-XXXXXXX')
                        ->maxLength(50)
                        ->helperText('Found in GTM → Admin → Container Settings.'),
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
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        try {
            return static::getUrl('edit', ['record' => Setting::current()->id]);
        } catch (\Throwable) {
            return static::getUrl('index');
        }
    }
}
