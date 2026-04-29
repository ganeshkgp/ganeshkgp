<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unread = ContactMessage::where('is_read', false)->count();

        return [
            Stat::make('Unread Messages', $unread)
                ->description($unread > 0 ? 'Waiting for your reply' : 'All caught up!')
                ->descriptionIcon($unread > 0 ? 'heroicon-m-envelope' : 'heroicon-m-check-circle')
                ->color($unread > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-inbox'),

            Stat::make('Blog Posts', BlogPost::count())
                ->description(BlogPost::where('is_published', true)->count() . ' published')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info')
                ->icon('heroicon-o-document-text'),

            Stat::make('Portfolio Items', PortfolioItem::where('is_active', true)->count())
                ->description('Active projects')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success')
                ->icon('heroicon-o-briefcase'),

            Stat::make('Services', Service::where('is_active', true)->count())
                ->description(Testimonial::count() . ' testimonials')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary')
                ->icon('heroicon-o-wrench-screwdriver'),
        ];
    }
}
