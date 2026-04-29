<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPostResource;
use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\PortfolioItemResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.quick-actions';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'links' => [
                [
                    'label' => 'New Blog Post',
                    'url'   => BlogPostResource::getUrl('create'),
                    'icon'  => 'heroicon-o-document-plus',
                ],
                [
                    'label' => 'Edit Settings',
                    'url'   => SettingResource::getUrl('edit', ['record' => Setting::current()->id]),
                    'icon'  => 'heroicon-o-cog-6-tooth',
                ],
                [
                    'label' => 'Add Portfolio Item',
                    'url'   => PortfolioItemResource::getUrl('create'),
                    'icon'  => 'heroicon-o-briefcase',
                ],
                [
                    'label' => 'Manage Services',
                    'url'   => ServiceResource::getUrl('index'),
                    'icon'  => 'heroicon-o-wrench-screwdriver',
                ],
                [
                    'label' => 'View Messages',
                    'url'   => ContactMessageResource::getUrl('index'),
                    'icon'  => 'heroicon-o-inbox',
                ],
                [
                    'label' => 'View Live Site',
                    'url'   => 'https://ganeshkgp.in',
                    'icon'  => 'heroicon-o-globe-alt',
                ],
            ],
        ];
    }
}
