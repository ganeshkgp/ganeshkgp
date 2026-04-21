<script setup>
import { useSetting } from '@/composables/useSettings.js';

const props = defineProps({
    settings: Object,
});

const s = useSetting(props);
</script>

<template>
    <section id="about" class="bg-[#161616] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex flex-col items-start gap-16 lg:flex-row">

                <!-- Photo -->
                <div class="flex flex-1 justify-center">
                    <div class="flex h-80 w-64 items-center justify-center overflow-hidden rounded-lg border border-white/10 bg-[#1e1e1e]">
                        <img
                            v-if="s('about_photo')"
                            :src="`/storage/${s('about_photo')}`"
                            alt="About"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="text-center text-sm text-gray-500">
                            <svg class="mx-auto mb-2 h-16 w-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            About Photo
                        </div>
                    </div>
                </div>

                <!-- Bio + Skills -->
                <div class="flex-1">
                    <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">About Me</p>
                    <h2 class="mb-4 text-3xl font-bold lg:text-4xl">{{ s('about_title') }}</h2>
                    <p class="mb-6 leading-relaxed text-gray-400">{{ s('about_bio') }}</p>

                    <a
                        v-if="s('about_cv_url')"
                        :href="s('about_cv_url')"
                        target="_blank"
                        class="mb-10 inline-block rounded border border-[#f0a500] px-6 py-2 text-sm font-semibold text-[#f0a500] transition-colors hover:bg-[#f0a500] hover:text-black"
                    >
                        Download CV
                    </a>

                    <div class="grid grid-cols-1 gap-x-12 gap-y-5 sm:grid-cols-2">
                        <div v-for="skill in s('skills')" :key="skill.name">
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="text-gray-300">{{ skill.name }}</span>
                                <span class="text-[#f0a500]">{{ skill.level }}%</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-white/10">
                                <div
                                    class="h-full rounded-full bg-[#f0a500] transition-all duration-1000"
                                    :style="{ width: skill.level + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
