<script setup>
defineProps({
    loading: Boolean,
    portfolioItems: { type: Array, default: () => [] },
});
</script>

<template>
    <section id="portfolio" class="bg-[#161616] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center">
                <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">My Recent Work</p>
                <h2 class="text-3xl font-bold lg:text-4xl">
                    See Some Of My <span class="text-[#f0a500]">Projects</span>
                </h2>
            </div>

            <!-- Loading skeleton -->
            <div v-if="loading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 3" :key="i" class="aspect-video animate-pulse rounded-lg border border-white/5 bg-[#1a1a1a]"></div>
            </div>

            <!-- DB data -->
            <div v-else-if="portfolioItems.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="item in portfolioItems"
                    :key="item.id"
                    class="group relative aspect-video overflow-hidden rounded-lg border border-white/5 bg-[#1a1a1a]"
                >
                    <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div v-else class="flex h-full w-full items-center justify-center text-gray-600">
                        <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-black/70 opacity-0 transition-opacity group-hover:opacity-100">
                        <span class="text-xs font-semibold tracking-widest text-[#f0a500] uppercase">{{ item.category }}</span>
                        <span class="text-lg font-bold text-white">{{ item.title }}</span>
                        <a v-if="item.url" :href="item.url" target="_blank" class="mt-2 rounded border border-white px-4 py-1.5 text-xs text-white transition-colors hover:bg-white hover:text-black">View Project</a>
                    </div>
                </div>
            </div>

            <!-- Static fallback -->
            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="i in 3" :key="i" class="flex aspect-video items-center justify-center rounded-lg border border-white/5 bg-[#1a1a1a] text-gray-600">
                    <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            </div>

            <div class="mt-10 text-center">
                <button class="rounded border border-[#f0a500] px-8 py-3 text-sm font-semibold text-[#f0a500] transition-colors hover:bg-[#f0a500] hover:text-black">
                    Discover More
                </button>
            </div>
        </div>
    </section>
</template>
