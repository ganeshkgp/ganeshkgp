<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Exception;

class ScraperController extends Controller
{
    /**
     * Scrape a single blog article from a URL
     */
    public function scrapeArticle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'category' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $request->input('url');
            $html = $this->fetchHtml($url);

            if (!$html) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch content from URL'
                ], 400);
            }

            $blogData = $this->extractBlogData($html, $url);

            // Override with user-provided data
            if ($request->has('category')) {
                $blogData['category'] = $request->input('category');
            }
            if ($request->has('is_featured')) {
                $blogData['is_featured'] = $request->input('is_featured');
            }
            if ($request->has('tags')) {
                $blogData['tags'] = $request->input('tags');
            }

            // Check if blog with same slug already exists
            $existingBlog = Blog::where('slug', $blogData['slug'])->first();
            if ($existingBlog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog with this content already exists',
                    'blog_id' => $existingBlog->id
                ], 409);
            }

            $blog = Blog::create($blogData);

            return response()->json([
                'success' => true,
                'message' => 'Blog article scraped successfully',
                'data' => $blog
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to scrape article: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scrape multiple articles from RSS feed or list of URLs
     */
    public function scrapeMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_type' => 'required|in:rss,urls',
            'source' => 'required',
            'source.*' => 'url',
            'category' => 'nullable|string|max:255',
            'auto_publish' => 'nullable|boolean',
            'max_articles' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $urls = [];
            $sourceType = $request->input('source_type');
            $source = $request->input('source');
            $maxArticles = $request->input('max_articles', 10);

            if ($sourceType === 'rss') {
                $urls = $this->extractUrlsFromRss($source);
            } else {
                $urls = is_array($source) ? $source : [$source];
            }

            $urls = array_slice($urls, 0, $maxArticles);
            $results = [];
            $successCount = 0;
            $failureCount = 0;

            foreach ($urls as $url) {
                try {
                    $html = $this->fetchHtml($url);
                    if (!$html) {
                        $results[] = ['url' => $url, 'success' => false, 'message' => 'Failed to fetch content'];
                        $failureCount++;
                        continue;
                    }

                    $blogData = $this->extractBlogData($html, $url);

                    // Apply common settings
                    if ($request->has('category')) {
                        $blogData['category'] = $request->input('category');
                    }
                    if ($request->input('auto_publish', false)) {
                        $blogData['is_published'] = true;
                        $blogData['published_at'] = now();
                    }

                    // Check for duplicates
                    if (!Blog::where('slug', $blogData['slug'])->exists()) {
                        $blog = Blog::create($blogData);
                        $results[] = ['url' => $url, 'success' => true, 'blog_id' => $blog->id, 'title' => $blog->title];
                        $successCount++;
                    } else {
                        $results[] = ['url' => $url, 'success' => false, 'message' => 'Blog already exists'];
                        $failureCount++;
                    }

                } catch (Exception $e) {
                    $results[] = ['url' => $url, 'success' => false, 'message' => $e->getMessage()];
                    $failureCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Scraping completed. Success: {$successCount}, Failed: {$failureCount}",
                'summary' => [
                    'total_processed' => count($urls),
                    'successful' => $successCount,
                    'failed' => $failureCount
                ],
                'results' => $results
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to scrape articles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available tech blog RSS feeds for scraping
     */
    public function getAvailableFeeds()
    {
        $feeds = [
            [
                'name' => 'TechCrunch',
                'rss_url' => 'https://techcrunch.com/feed/',
                'category' => 'technology',
                'description' => 'Latest technology news and startup updates'
            ],
            [
                'name' => 'Hacker News',
                'rss_url' => 'https://hnrss.org/frontpage',
                'category' => 'technology',
                'description' => 'Top stories from Hacker News'
            ],
            [
                'name' => 'Ars Technica',
                'rss_url' => 'https://feeds.arstechnica.com/arstechnica/index',
                'category' => 'technology',
                'description' => 'Technology news and analysis'
            ],
            [
                'name' => 'The Verge',
                'rss_url' => 'https://www.theverge.com/rss/index.xml',
                'category' => 'technology',
                'description' => 'Technology, science, art, culture, and more'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $feeds
        ]);
    }

    /**
     * Fetch HTML content from URL
     */
    private function fetchHtml($url)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])
                ->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Extract blog data from HTML content
     */
    private function extractBlogData($html, $sourceUrl)
    {
        $crawler = new Crawler($html);

        // Extract title
        $title = $this->extractText($crawler, [
            'h1',
            'meta[property="og:title"]',
            'title',
            '.post-title',
            '.entry-title',
            'h2'
        ]);

        // Extract content
        $content = $this->extractContent($crawler);

        // Extract excerpt
        $excerpt = $this->extractText($crawler, [
            'meta[name="description"]',
            'meta[property="og:description"]',
            '.excerpt',
            '.entry-excerpt',
            '.post-excerpt'
        ]);

        if (!$excerpt && $content) {
            $excerpt = Str::limit(strip_tags($content), 200);
        }

        // Extract featured image
        $featuredImage = $this->extractAttribute($crawler, [
            'meta[property="og:image"]',
            'meta[name="twitter:image"]',
            '.featured-image img',
            '.post-image img',
            'article img'
        ], 'src');

        // Extract tags/keywords
        $tags = $this->extractTags($crawler);

        // Generate slug from title
        $slug = Str::slug($title) . '-' . Str::random(6);

        // Determine category from URL or content
        $category = $this->determineCategory($sourceUrl, $title, $content);

        // Calculate reading time (will be automatically calculated by model)
        $readingTime = 0;

        return [
            'title' => $title ?: 'Untitled Blog Post',
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'featured_image' => $featuredImage,
            'tags' => $tags,
            'category' => $category,
            'reading_time' => $readingTime,
            'is_published' => false,
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    /**
     * Extract text content using multiple selectors
     */
    private function extractText($crawler, $selectors)
    {
        foreach ($selectors as $selector) {
            try {
                if (str_starts_with($selector, 'meta')) {
                    $element = $crawler->filter($selector)->first();
                    if ($element->count()) {
                        return $element->attr('content') ?: $element->attr('value');
                    }
                } else {
                    $element = $crawler->filter($selector)->first();
                    if ($element->count()) {
                        return trim($element->text());
                    }
                }
            } catch (Exception $e) {
                continue;
            }
        }
        return null;
    }

    /**
     * Extract main article content
     */
    private function extractContent($crawler)
    {
        $contentSelectors = [
            'article',
            '.post-content',
            '.entry-content',
            '.content',
            'main',
            '.blog-content',
            '[itemprop="articleBody"]'
        ];

        foreach ($contentSelectors as $selector) {
            try {
                $element = $crawler->filter($selector)->first();
                if ($element->count()) {
                    // Remove unwanted elements
                    $element->filter('script, style, nav, header, footer, .sidebar, .ads, .advertisement')->each(function ($node) {
                        $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                    });

                    $content = trim($element->html());
                    if (strlen($content) > 500) { // Ensure we have substantial content
                        return $content;
                    }
                }
            } catch (Exception $e) {
                continue;
            }
        }

        // Fallback: get all paragraphs
        try {
            $paragraphs = $crawler->filter('p')->each(function ($node) {
                return trim($node->text());
            });

            $content = implode("\n\n", array_filter($paragraphs));
            return strlen($content) > 200 ? $content : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Extract attribute value using multiple selectors
     */
    private function extractAttribute($crawler, $selectors, $attribute)
    {
        foreach ($selectors as $selector) {
            try {
                $element = $crawler->filter($selector)->first();
                if ($element->count()) {
                    $value = $element->attr($attribute);
                    if ($value && filter_var($value, FILTER_VALIDATE_URL)) {
                        return $value;
                    }
                }
            } catch (Exception $e) {
                continue;
            }
        }
        return null;
    }

    /**
     * Extract tags from the page
     */
    private function extractTags($crawler)
    {
        $tags = [];

        // Try to extract from meta keywords
        $keywords = $this->extractText($crawler, ['meta[name="keywords"]']);
        if ($keywords) {
            $tags = array_map('trim', explode(',', $keywords));
        }

        // Try to extract from tag classes
        $tagElements = $crawler->filter('.tags a, .tag, .category a, [rel="tag"]');
        $tagElements->each(function ($node) use (&$tags) {
            $tag = trim($node->text());
            if ($tag && !in_array($tag, $tags)) {
                $tags[] = $tag;
            }
        });

        return array_slice($tags, 0, 10); // Limit to 10 tags
    }

    /**
     * Determine category based on URL and content
     */
    private function determineCategory($url, $title, $content)
    {
        $url = strtolower($url);
        $title = strtolower($title);
        $content = strtolower($content);

        $categories = [
            'technology' => ['tech', 'programming', 'software', 'code', 'developer', 'api', 'framework', 'javascript', 'python'],
            'ai' => ['artificial intelligence', 'machine learning', 'ai', 'neural network', 'deep learning', 'gpt', 'chatgpt'],
            'web-development' => ['web', 'frontend', 'backend', 'html', 'css', 'react', 'vue', 'angular', 'nodejs'],
            'mobile' => ['mobile', 'ios', 'android', 'app', 'smartphone', 'tablet'],
            'security' => ['security', 'cyber', 'hack', 'vulnerability', 'encryption', 'malware'],
            'business' => ['business', 'startup', 'entrepreneur', 'funding', 'investment', 'revenue']
        ];

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($url, $keyword) !== false || strpos($title, $keyword) !== false || strpos($content, $keyword) !== false) {
                    return $category;
                }
            }
        }

        return 'technology'; // Default category
    }

    /**
     * Extract URLs from RSS feed
     */
    private function extractUrlsFromRss($rssUrl)
    {
        try {
            $xml = simplexml_load_file($rssUrl);
            $urls = [];

            if ($xml && isset($xml->channel->item)) {
                foreach ($xml->channel->item as $item) {
                    if (isset($item->link)) {
                        $urls[] = (string) $item->link;
                    }
                }
            }

            return $urls;
        } catch (Exception $e) {
            return [];
        }
    }
}