<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\AiRewriteService;
use App\Services\BlogImageService;
use App\Services\RssFeedService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AutoPostBlog extends Command
{
    protected $signature = 'blog:auto-post
                            {--limit=2 : Max posts to publish per run}
                            {--dry-run : Fetch and show what would be posted, without saving}
                            {--category= : Only fetch a specific category}';

    protected $description = 'Fetch articles from RSS feeds, rewrite with AI, and auto-publish as blog posts.';

    public function handle(
        RssFeedService $rss,
        AiRewriteService $ai,
        BlogImageService $images,
    ): int {
        $limit = (int) $this->option('limit');
        $dryRun = (bool) $this->option('dry-run');
        $filterCategory = $this->option('category');

        $this->info('🤖 BlogBot starting...');

        if (! $ai->isConfigured()) {
            $this->warn('⚠  ANTHROPIC_API_KEY not set — articles will be posted without AI rewriting.');
        }

        // Fetch articles
        $this->line('  Fetching articles from dev.to API, HackerNews, and RSS feeds...');
        $articles = $rss->fetchAll(perFeed: 5);

        if ($filterCategory) {
            $articles = array_filter(
                $articles,
                fn (array $a) => strtolower($a['category']) === strtolower($filterCategory),
            );
        }

        $count = count($articles);
        $this->line("  Found {$count} articles total.");

        if ($count === 0) {
            $this->error('  No articles fetched. Check your internet connection or run with -v for logs.');

            return self::FAILURE;
        }

        // Show a breakdown by category
        $byCategory = [];
        foreach ($articles as $a) {
            $byCategory[$a['category']] = ($byCategory[$a['category']] ?? 0) + 1;
        }
        foreach ($byCategory as $cat => $n) {
            $this->line("    • {$cat}: {$n}");
        }

        $posted = 0;

        foreach ($articles as $article) {
            if ($posted >= $limit) {
                break;
            }

            $url = $article['url'];

            // Skip already posted
            if (BlogPost::where('source_url', $url)->exists()) {
                $this->line("  ⏭  Already posted: {$article['title']}");

                continue;
            }

            // Skip if content is too short to be useful
            $plainLength = strlen(strip_tags($article['content']));
            if ($plainLength < 200) {
                $this->line("  ⏭  Content too short ({$plainLength} chars): {$article['title']}");

                continue;
            }

            $this->line("  ✏  Rewriting: {$article['title']}");

            // AI rewrite
            $rewritten = $ai->rewrite($article['title'], $article['content'], $article['category']);

            // Make a unique slug
            $slug = Str::slug($rewritten['title']);
            $originalSlug = $slug;
            $i = 1;
            while (BlogPost::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            if ($dryRun) {
                $this->table(
                    ['Field', 'Value'],
                    [
                        ['Title', $rewritten['title']],
                        ['Slug', $slug],
                        ['Category', $article['category']],
                        ['Excerpt', $rewritten['excerpt']],
                        ['Source', $url],
                        ['Content length', strlen($rewritten['content']) . ' chars'],
                    ],
                );
                $posted++;

                continue;
            }

            // Generate cover image
            $imagePath = $images->generate($rewritten['title'], $article['category']);

            // Save
            BlogPost::create([
                'title' => $rewritten['title'],
                'slug' => $slug,
                'excerpt' => $rewritten['excerpt'],
                'content' => $rewritten['content'],
                'image' => $imagePath,
                'category' => $article['category'],
                'author' => $article['author'],
                'source_url' => $url,
                'published_at' => now(),
                'is_published' => true,
            ]);

            $this->info("  ✅ Published: {$rewritten['title']}");
            $posted++;
        }

        if ($dryRun) {
            $this->warn("Dry-run complete. {$posted} article(s) would be posted.");
        } else {
            $this->info("✅ BlogBot done. {$posted} new post(s) published.");
        }

        return self::SUCCESS;
    }
}
