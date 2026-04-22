<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Laravel 13 Is Here — Everything You Need to Know',
                'slug' => 'laravel-13-everything-you-need-to-know',
                'category' => 'Laravel',
                'image' => 'blog/laravel-13-features.jpg',
                'excerpt' => 'From automatic eager loading to enhanced type safety and the new AsUri cast, Laravel 13 ships features that make everyday development faster and less error-prone.',
                'published_at' => now()->subDays(2),
                'is_published' => true,
                'content' => <<<'HTML'
<p>Laravel 13 landed with a clear mission: make backend development feel even more natural. Whether you've been following the release notes or not, this post walks you through the features that will actually change how you write code day to day.</p>

<h2>Automatic Eager Loading — Goodbye N+1</h2>
<p>One of the most celebrated additions is <strong>automatic eager loading</strong>. For years, developers had to manually remember to add <code>->with()</code> on every query or face silent performance bombs in production. Laravel 13 lets you configure relationships to always be eager-loaded at the model level:</p>

<pre><code>protected $with = ['profile', 'roles'];</code></pre>

<p>Combined with the query log during development, spotting N+1 issues before they reach production has never been easier.</p>

<h2>The New AsUri Cast</h2>
<p>Handling URLs and URIs in models used to mean juggling raw strings. The new <code>AsUri</code> cast turns any URI column into a first-class <code>Uri</code> object, giving you parsing, comparison, and manipulation methods out of the box:</p>

<pre><code>protected $casts = [
    'website' => AsUri::class,
];

// Now you can do:
$user->website->getHost(); // 'example.com'</code></pre>

<h2>Type Safety Across the Framework</h2>
<p>Laravel 13 doubles down on PHP 8.2+ type hints throughout the framework. This means better IDE autocompletion, fewer runtime surprises, and codebases that are significantly easier to onboard new developers into. If you've been putting off adding return types to your controllers and services — now is the time.</p>

<h2>Starter Kits for React, Vue, and Livewire</h2>
<p>The official starter kits have been rebuilt from the ground up. The Vue starter gives you a production-ready Inertia + Vue 3 + Tailwind setup out of the box — with authentication, dark mode, and sensible defaults pre-wired. No more boilerplate from scratch.</p>

<h2>Enhanced Pest Integration</h2>
<p>Laravel 13 ships with Pest 4 and introduces native <strong>snapshot testing</strong> for Blade views, JSON responses, and HTTP payloads. Writing regression tests for complex API responses is now a single <code>->assertMatchesSnapshot()</code> call.</p>

<h2>Should You Upgrade?</h2>
<p>If you're on Laravel 12, the upgrade path is straightforward — the team has kept breaking changes minimal. Laravel 12 receives bug fixes until August 2026, but getting onto 13 sooner gives you access to these quality-of-life improvements immediately.</p>

<p>The framework keeps getting better. If you're building something new in 2025, there's no reason not to start on 13.</p>
HTML,
            ],

            [
                'title' => 'Vue 3 Composables: Stop Writing the Same Code Twice',
                'slug' => 'vue-3-composables-stop-writing-same-code-twice',
                'category' => 'Vue.js',
                'image' => 'blog/vue-composables.jpg',
                'excerpt' => 'Composables are the most powerful pattern in Vue 3 — yet most developers underuse them. Here\'s how to write composables that are clean, reusable, and performant.',
                'published_at' => now()->subDays(7),
                'is_published' => true,
                'content' => <<<'HTML'
<p>The Composition API changed how we think about Vue. But the real power isn't just <code>ref()</code> and <code>computed()</code> — it's <strong>composables</strong>: reusable logic functions that you can drop into any component. If you're still repeating fetch logic, form state, or event listeners across components, this post is for you.</p>

<h2>What Makes a Good Composable?</h2>
<p>A composable does one thing well. It accepts inputs (either primitive values or reactive refs), manages its own internal state, and returns whatever the caller needs. Think of it like a hook in React, but with Vue's reactivity baked in.</p>

<p>A bad composable tries to do too much — it handles both data fetching <em>and</em> UI state <em>and</em> error formatting. Keep them focused.</p>

<h2>The useFetch Pattern</h2>
<p>Every project has async data fetching. Instead of duplicating loading/error/data state in every component, centralise it:</p>

<pre><code>import { ref } from 'vue';
import axios from 'axios';

export function useFetch(url) {
    const data = ref(null);
    const loading = ref(true);
    const error = ref(null);

    axios.get(url)
        .then(res => { data.value = res.data; })
        .catch(err => { error.value = err; })
        .finally(() => { loading.value = false; });

    return { data, loading, error };
}</code></pre>

<p>Now any component gets consistent loading/error handling with one line: <code>const { data, loading } = useFetch('/api/posts');</code></p>

<h2>Memory-Efficient Composables</h2>
<p>Here's a performance tip most developers miss: every time you call a composable, Vue runs the entire function and allocates memory for everything declared inside it. If you have utility functions that don't depend on reactive state, <strong>declare them outside the composable function</strong> so they're shared across all callers:</p>

<pre><code>// Shared — allocated once
function formatDate(date) {
    return new Date(date).toLocaleDateString('en-IN');
}

export function usePosts() {
    const posts = ref([]);
    // formatDate is reused, not re-created
    return { posts, formatDate };
}</code></pre>

<h2>Avoid Watchers When Computed Works</h2>
<p>Watchers are the most overused feature in Vue. Before reaching for <code>watch()</code>, ask: "can I express this as a computed property?" Computed properties are lazy, cached, and much easier to reason about. Watchers make sense for side effects — API calls, localStorage writes, DOM manipulation. Everything else: use <code>computed()</code>.</p>

<h2>Composables vs Components</h2>
<p>The rule is simple: <strong>use composables for logic, components for templates.</strong> If your composable starts returning JSX or template strings, you've gone too far — extract a component instead.</p>

<p>Composables are one of those patterns that seem simple on the surface but reward you massively as your codebase grows. Start small, stay focused, and you'll wonder how you ever lived without them.</p>
HTML,
            ],

            [
                'title' => 'Flutter vs React Native in 2025: An Indian Developer\'s Take',
                'slug' => 'flutter-vs-react-native-2025-indian-developer-take',
                'category' => 'Mobile',
                'image' => 'blog/flutter-vs-react-native.jpg',
                'excerpt' => 'The cross-platform mobile debate rages on. After building with both, here\'s an honest breakdown of which framework makes more sense for Indian startups and freelancers in 2025.',
                'published_at' => now()->subDays(14),
                'is_published' => true,
                'content' => <<<'HTML'
<p>Every few months this debate resurfaces. Flutter or React Native? As someone who's shipped projects with both, I want to give you a practical answer — not a benchmark chart, but a real-world perspective from the Indian dev ecosystem.</p>

<h2>The Short Answer</h2>
<p>If you already know JavaScript well (especially React), <strong>React Native will get you shipping faster</strong>. If you're starting fresh or your client needs pixel-perfect UI consistency across Android and iOS, <strong>Flutter is the better long-term bet</strong>.</p>

<h2>Performance: Flutter Wins — But the Gap Is Closing</h2>
<p>Flutter compiles directly to native ARM code and uses its own rendering engine. There's no JavaScript bridge, which means animations and complex UIs run smoother. For apps with heavy custom UI — think Zomato's food cards or CRED's micro-animations — Flutter gives you more control.</p>

<p>React Native's new <strong>Fabric architecture</strong> (shipped in 0.76) removes the old JS bridge and brings it much closer to Flutter in performance. For most typical apps — forms, lists, dashboards — you won't feel the difference.</p>

<h2>The Talent Pool Problem (In India)</h2>
<p>Here's something those international comparisons don't highlight: finding skilled Flutter developers in Tier 2 Indian cities is genuinely hard. JavaScript is everywhere — React Native developers are abundant and often cheaper to hire if you're building a team.</p>

<p>As a freelancer, knowing Flutter makes you stand out. As a startup CTO hiring a team quickly, React Native gives you more options.</p>

<h2>Cross-Platform Story in 2025</h2>
<p>Flutter's "write once, run anywhere" promise has matured dramatically. Flutter for Web has gone from experimental to production-ready. Desktop support (Windows, macOS, Linux) is solid. If your client wants a mobile app <em>and</em> a web dashboard, Flutter lets you reuse a significant portion of the codebase.</p>

<h2>The Indian Market Consideration</h2>
<p>Low-bandwidth and mid-range Android devices dominate India. Flutter apps tend to have slightly larger initial bundle sizes, but their consistent rendering performance on lower-end devices is actually a plus — you get the same UI whether someone's on a ₹8,000 Redmi or a ₹80,000 Galaxy.</p>

<h2>My Recommendation</h2>
<p>For <strong>freelance client projects</strong> where you need to deliver fast: React Native if the client has web devs, Flutter if they want premium feel.</p>
<p>For <strong>your own product</strong>: Flutter. The experience is better, the tooling has caught up, and Dart is a genuinely pleasant language once you get past the initial learning curve.</p>

<p>Either way — pick one and go deep. Jumping between frameworks based on Twitter trends is how you end up mediocre at both.</p>
HTML,
            ],

            [
                'title' => 'Python FastAPI: Build Production-Ready APIs in 2025',
                'slug' => 'python-fastapi-production-ready-apis-2025',
                'category' => 'Python',
                'image' => 'blog/python-fastapi.jpg',
                'excerpt' => 'FastAPI has become the go-to choice for Python backend development — async-first, type-safe, and blazing fast. Here\'s how to structure a real project the right way.',
                'published_at' => now()->subDays(21),
                'is_published' => true,
                'content' => <<<'HTML'
<p>FastAPI went from "interesting new framework" to "production standard" in just a few years. In 2025, it's the default choice for Python APIs — outpacing Flask on developer experience and rivalling Go on performance. If you're building an AI service, a microservice, or just a clean REST API with Python, FastAPI is worth your attention.</p>

<h2>Why FastAPI?</h2>
<p>Three things set it apart: <strong>async-first design</strong>, <strong>automatic validation via Pydantic</strong>, and <strong>zero-config interactive docs</strong>. Write a route, annotate your types, and FastAPI gives you Swagger UI and ReDoc out of the box — no extra setup.</p>

<h2>Project Structure That Scales</h2>
<p>Don't dump everything in a single <code>main.py</code>. A clean FastAPI project separates concerns clearly:</p>

<pre><code>app/
├── main.py          # App factory, middleware
├── routers/         # Route handlers by domain
│   ├── users.py
│   └── posts.py
├── models/          # SQLAlchemy or Tortoise models
├── schemas/         # Pydantic request/response schemas
├── services/        # Business logic
└── core/
    ├── config.py    # Settings via pydantic-settings
    └── security.py  # JWT, password hashing</code></pre>

<h2>Async Done Right</h2>
<p>FastAPI's async support is powerful but easy to misuse. The rule: use <code>async def</code> only when your endpoint does real async I/O — database calls with an async driver, HTTP requests with <code>httpx</code>, file operations. If you're calling a synchronous library, use a regular <code>def</code> and FastAPI will run it in a thread pool automatically.</p>

<pre><code># Good — actual async I/O
async def get_user(user_id: int, db: AsyncSession = Depends(get_db)):
    result = await db.execute(select(User).where(User.id == user_id))
    return result.scalar_one_or_none()

# Also fine — FastAPI handles thread pool
def sync_operation():
    return some_sync_library.do_something()</code></pre>

<h2>Validation Is Free — Use It</h2>
<p>Pydantic schemas are the backbone of FastAPI. Define your request body, query params, and responses with full type annotations and you get validation, serialization, and documentation for free:</p>

<pre><code>class CreatePostRequest(BaseModel):
    title: str = Field(min_length=3, max_length=200)
    content: str
    published: bool = False

@router.post("/posts", response_model=PostResponse, status_code=201)
async def create_post(body: CreatePostRequest, db: AsyncSession = Depends(get_db)):
    ...</code></pre>

<h2>Security Checklist</h2>
<p>Before shipping any FastAPI app to production, run through this list:</p>
<ul>
<li>JWT or OAuth2 authentication on protected routes</li>
<li>CORS restricted to known origins</li>
<li>Secrets loaded from environment variables, never hardcoded</li>
<li>Rate limiting via slowapi or a reverse proxy</li>
<li>HTTPS enforced at the infrastructure level</li>
</ul>

<p>FastAPI makes the right choices easy. The framework won't fight you — but it also won't protect you if you skip the basics.</p>
HTML,
            ],

            [
                'title' => 'Full Stack in 2025: Why Laravel + Vue Is Still the Best Combo',
                'slug' => 'full-stack-2025-laravel-vue-best-combo',
                'category' => 'Full Stack',
                'image' => 'blog/laravel-vue-fullstack.jpg',
                'excerpt' => 'With so many full stack options available, Laravel + Vue continues to stand out for its developer experience, performance, and practical path to production.',
                'published_at' => now()->subDays(30),
                'is_published' => true,
                'content' => <<<'HTML'
<p>The full stack landscape in 2025 is noisier than ever. Next.js dominates the headlines, Remix has a passionate following, and SvelteKit keeps growing. But for developers building real products for real clients — especially in the Indian market — <strong>Laravel + Vue</strong> remains one of the most productive combinations available.</p>

<h2>The Backend: Laravel Still Leads</h2>
<p>Laravel's greatest strength has always been developer velocity. Eloquent ORM, built-in queues, first-class testing with Pest, Filament for admin panels — the ecosystem is mature, well-documented, and battle-tested. When you pick up a Laravel project after months away, you know exactly where everything is.</p>

<p>Laravel 13 doubled down on this with enhanced type safety, improved starter kits, and automatic eager loading. The framework is not standing still.</p>

<h2>The Frontend: Vue 3 Composition API</h2>
<p>Vue 3's Composition API solved the biggest criticism of the Options API — logic sprawl. With composables, you can extract and reuse stateful logic across components cleanly. Combined with Vite's instant HMR, the development experience is fast and enjoyable.</p>

<p>Vue's template syntax is also the gentlest onboarding path for developers coming from traditional HTML backgrounds — something that matters when you're working with a mixed team or handing off to a client's in-house developer.</p>

<h2>The Glue: Two Approaches</h2>
<p>You have two solid options for connecting the two:</p>

<blockquote>
<strong>Inertia.js</strong> — Write Laravel controllers that return Vue pages directly. No REST API needed. This is the fastest path to a full stack app and works brilliantly for standard CRUD applications.
</blockquote>

<blockquote>
<strong>Laravel API + Vue SPA</strong> — Decouple the backend completely. Ideal when the same API will serve a mobile app or third-party integrations. More boilerplate upfront, but more flexibility long term.
</blockquote>

<h2>What About Next.js or Nuxt?</h2>
<p>Both are excellent. But they come with tradeoffs: you're responsible for the backend too (or you're locked into serverless functions), deployment is more complex, and the JavaScript ecosystem moves fast in ways that can leave your dependencies stale.</p>

<p>Laravel handles auth, queues, mail, storage, caching, and database migrations as first-class features. You're not assembling 12 npm packages to get the same.</p>

<h2>My Take</h2>
<p>For client work with a defined scope and a deadline, Laravel + Vue consistently delivers. You ship faster, the codebase stays readable, and when something breaks in production you have mature tooling to debug it.</p>

<p>For greenfield SaaS products where you need maximum flexibility — especially if you're planning a mobile app — consider the API-first approach with Laravel as a backend and Vue (or Flutter) on the frontend.</p>

<p>Either way, this stack has a proven track record. Pick it, learn it deeply, and you'll be productive for years.</p>
HTML,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }

        $this->command->info('✅ 5 blog posts seeded with cover images.');
    }
}
