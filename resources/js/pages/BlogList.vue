<script setup>
import { ref, onMounted, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const posts = ref([]);
const pagination = ref(null);
const loading = ref(true);

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-IN', { month: 'short', day: 'numeric', year: 'numeric' });
}

async function fetchPosts(page = 1) {
    loading.value = true;
    try {
        const { data } = await axios.get('/api/blogs', { params: { page } });
        posts.value = data.data;
        pagination.value = {
            currentPage: data.current_page,
            lastPage: data.last_page,
            total: data.total,
        };
    } finally {
        loading.value = false;
    }
}

function goToPage(page) {
    router.push({ name: 'blog', query: { page } });
}

onMounted(() => fetchPosts(route.query.page ?? 1));
watch(() => route.query.page, (page) => fetchPosts(page ?? 1));
</script>

<template>
    <div class="min-h-screen bg-[#0e0e0e] text-white">

        <!-- Navbar back link -->
        <header class="sticky top-0 z-30 border-b border-white/5 bg-[#0e0e0e]/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-6 py-4">
                <RouterLink to="/" class="flex items-center gap-2 text-sm text-gray-400 transition-colors hover:text-[#f0a500]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back to Portfolio
                </RouterLink>
                <span class="text-gray-700">/</span>
                <span class="text-sm text-white">Blog</span>
            </div>
        </header>

        <!-- Hero -->
        <section class="py-16 text-center">
            <div class="mx-auto max-w-3xl px-6">
                <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">My Writing</p>
                <h1 class="text-4xl font-bold lg:text-5xl">Blog &amp; <span class="text-[#f0a500]">Articles</span></h1>
                <p class="mt-4 text-gray-400">Thoughts on development, design, and building products for India.</p>
            </div>
        </section>

        <!-- Posts grid -->
        <section class="mx-auto max-w-7xl px-6 pb-20">

            <!-- Loading skeletons -->
            <div v-if="loading" class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 9" :key="i" class="animate-pulse overflow-hidden rounded-lg border border-white/5 bg-[#1a1a1a]">
                    <div class="aspect-video bg-[#252525]"></div>
                    <div class="p-5 space-y-3">
                        <div class="h-3 w-20 rounded bg-[#252525]"></div>
                        <div class="h-4 w-full rounded bg-[#252525]"></div>
                        <div class="h-3 w-3/4 rounded bg-[#252525]"></div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else-if="!posts.length" class="py-20 text-center text-gray-500">
                <svg class="mx-auto mb-4 h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                </svg>
                <p>No blog posts yet. Check back soon!</p>
            </div>

            <!-- Grid -->
            <div v-else class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <RouterLink
                    v-for="post in posts"
                    :key="post.id"
                    :to="{ name: 'blog-detail', params: { slug: post.slug } }"
                    class="group overflow-hidden rounded-lg border border-white/5 bg-[#1a1a1a] transition-all hover:border-[#f0a500]/40 hover:-translate-y-1"
                >
                    <div class="aspect-video overflow-hidden bg-[#222]">
                        <img v-if="post.image" :src="`/storage/${post.image}`" :alt="post.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div v-else class="flex h-full w-full items-center justify-center text-gray-700">
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-3 flex items-center gap-2">
                            <span v-if="post.category" class="rounded-full bg-[#f0a500]/10 px-2.5 py-0.5 text-xs font-semibold text-[#f0a500]">{{ post.category }}</span>
                            <span class="text-xs text-gray-600">{{ formatDate(post.published_at) }}</span>
                        </div>
                        <h2 class="mb-2 font-bold leading-snug transition-colors group-hover:text-[#f0a500]">{{ post.title }}</h2>
                        <p v-if="post.excerpt" class="line-clamp-2 text-sm leading-relaxed text-gray-500">{{ post.excerpt }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-xs text-[#f0a500]">
                            Read More
                            <svg class="h-3 w-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </RouterLink>
            </div>

            <!-- Pagination -->
            <div v-if="pagination && pagination.lastPage > 1" class="mt-12 flex items-center justify-center gap-2">
                <button
                    @click="goToPage(pagination.currentPage - 1)"
                    :disabled="pagination.currentPage === 1"
                    class="flex h-9 w-9 items-center justify-center rounded border border-white/10 text-gray-400 transition-colors hover:border-[#f0a500] hover:text-[#f0a500] disabled:opacity-30"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button
                    v-for="page in pagination.lastPage"
                    :key="page"
                    @click="goToPage(page)"
                    :class="['h-9 w-9 rounded border text-sm font-medium transition-colors', pagination.currentPage === page ? 'border-[#f0a500] bg-[#f0a500] text-black' : 'border-white/10 text-gray-400 hover:border-[#f0a500] hover:text-[#f0a500]']"
                >
                    {{ page }}
                </button>

                <button
                    @click="goToPage(pagination.currentPage + 1)"
                    :disabled="pagination.currentPage === pagination.lastPage"
                    class="flex h-9 w-9 items-center justify-center rounded border border-white/10 text-gray-400 transition-colors hover:border-[#f0a500] hover:text-[#f0a500] disabled:opacity-30"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>

        </section>
    </div>
</template>
