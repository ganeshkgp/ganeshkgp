<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PortfolioItem;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Welcome', [
            'services' => Service::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'title', 'description', 'icon']),

            'portfolioItems' => PortfolioItem::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'title', 'description', 'image', 'category', 'url']),

            'pricingPlans' => PricingPlan::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'price', 'period', 'features', 'is_featured']),

            'testimonials' => Testimonial::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'role', 'avatar', 'content', 'rating']),

            'blogPosts' => BlogPost::where('is_published', true)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(['id', 'title', 'slug', 'excerpt', 'image', 'category', 'published_at']),
        ]);
    }
}
