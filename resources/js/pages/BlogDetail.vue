<script setup>
import { ref, onMounted, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();

const post = ref(null);
const related = ref([]);
const loading = ref(true);
const notFound = ref(false);

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-IN', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
}

async function fetchPost(slug) {
    loading.value = true;
    notFound.value = false;
    try {
        const { data } = await axios.get(`/api/blogs/${slug}`);
        post.value = data.post;
        related.value = data.related;
    } catch (err) {
        if (err.response?.status === 404) {
            notFound.value = true;
        }
    } finally {
        loading.value = false;
    }
}

onMounted(() => fetchPost(route.params.slug));
watch(() => route.params.slug, (slug) => {
    window.scrollTo({ top: 0 });
    fetchPost(slug);
});
</script>

<template>
    <div class="min-h-screen bg-[#0e0e0e] text-white">

        <!-- Navbar -->
        <header class="sticky top-0 z-30 border-b border-white/5 bg-[#0e0e0e]/90 backdrop-blur">
            <div class="mx-auto flex max-w-4xl items-center gap-4 px-6 py-4">
                <RouterLink to="/" class="flex items-center gap-2 text-sm text-gray-400 transition-colors hover:text-[#f0a500]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Portfolio
                </RouterLink>
                <span class="text-gray-700">/</span>
                <RouterLink to="/blog" class="text-sm text-gray-400 transition-colors hover:text-[#f0a500]">Blog</RouterLink>
                <span v-if="post" class="text-gray-700">/</span>
                <span v-if="post" class="truncate text-sm text-white">{{ post.title }}</span>
            </div>
        </header>

        <!-- Loading skeleton -->
        <div v-if="loading" class="mx-auto max-w-4xl px-6 py-16">
            <div class="animate-pulse space-y-6">
                <div class="h-3 w-24 rounded bg-[#252525]"></div>
                <div class="h-8 w-3/4 rounded bg-[#252525]"></div>
                <div class="h-3 w-40 rounded bg-[#252525]"></div>
                <div class="aspect-video rounded-xl bg-[#252525]"></div>
                <div class="space-y-3">
                    <div class="h-3 w-full rounded bg-[#252525]"></div>
                    <div class="h-3 w-full rounded bg-[#252525]"></div>
                    <div class="h-3 w-3/4 rounded bg-[#252525]"></div>
                </div>
            </div>
        </div>

        <!-- Not found -->
        <div v-else-if="notFound" class="flex min-h-[50vh] flex-col items-center justify-center text-center">
            <p class="text-6xl font-bold text-[#f0a500]">404</p>
            <p class="mt-3 text-gray-400">This blog post doesn't exist.</p>
            <RouterLink to="/blog" class="mt-6 rounded bg-[#f0a500] px-6 py-2.5 text-sm font-semibold text-black hover:bg-[#d4920a]">
                View All Posts
            </RouterLink>
        </div>

        <!-- Post -->
        <article v-else-if="post" class="mx-auto max-w-4xl px-6 pb-24 pt-12">

            <!-- Category + date -->
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <span v-if="post.category" class="rounded-full bg-[#f0a500]/10 px-3 py-1 text-xs font-semibold text-[#f0a500]">{{ post.category }}</span>
                <span class="text-sm text-gray-500">{{ formatDate(post.published_at) }}</span>
            </div>

            <!-- Title -->
            <h1 class="mb-6 text-3xl font-bold leading-tight lg:text-4xl">{{ post.title }}</h1>

            <!-- Excerpt -->
            <p v-if="post.excerpt" class="mb-8 text-lg leading-relaxed text-gray-400">{{ post.excerpt }}</p>

            <!-- Cover image -->
            <div v-if="post.image" class="mb-10 overflow-hidden rounded-xl">
                <img :src="`/storage/${post.image}`" :alt="post.title" class="w-full object-cover" />
            </div>

            <!-- Content (rich HTML from Filament RichEditor) -->
            <div class="blog-content" v-html="post.content"></div>

            <!-- Share -->
            <div class="mt-12 border-t border-white/5 pt-8 flex items-center justify-between flex-wrap gap-4">
                <RouterLink to="/blog" class="flex items-center gap-2 text-sm text-gray-400 transition-colors hover:text-[#f0a500]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back to all posts
                </RouterLink>
            </div>
        </article>

        <!-- Related posts -->
        <section v-if="related.length" class="border-t border-white/5 bg-[#161616] py-16">
            <div class="mx-auto max-w-7xl px-6">
                <h2 class="mb-8 text-xl font-bold">More in <span class="text-[#f0a500]">{{ post?.category }}</span></h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <RouterLink
                        v-for="item in related"
                        :key="item.id"
                        :to="{ name: 'blog-detail', params: { slug: item.slug } }"
                        class="group overflow-hidden rounded-lg border border-white/5 bg-[#1a1a1a] transition-all hover:border-[#f0a500]/40"
                    >
                        <div class="aspect-video overflow-hidden bg-[#222]">
                            <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div v-else class="flex h-full items-center justify-center text-gray-700">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="mb-1 text-xs text-gray-500">{{ formatDate(item.published_at) }}</p>
                            <h3 class="text-sm font-bold leading-snug transition-colors group-hover:text-[#f0a500]">{{ item.title }}</h3>
                        </div>
                    </RouterLink>
                </div>
            </div>
        </section>

    </div>
</template>

<style scoped>
.blog-content {
    color: #d1d5db;
    line-height: 1.8;
    font-size: 1rem;
}
.blog-content :deep(h1),
.blog-content :deep(h2),
.blog-content :deep(h3),
.blog-content :deep(h4) {
    color: #ffffff;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}
.blog-content :deep(h1) { font-size: 1.875rem; }
.blog-content :deep(h2) { font-size: 1.5rem; }
.blog-content :deep(h3) { font-size: 1.25rem; }
.blog-content :deep(p) {
    margin-bottom: 1.25rem;
}
.blog-content :deep(a) {
    color: #f0a500;
    text-decoration: none;
}
.blog-content :deep(a:hover) {
    text-decoration: underline;
}
.blog-content :deep(strong) {
    color: #ffffff;
    font-weight: 600;
}
.blog-content :deep(ul),
.blog-content :deep(ol) {
    padding-left: 1.5rem;
    margin-bottom: 1.25rem;
}
.blog-content :deep(ul) { list-style-type: disc; }
.blog-content :deep(ol) { list-style-type: decimal; }
.blog-content :deep(li) {
    margin-bottom: 0.4rem;
}
.blog-content :deep(blockquote) {
    border-left: 3px solid #f0a500;
    padding: 0.75rem 1.25rem;
    margin: 1.5rem 0;
    background: rgba(240, 165, 0, 0.05);
    border-radius: 0 6px 6px 0;
    color: #9ca3af;
    font-style: italic;
}
.blog-content :deep(code) {
    background: rgba(255, 255, 255, 0.06);
    color: #f0a500;
    padding: 0.15rem 0.4rem;
    border-radius: 4px;
    font-size: 0.875em;
}
.blog-content :deep(pre) {
    background: #1a1a1a;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    padding: 1.25rem;
    overflow-x: auto;
    margin-bottom: 1.5rem;
}
.blog-content :deep(pre code) {
    background: none;
    padding: 0;
    color: #e5e7eb;
}
.blog-content :deep(img) {
    border-radius: 10px;
    max-width: 100%;
    margin: 1.5rem 0;
}
.blog-content :deep(hr) {
    border-color: rgba(255, 255, 255, 0.08);
    margin: 2rem 0;
}
</style>
