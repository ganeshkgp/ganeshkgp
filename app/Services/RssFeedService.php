<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RssFeedService
{
    /**
     * dev.to tags to pull from (free public API, no key needed).
     */
    protected array $devToTags = [
        'laravel' => 'Laravel',
        'php' => 'PHP',
        'vue' => 'Vue.js',
        'vuejs' => 'Vue.js',
        'flutter' => 'Flutter',
        'dart' => 'Flutter',
        'python' => 'Python',
        'fastapi' => 'Python',
        'webdev' => 'Full Stack',
        'fullstack' => 'Full Stack',
        'javascript' => 'Full Stack',
        'typescript' => 'Full Stack',
    ];

    /**
     * HackerNews Algolia search queries.
     */
    protected array $hackerNewsQueries = [
        'Laravel' => 'Laravel PHP',
        'Vue.js' => 'Vue.js frontend',
        'Flutter' => 'Flutter Dart mobile',
        'Python' => 'Python FastAPI backend',
        'Full Stack' => 'web development full stack',
    ];

    /**
     * Reliable RSS feeds (these actually work).
     */
    protected array $rssFeeds = [
        'Laravel' => [
            'https://laravel-news.com/feed',
            'https://freek.dev/feed',
        ],
        'PHP' => [
            'https://stitcher.io/rss',
        ],
        'Full Stack' => [
            'https://css-tricks.com/feed/',
            'https://smashingmagazine.com/feed',
        ],
    ];

    /**
     * Fetch articles from all sources and return a flat deduplicated list.
     *
     * @return array<int, array{title: string, url: string, content: string, excerpt: string, author: string, category: string, published_at: string}>
     */
    public function fetchAll(int $perFeed = 5): array
    {
        $articles = [];

        $articles = array_merge($articles, $this->fetchFromDevTo($perFeed));
        $articles = array_merge($articles, $this->fetchFromHackerNews(3));
        $articles = array_merge($articles, $this->fetchFromRss($perFeed));

        // Deduplicate by URL
        $seen = [];

        return array_values(array_filter($articles, function (array $item) use (&$seen): bool {
            if (empty($item['url']) || isset($seen[$item['url']])) {
                return false;
            }
            $seen[$item['url']] = true;

            return true;
        }));
    }

    // -------------------------------------------------------------------------
    // dev.to public REST API
    // -------------------------------------------------------------------------

    /**
     * @return array<int, array>
     */
    protected function fetchFromDevTo(int $perTag): array
    {
        $articles = [];

        foreach ($this->devToTags as $tag => $category) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'PortfolioBlogBot/1.0',
                    'Accept' => 'application/json',
                ])->timeout(15)->get('https://dev.to/api/articles', [
                    'tag' => $tag,
                    'per_page' => $perTag,
                    'state' => 'fresh',
                    'top' => 1,
                ]);

                if (! $response->successful()) {
                    continue;
                }

                foreach ($response->json() as $item) {
                    $content = $item['body_html'] ?? $item['description'] ?? '';
                    if (empty($content)) {
                        $content = '<p>' . ($item['description'] ?? '') . '</p>';
                    }

                    $articles[] = [
                        'title' => html_entity_decode($item['title'] ?? '', ENT_QUOTES),
                        'url' => $item['url'] ?? $item['canonical_url'] ?? '',
                        'content' => $content,
                        'excerpt' => $item['description'] ?? '',
                        'author' => $item['user']['name'] ?? '',
                        'category' => $category,
                        'published_at' => $this->parseDate($item['published_at'] ?? ''),
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning("BlogBot dev.to [{$tag}]: {$e->getMessage()}");
            }
        }

        return $articles;
    }

    // -------------------------------------------------------------------------
    // HackerNews via Algolia API
    // -------------------------------------------------------------------------

    /**
     * @return array<int, array>
     */
    protected function fetchFromHackerNews(int $perQuery): array
    {
        $articles = [];

        foreach ($this->hackerNewsQueries as $category => $query) {
            try {
                $response = Http::withHeaders(['User-Agent' => 'PortfolioBlogBot/1.0'])
                    ->timeout(15)
                    ->get('https://hn.algolia.com/api/v1/search', [
                        'query' => $query,
                        'tags' => 'story',
                        'hitsPerPage' => $perQuery,
                        'numericFilters' => 'points>50',
                    ]);

                if (! $response->successful()) {
                    continue;
                }

                foreach ($response->json('hits', []) as $hit) {
                    $url = $hit['url'] ?? '';
                    if (empty($url)) {
                        continue;
                    }

                    $articles[] = [
                        'title' => html_entity_decode($hit['title'] ?? '', ENT_QUOTES),
                        'url' => $url,
                        'content' => '<p>' . ($hit['story_text'] ?? $hit['title'] ?? '') . '</p>',
                        'excerpt' => $hit['title'] ?? '',
                        'author' => $hit['author'] ?? '',
                        'category' => $category,
                        'published_at' => $this->parseDate(
                            isset($hit['created_at_i']) ? '@' . $hit['created_at_i'] : ''
                        ),
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning("BlogBot HackerNews [{$category}]: {$e->getMessage()}");
            }
        }

        return $articles;
    }

    // -------------------------------------------------------------------------
    // RSS fallback (known-good feeds only)
    // -------------------------------------------------------------------------

    /**
     * @return array<int, array>
     */
    protected function fetchFromRss(int $perFeed): array
    {
        $articles = [];

        foreach ($this->rssFeeds as $category => $urls) {
            foreach ($urls as $url) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'PortfolioBlogBot/1.0 (RSS reader)',
                        'Accept' => 'application/rss+xml, application/xml, text/xml',
                    ])->timeout(15)->get($url);

                    if (! $response->successful()) {
                        continue;
                    }

                    libxml_use_internal_errors(true);
                    $xml = simplexml_load_string($response->body());
                    libxml_clear_errors();

                    if ($xml === false) {
                        continue;
                    }

                    $count = 0;

                    // RSS 2.0
                    foreach ($xml->channel->item ?? [] as $item) {
                        if ($count >= $perFeed) {
                            break;
                        }

                        $namespaces = $item->getNamespaces(true);
                        $content = '';

                        if (isset($namespaces['content'])) {
                            $ns = $item->children($namespaces['content']);
                            $content = (string) ($ns->encoded ?? '');
                        }

                        if (empty($content)) {
                            $content = (string) $item->description;
                        }

                        $articles[] = [
                            'title' => html_entity_decode(strip_tags((string) $item->title), ENT_QUOTES),
                            'url' => (string) $item->link,
                            'content' => $content,
                            'excerpt' => $this->makeExcerpt($content),
                            'author' => (string) ($item->author ?? ''),
                            'category' => $category,
                            'published_at' => $this->parseDate((string) $item->pubDate),
                        ];

                        $count++;
                    }
                } catch (\Throwable $e) {
                    Log::warning("BlogBot RSS [{$url}]: {$e->getMessage()}");
                }
            }
        }

        return $articles;
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function makeExcerpt(string $html, int $words = 30): string
    {
        $text = preg_replace('/\s+/', ' ', trim(strip_tags($html)));
        $parts = explode(' ', $text);

        return implode(' ', array_slice($parts, 0, $words)) . (count($parts) > $words ? '…' : '');
    }

    protected function parseDate(string $dateStr): string
    {
        if (empty($dateStr)) {
            return now()->toDateTimeString();
        }

        try {
            return \Carbon\Carbon::parse($dateStr)->toDateTimeString();
        } catch (\Throwable) {
            return now()->toDateTimeString();
        }
    }
}
