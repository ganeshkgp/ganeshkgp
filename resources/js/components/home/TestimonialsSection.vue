<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    testimonials: { type: Array, default: () => [] },
});

const staticTestimonials = [
    {
        name: 'Priya Nair',
        role: 'Founder, KeralaKraft — Kochi',
        content: 'Ganesh delivered our entire D2C brand identity and website in under 3 weeks. The UI was exactly what we envisioned — modern, clean, and perfect for our Indian audience. Highly recommend!',
        rating: 5,
    },
    {
        name: 'Arjun Mehta',
        role: 'CTO, FinTrack Solutions — Pune',
        content: 'We needed a Laravel + Vue SPA built fast without cutting corners. Ganesh nailed it — clean architecture, solid API design, and delivered ahead of schedule. Will definitely work with him again.',
        rating: 5,
    },
    {
        name: 'Sneha Reddy',
        role: 'Product Manager, EduBridge — Hyderabad',
        content: 'Ganesh built our Flutter app from scratch and integrated it with our existing backend seamlessly. The attention to detail and communication throughout the project was outstanding.',
        rating: 5,
    },
];

const activeIndex = ref(0);

const list = computed(() => props.testimonials.length ? props.testimonials : staticTestimonials);

function prev() {
    activeIndex.value = (activeIndex.value - 1 + list.value.length) % list.value.length;
}

function next() {
    activeIndex.value = (activeIndex.value + 1) % list.value.length;
}
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

            <div class="mx-auto max-w-3xl">
                <div class="rounded-xl border border-white/5 bg-[#1a1a1a] p-8 text-center">

                    <!-- Stars -->
                    <div class="mb-4 flex justify-center gap-1">
                        <svg
                            v-for="i in 5"
                            :key="i"
                            class="h-4 w-4"
                            :class="i <= (list[activeIndex].rating ?? 5) ? 'text-[#f0a500]' : 'text-white/20'"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>

                    <p class="mb-4 font-serif text-5xl leading-none text-[#f0a500]">"</p>

                    <p class="mb-8 text-lg leading-relaxed text-gray-300">
                        {{ list[activeIndex].content }}
                    </p>

                    <div class="flex items-center justify-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-[#2a2a2a]">
                            <img
                                v-if="list[activeIndex].avatar"
                                :src="`/storage/${list[activeIndex].avatar}`"
                                :alt="list[activeIndex].name"
                                class="h-full w-full object-cover"
                            />
                            <svg v-else class="h-7 w-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-[#f0a500]">{{ list[activeIndex].name }}</p>
                            <p class="text-sm text-gray-500">{{ list[activeIndex].role }}</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-8 flex items-center justify-center gap-6">
                        <button
                            @click="prev"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/10 text-gray-400 transition-colors hover:border-[#f0a500] hover:text-[#f0a500]"
                            aria-label="Previous testimonial"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>

                        <div class="flex gap-2">
                            <button
                                v-for="(_, i) in list"
                                :key="i"
                                @click="activeIndex = i"
                                :class="['h-2 rounded-full transition-all', activeIndex === i ? 'w-6 bg-[#f0a500]' : 'w-2.5 bg-white/20 hover:bg-white/40']"
                                :aria-label="`Go to testimonial ${i + 1}`"
                            ></button>
                        </div>

                        <button
                            @click="next"
                            class="flex h-9 w-9 items-center justify-center rounded-full border border-white/10 text-gray-400 transition-colors hover:border-[#f0a500] hover:text-[#f0a500]"
                            aria-label="Next testimonial"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>
</template>
