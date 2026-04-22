<script setup>
import { RouterLink } from 'vue-router';

defineProps({
    blogPosts: { type: Array, default: () => [] },
});

const staticBlogPosts = [
    {
        id: 1,
        slug: null,
        title: 'How Indian Startups Are Winning With Design',
        excerpt: "From Zomato to CRED, great UI/UX has become a key differentiator for India's top tech companies.",
        category: 'Design',
        published_at: null,
    },
    {
        id: 2,
        slug: null,
        title: 'Building Scalable Web Apps For Bharat',
        excerpt: 'Tips and best practices for creating fast, accessible websites optimised for low-bandwidth users across India.',
        category: 'Development',
        published_at: null,
    },
    {
        id: 3,
        slug: null,
        title: 'Visual Branding In The Age Of UPI & Digital India',
        excerpt: "How consistent visual identity helps businesses stand out in India's booming digital payment ecosystem.",
        category: 'Branding',
        published_at: null,
    },
];

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-IN', { month: 'short', day: 'numeric', year: 'numeric' });
}
</script>

<template>
    <section id="blog" class="bg-[#161616] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center">
                <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">Recent Blog</p>
                <h2 class="text-3xl font-bold lg:text-4xl">
                    My Latest <span class="text-[#f0a500]">Blog &amp; News</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <component
                    v-for="post in blogPosts.length ? blogPosts : staticBlogPosts"
                    :key="post.id"
                    :is="post.slug ? RouterLink : 'div'"
                    :to="post.slug ? { name: 'blog-detail', params: { slug: post.slug } } : undefined"
                    class="group overflow-hidden rounded-lg border border-white/5 bg-[#1a1a1a] transition-all hover:border-[#f0a500]/30"
                    :class="post.slug ? 'hover:-translate-y-1' : ''"
                >
                    <div class="aspect-video overflow-hidden bg-[#222]">
                        <img v-if="post.image" :src="`/storage/${post.image}`" :alt="post.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div v-else class="flex h-full w-full items-center justify-center text-gray-600">
                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-2 flex items-center gap-2">
                            <span v-if="post.category" class="rounded-full bg-[#f0a500]/10 px-2.5 py-0.5 text-xs font-semibold text-[#f0a500]">{{ post.category }}</span>
                            <span class="text-xs text-gray-600">{{ formatDate(post.published_at) }}</span>
                        </div>
                        <h3 class="mb-2 text-sm font-bold leading-snug transition-colors group-hover:text-[#f0a500]">{{ post.title }}</h3>
                        <p v-if="post.excerpt" class="line-clamp-2 text-xs leading-relaxed text-gray-500">{{ post.excerpt }}</p>
                        <span class="mt-3 inline-flex items-center gap-1 text-xs text-[#f0a500]">
                            Read More
                            <svg class="h-3 w-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </component>
            </div>

            <!-- View More button -->
            <div class="mt-12 text-center">
                <RouterLink
                    to="/blog"
                    class="inline-flex items-center gap-2 rounded border border-[#f0a500] px-8 py-3 text-sm font-semibold text-[#f0a500] transition-colors hover:bg-[#f0a500] hover:text-black"
                >
                    View All Posts
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </RouterLink>
            </div>
        </div>
    </section>
</template>
