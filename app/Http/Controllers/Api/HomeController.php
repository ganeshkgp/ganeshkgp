<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Service;
use App\Models\Contact;
use App\Models\Blog;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Get all data for frontend
     */
    public function index()
    {
        return response()->json([
            'projects' => Project::active()->ordered()->get(),
            'services' => Service::active()->ordered()->get(),
            'featured_blogs' => Blog::published()->featured()->ordered()->take(3)->get(),
        ]);
    }

    /**
     * Get projects data
     */
    public function projects()
    {
        $projects = Project::active()->ordered()->get()->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'image' => $project->thumbnail_url,
                'technologies' => $project->technologies,
                'live_url' => $project->live_url,
                'github_url' => $project->github_url,
                'demo_url' => $project->demo_url,
                'color' => $project->color ?? '#00ffff',
                'maxHealth' => 3,
                'featured' => $project->featured,
            ];
        });

        return response()->json($projects);
    }

    /**
     * Get services data
     */
    public function services()
    {
        $services = Service::active()->ordered()->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'icon' => $service->icon,
                'color' => $service->color,
                'features' => $service->features,
            ];
        });

        return response()->json($services);
    }

    /**
     * Get services data formatted as planets for space portfolio
     */
    public function planets()
    {
        $services = Service::active()->ordered()->get()->map(function ($service, $index) {
            return [
                'id' => $service->id,
                'name' => $service->title,
                'subtitle' => 'Service Expertise',
                'icon' => $service->icon,
                'color' => $service->color,
                'position' => [
                    'x' => 8 + ($index * 4),
                    'y' => 0,
                    'z' => 0
                ],
                'size' => 2 + (mt_rand() / mt_getrandmax()) * 1.5, // Random size between 2-3.5
                'experience' => mt_rand(1, 5), // Random experience years
                'description' => $service->description,
                'technologies' => $service->features,
                'projects' => [
                    [
                        'id' => $service->id,
                        'name' => $service->title . ' Projects',
                        'description' => 'Explore various ' . strtolower($service->title) . ' projects and implementations',
                        'link' => '#services/' . $service->id,
                        'technologies' => $service->features
                    ]
                ]
            ];
        });

        return response()->json($services);
    }

    /**
     * Get all published blogs with pagination
     */
    public function blogs(Request $request)
    {
        $perPage = $request->get('per_page', 12); // Default 12 blogs per page
        $page = $request->get('page', 1);

        $blogs = Blog::published()->ordered()->paginate($perPage, ['*'], 'page', $page);

        $blogCollection = $blogs->getCollection()->map(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'excerpt' => $blog->excerpt,
                'featured_image' => $blog->featured_image,
                'tags' => $blog->tags,
                'category' => $blog->category,
                'reading_time' => $blog->reading_time,
                'is_featured' => $blog->is_featured,
                'published_at' => $blog->published_at->format('M j, Y'),
                'author' => [
                    'name' => 'Ganesh Khanderao',
                    'avatar' => '/images/avatar.jpg',
                    'bio' => 'Full Stack Developer & Tech Enthusiast'
                ],
                'stats' => [
                    'views' => mt_rand(100, 5000),
                    'likes' => mt_rand(20, 200),
                    'comments' => mt_rand(5, 50)
                ]
            ];
        });

        return response()->json([
            'data' => $blogCollection,
            'pagination' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
                'from' => $blogs->firstItem(),
                'to' => $blogs->lastItem(),
                'has_more_pages' => $blogs->hasMorePages(),
                'next_page_url' => $blogs->nextPageUrl(),
                'prev_page_url' => $blogs->previousPageUrl(),
            ]
        ]);
    }

    /**
     * Get blog details by slug
     */
    public function blogDetails($slug)
    {
        $blog = Blog::published()->where('slug', $slug)->first();

        if (!$blog) {
            return response()->json([
                'error' => 'Blog not found',
                'message' => 'The requested blog post could not be found.'
            ], 404);
        }

        // Increment view count (you might want to implement this differently)
        // $blog->increment('views');

        $blogData = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'excerpt' => $blog->excerpt,
            'content' => $blog->content,
            'featured_image' => $blog->featured_image,
            'tags' => $blog->tags ?? [],
            'category' => $blog->category,
            'reading_time' => $blog->reading_time,
            'is_featured' => $blog->is_featured,
            'published_at' => $blog->published_at->format('M j, Y'),
            'created_at' => $blog->created_at->format('M j, Y'),
            'updated_at' => $blog->updated_at->format('M j, Y'),
            'author' => [
                'name' => 'Ganesh Khanderao',
                'avatar' => '/images/avatar.jpg',
                'bio' => 'Full Stack Developer & Tech Enthusiast',
                'social' => [
                    'github' => 'https://github.com/ganeshkgp',
                    'linkedin' => 'https://linkedin.com/in/ganeshkgp',
                    'twitter' => 'https://twitter.com/ganeshkgp'
                ]
            ],
            'stats' => [
                'views' => mt_rand(100, 5000),
                'likes' => mt_rand(20, 200),
                'comments' => mt_rand(5, 50)
            ],
            'table_of_contents' => $this->generateTableOfContents($blog->content),
            'related_posts' => $this->getRelatedPosts($blog)
        ];

        return response()->json($blogData);
    }

    /**
     * Generate table of contents from blog content
     */
    private function generateTableOfContents($content)
    {
        // Extract headings from HTML content
        preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER);

        $toc = [];
        foreach ($matches as $match) {
            $level = (int)$match[1];
            $title = strip_tags($match[2]);
            $slug = Str::slug($title);

            $toc[] = [
                'level' => $level,
                'title' => $title,
                'slug' => $slug
            ];
        }

        return $toc;
    }

    /**
     * Get related blog posts based on category and tags
     */
    private function getRelatedPosts($currentBlog)
    {
        return Blog::published()
            ->where('id', '!=', $currentBlog->id)
            ->where(function($query) use ($currentBlog) {
                $query->where('category', $currentBlog->category)
                      ->orWhereJsonContains('tags', $currentBlog->tags ?? []);
            })
            ->inRandomOrder()
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'reading_time', 'published_at'])
            ->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'excerpt' => $blog->excerpt,
                    'featured_image' => $blog->featured_image,
                    'reading_time' => $blog->reading_time,
                    'published_at' => $blog->published_at->format('M j, Y')
                ];
            });
    }

    /**
     * Generate and store AI-powered blog post
     */
    public function generateBlogPost(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'category' => 'required|string|in:technology,programming,web-development,mobile-development,ai-ml,devops,cybersecurity,tutorial,opinion,news',
            'tone' => 'nullable|string|in:professional,casual,technical,educational',
            'target_audience' => 'nullable|string|max:255',
            'keywords' => 'nullable|array',
            'length' => 'nullable|integer|min:500|max:3000',
        ]);

        try {
            // Generate blog content using AI simulation
            $blogContent = $this->generateAIContent($validated);

            // Create slug from title
            $slug = Str::slug($blogContent['title']);

            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Blog::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create the blog post
            $blog = Blog::create([
                'title' => $blogContent['title'],
                'slug' => $slug,
                'excerpt' => $blogContent['excerpt'],
                'content' => $blogContent['content'],
                'featured_image' => $blogContent['featured_image'] ?? null,
                'tags' => $blogContent['tags'] ?? [],
                'category' => $validated['category'],
                'reading_time' => $this->calculateReadingTime($blogContent['content']),
                'is_published' => false, // Start as draft for review
                'is_featured' => false,
                'published_at' => null,
                'sort_order' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'AI-generated blog post created successfully!',
                'blog' => [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'excerpt' => $blog->excerpt,
                    'category' => $blog->category,
                    'reading_time' => $blog->reading_time,
                    'tags' => $blog->tags,
                    'is_draft' => true,
                    'created_at' => $blog->created_at,
                ],
                'ai_metadata' => [
                    'topic' => $validated['topic'],
                    'generated_at' => now()->toISOString(),
                    'word_count' => str_word_count(strip_tags($blogContent['content'])),
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate AI blog post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate AI content for blog post
     */
    private function generateAIContent($input)
    {
        // This is a simulated AI content generation
        // In a real implementation, you would integrate with AI services like OpenAI, Claude, etc.

        $titleTemplates = [
            'Complete Guide to {topic}: Everything You Need to Know',
            '{topic} in 2024: Trends, Best Practices, and Future Outlook',
            'Mastering {topic}: A Comprehensive Tutorial for Developers',
            'The Ultimate {topic} Handbook: From Basics to Advanced Concepts',
            'Exploring {topic}: Modern Approaches and Real-World Applications',
            '{topic} Deep Dive: Technical Implementation and Best Practices',
        ];

        $title = str_replace('{topic}', $input['topic'], $titleTemplates[array_rand($titleTemplates)]);

        // Generate content based on category and topic
        $content = $this->generateContentByCategory($input);

        // Generate tags
        $tags = $this->generateTags($input);

        // Generate excerpt
        $excerpt = $this->generateExcerpt($content);

        // Generate featured image suggestion
        $featuredImage = $this->generateFeaturedImageUrl($input['category'], $input['topic']);

        return [
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'tags' => $tags,
            'featured_image' => $featuredImage,
        ];
    }

    /**
     * Generate content based on category
     */
    private function generateContentByCategory($input)
    {
        $category = $input['category'];
        $topic = $input['topic'];
        $tone = $input['tone'] ?? 'professional';

        $sections = [
            'introduction' => $this->generateIntroduction($topic, $tone),
            'main_content' => $this->generateMainContent($topic, $category, $tone),
            'code_examples' => $this->generateCodeExamples($category),
            'best_practices' => $this->generateBestPractices($topic, $category),
            'conclusion' => $this->generateConclusion($topic, $tone),
        ];

        $htmlContent = '';
        foreach ($sections as $section => $content) {
            if ($content) {
                $htmlContent .= $content . "\n\n";
            }
        }

        return $htmlContent;
    }

    /**
     * Generate introduction section
     */
    private function generateIntroduction($topic, $tone)
    {
        $intros = [
            "In today's rapidly evolving tech landscape, <strong>{$topic}</strong> has emerged as a critical component for developers and businesses alike. This comprehensive guide will walk you through everything you need to know about this powerful technology.",
            "Welcome to your complete guide on <strong>{$topic}</strong>. Whether you're a beginner looking to get started or an experienced developer seeking to enhance your skills, this article will provide valuable insights and practical knowledge.",
            "<strong>{$topic}</strong> has revolutionized the way we approach modern development. In this detailed exploration, we'll dive deep into the concepts, implementations, and best practices that will help you master this technology.",
        ];

        return "<h2>Introduction</h2>\n\n<p>" . $intros[array_rand($intros)] . "</p>";
    }

    /**
     * Generate main content section
     */
    private function generateMainContent($topic, $category, $tone)
    {
        $content = "<h2>Understanding {$topic}</h2>\n\n";

        $content .= "<p>At its core, {$topic} represents a paradigm shift in how we approach ";

        switch ($category) {
            case 'web-development':
                $content .= "modern web development. It encompasses the latest methodologies, frameworks, and best practices that enable developers to build robust, scalable, and maintainable web applications.";
                break;
            case 'ai-ml':
                $content .= "artificial intelligence and machine learning implementations. From basic algorithms to advanced neural networks, this field offers unprecedented opportunities for innovation and problem-solving.";
                break;
            case 'programming':
                $content .= "programming paradigms and software development. It combines theoretical knowledge with practical applications, helping developers write cleaner, more efficient code.";
                break;
            default:
                $content .= "technology challenges and opportunities. It brings together innovative solutions and proven methodologies to address real-world problems.";
        }

        $content .= "</p>\n\n";

        $content .= "<h3>Key Concepts</h3>\n\n<ul>\n";
        $concepts = $this->generateKeyConcepts($topic, $category);
        foreach ($concepts as $concept) {
            $content .= "<li>{$concept}</li>\n";
        }
        $content .= "</ul>";

        return $content;
    }

    /**
     * Generate key concepts list
     */
    private function generateKeyConcepts($topic, $category)
    {
        $conceptSets = [
            'web-development' => [
                "Component-based architecture and modular design",
                "Responsive design and mobile-first approach",
                "Performance optimization and best practices",
                "Modern JavaScript frameworks and libraries",
                "API integration and data management"
            ],
            'ai-ml' => [
                "Machine learning algorithms and models",
                "Data preprocessing and feature engineering",
                "Neural networks and deep learning",
                "Model evaluation and optimization",
                "Ethical considerations in AI development"
            ],
            'programming' => [
                "Design patterns and architectural principles",
                "Code organization and maintainability",
                "Testing strategies and quality assurance",
                "Performance optimization techniques",
                "Debugging and troubleshooting methodologies"
            ],
        ];

        return $conceptSets[$category] ?? [
            "Fundamental principles and core concepts",
            "Practical implementation strategies",
            "Best practices and optimization techniques",
            "Real-world applications and use cases",
            "Future trends and developments"
        ];
    }

    /**
     * Generate code examples section
     */
    private function generateCodeExamples($category)
    {
        if (!in_array($category, ['programming', 'web-development', 'ai-ml'])) {
            return '';
        }

        $content = "<h2>Practical Examples</h2>\n\n";

        if ($category === 'web-development') {
            $content .= "<h3>Example: Modern React Component</h3>\n\n";
            $content .= "<pre><code class=\"language-javascript\">import React, { useState, useEffect } from 'react';

const BlogComponent = () => {\n  const [posts, setPosts] = useState([]);\n  \n  useEffect(() => {\n    fetchBlogPosts();\n  }, []);\n  \n  const fetchBlogPosts = async () => {\n    try {\n      const response = await fetch('/api/posts');\n      const data = await response.json();\n      setPosts(data);\n    } catch (error) {\n      console.error('Error fetching posts:', error);\n    }\n  };\n  \n  return (\n    <div className=\"blog-container\">\n      {posts.map(post => (\n        <article key={post.id} className=\"blog-post\">\n          <h3>{post.title}</h3>\n          <p>{post.excerpt}</p>\n        </article>\n      ))}\n    </div>\n  );\n};\n\nexport default BlogComponent;</code></pre>\n\n";
        } elseif ($category === 'programming') {
            $content .= "<h3>Example: Clean Code Principles</h3>\n\n";
            $content .= "<pre><code class=\"language-php\">class BlogService\n{\n    private \$repository;\n    \n    public function __construct(BlogRepository \$repository)\n    {\n        \$this->repository = \$repository;\n    }\n    \n    public function getPublishedPosts(): Collection\n    {\n        return \$this->repository\n            ->getPublished()\n            ->sortByDesc('published_at');\n    }\n    \n    public function createPost(array \$data): Blog\n    {\n        \$blog = new Blog(\$data);\n        \$blog->generateSlug();\n        \$blog->calculateReadingTime();\n        \n        return \$this->repository->save(\$blog);\n    }\n}</code></pre>\n\n";
        }

        return $content;
    }

    /**
     * Generate best practices section
     */
    private function generateBestPractices($topic, $category)
    {
        $content = "<h2>Best Practices and Recommendations</h2>\n\n";

        $practices = [
            "Always start with clear requirements and project planning",
            "Implement comprehensive testing strategies throughout development",
            "Follow security best practices and stay updated on vulnerabilities",
            "Optimize for performance and scalability from the beginning",
            "Maintain clean, well-documented code for better maintainability",
            "Use version control effectively and follow team collaboration protocols",
            "Stay current with industry trends and continuous learning"
        ];

        $content .= "<ul>\n";
        foreach ($practices as $practice) {
            $content .= "<li>{$practice}</li>\n";
        }
        $content .= "</ul>";

        return $content;
    }

    /**
     * Generate conclusion section
     */
    private function generateConclusion($topic, $tone)
    {
        $conclusions = [
            "As we've explored throughout this comprehensive guide, <strong>{$topic}</strong> offers powerful tools and methodologies for modern development. By implementing the concepts and best practices discussed here, you'll be well-equipped to tackle complex challenges and build innovative solutions.",
            "Mastering <strong>{$topic}</strong> is a journey that requires continuous learning and practice. The techniques covered in this article provide a solid foundation, but remember that technology evolves rapidly. Stay curious, keep experimenting, and don't hesitate to push the boundaries of what's possible.",
            "This deep dive into <strong>{$topic}</strong> has covered the essential aspects from fundamentals to advanced techniques. As you apply these concepts in your projects, you'll discover new patterns and approaches that work best for your specific needs. Keep learning, keep building, and most importantly, enjoy the process of creating amazing things.",
        ];

        return "<h2>Conclusion</h2>\n\n<p>" . $conclusions[array_rand($conclusions)] . "</p>";
    }

    /**
     * Generate relevant tags
     */
    private function generateTags($input)
    {
        $categoryTags = [
            'web-development' => ['javascript', 'react', 'vue', 'css', 'html', 'frontend', 'backend', 'api'],
            'programming' => ['clean-code', 'design-patterns', 'testing', 'refactoring', 'architecture'],
            'ai-ml' => ['machine-learning', 'neural-networks', 'data-science', 'python', 'tensorflow'],
            'mobile-development' => ['react-native', 'flutter', 'ios', 'android', 'mobile-apps'],
            'devops' => ['docker', 'kubernetes', 'ci-cd', 'automation', 'deployment'],
            'cybersecurity' => ['security', 'encryption', 'authentication', 'best-practices', 'vulnerabilities'],
        ];

        $category = $input['category'];
        $topic = strtolower($input['topic']);

        $tags = [];

        // Add topic as a tag
        $tags[] = str_replace(' ', '-', $topic);

        // Add category-specific tags
        if (isset($categoryTags[$category])) {
            $tags = array_merge($tags, array_slice($categoryTags[$category], 0, 3));
        }

        // Add user-provided keywords
        if (isset($input['keywords']) && is_array($input['keywords'])) {
            $tags = array_merge($tags, $input['keywords']);
        }

        return array_unique(array_slice($tags, 0, 6));
    }

    /**
     * Generate excerpt from content
     */
    private function generateExcerpt($content)
    {
        $plainText = strip_tags($content);
        $excerpt = substr($plainText, 0, 300);

        // Don't cut words in the middle
        $lastSpace = strrpos($excerpt, ' ');
        if ($lastSpace > 250) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }

        return $excerpt . '...';
    }

    /**
     * Generate featured image URL suggestion
     */
    private function generateFeaturedImageUrl($category, $topic)
    {
        $imageSeeds = [
            'web-development' => 'modern-web-development-code',
            'programming' => 'programming-code-technology',
            'ai-ml' => 'artificial-intelligence-technology',
            'mobile-development' => 'mobile-app-development',
            'devops' => 'devops-cloud-technology',
            'cybersecurity' => 'cybersecurity-network-protection',
        ];

        $seed = $imageSeeds[$category] ?? 'technology-programming';
        $topicSlug = str_replace(' ', '-', strtolower($topic));

        // Using Unsplash API-like URL pattern (this is a suggestion)
        return "https://source.unsplash.com/1200x630/?{$seed},{$topicSlug}";
    }

    /**
     * Calculate reading time
     */
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    /**
     * Store a new contact message
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'phone' => 'nullable|string|max:20',
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact message sent successfully!',
            'contact' => $contact
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }
}
