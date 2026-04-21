<script setup>
import { useSetting } from '@/composables/useSettings.js';

const props = defineProps({
    settings: Object,
});

const emit = defineEmits(['scroll-to']);

const s = useSetting(props);
</script>

<template>
    <section id="hero" class="mx-auto flex min-h-screen max-w-7xl items-center px-6 pt-24 pb-16">
        <div class="flex w-full flex-col-reverse items-center gap-12 lg:flex-row">
            <div class="flex-1">
                <p class="mb-2 text-sm font-semibold tracking-widest text-[#f0a500] uppercase">
                    {{ s('hero_greeting') }}
                </p>
                <h1 class="mb-4 text-5xl leading-tight font-extrabold lg:text-6xl">
                    {{ s('hero_name') }},<br />
                    <span class="text-3xl font-semibold text-gray-300 lg:text-4xl">{{ s('hero_tagline') }}</span>
                </h1>
                <p class="mt-4 mb-8 max-w-lg leading-relaxed text-gray-400">
                    {{ s('hero_bio') }}
                </p>
                <button
                    @click="emit('scroll-to', 'about')"
                    class="inline-flex items-center gap-2 rounded border border-[#f0a500] px-6 py-3 font-semibold text-[#f0a500] transition-colors hover:bg-[#f0a500] hover:text-black"
                >
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                    Play Short Intro
                </button>
            </div>

            <div class="flex flex-1 justify-center">
                <div class="relative h-72 w-72 lg:h-96 lg:w-96">
                    <div class="absolute inset-0 rounded-full bg-[#f0a500]/10 blur-3xl"></div>
                    <div class="relative flex h-full w-full items-end justify-center overflow-hidden rounded-full border border-[#f0a500]/20 bg-[#1a1a1a]">
                        <img
                            v-if="s('hero_image')"
                            :src="`/storage/${s('hero_image')}`"
                            alt="Hero"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="pb-8 text-center text-sm text-gray-500">
                            <svg class="mx-auto mb-2 h-16 w-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Hero Image
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
