<script setup>
import { ref } from 'vue';

defineProps({
    testimonials: { type: Array, default: () => [] },
});

const activeIndex = ref(0);
</script>

<template>
    <section id="testimonials" class="bg-[#161616] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center">
                <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">Client's Views</p>
                <h2 class="text-3xl font-bold lg:text-4xl">
                    What Client Says <span class="text-[#f0a500]">About Me</span>
                </h2>
            </div>

            <!-- DB data -->
            <div v-if="testimonials.length" class="mx-auto max-w-3xl">
                <div class="rounded-xl border border-white/5 bg-[#1a1a1a] p-8 text-center">
                    <p class="mb-4 font-serif text-5xl leading-none text-[#f0a500]">"</p>
                    <p class="mb-6 text-lg leading-relaxed text-gray-300">
                        {{ testimonials[activeIndex % testimonials.length].content }}
                    </p>
                    <div class="flex items-center justify-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-[#2a2a2a]">
                            <img v-if="testimonials[activeIndex % testimonials.length].avatar" :src="`/storage/${testimonials[activeIndex % testimonials.length].avatar}`" :alt="testimonials[activeIndex % testimonials.length].name" class="h-full w-full object-cover" />
                            <svg v-else class="h-7 w-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-[#f0a500]">{{ testimonials[activeIndex % testimonials.length].name }}</p>
                            <p class="text-sm text-gray-500">{{ testimonials[activeIndex % testimonials.length].role }}</p>
                        </div>
                    </div>
                    <div v-if="testimonials.length > 1" class="mt-6 flex justify-center gap-2">
                        <button
                            v-for="(_, i) in testimonials"
                            :key="i"
                            @click="activeIndex = i"
                            :class="['h-2.5 w-2.5 rounded-full transition-colors', activeIndex % testimonials.length === i ? 'bg-[#f0a500]' : 'bg-white/20']"
                        ></button>
                    </div>
                </div>
            </div>

            <!-- Static fallback -->
            <div v-else class="mx-auto max-w-3xl rounded-xl border border-white/5 bg-[#1a1a1a] p-8 text-center">
                <p class="mb-4 font-serif text-5xl leading-none text-[#f0a500]">"</p>
                <p class="mb-6 text-lg leading-relaxed text-gray-300">
                    Ganesh delivered our entire D2C brand identity and website in under 3 weeks. The UI was exactly what we envisioned — modern, clean, and perfect for our Indian audience. Highly recommend!
                </p>
                <div class="flex items-center justify-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#2a2a2a]">
                        <svg class="h-7 w-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-[#f0a500]">Priya Nair</p>
                        <p class="text-sm text-gray-500">Founder, KeralaKraft — Kochi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
