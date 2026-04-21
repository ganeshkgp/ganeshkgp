<script setup>
import { ref } from 'vue';
import { useSetting } from '@/composables/useSettings.js';

const props = defineProps({
    settings: Object,
    isScrolled: Boolean,
});

const emit = defineEmits(['scroll-to']);

const s = useSetting(props);
const isMenuOpen = ref(false);

function nav(id) {
    isMenuOpen.value = false;
    emit('scroll-to', id);
}
</script>

<template>
    <nav
        :class="[
            'fixed top-0 right-0 left-0 z-50 transition-all duration-300',
            isScrolled ? 'bg-[#111111]/95 shadow-lg backdrop-blur' : 'bg-transparent',
        ]"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="#" class="text-2xl font-bold text-[#f0a500]">{{ s('site_name') }}</a>

            <ul class="hidden items-center gap-8 text-sm text-gray-300 md:flex">
                <li><button @click="nav('hero')" class="transition-colors hover:text-[#f0a500]">Home</button></li>
                <li><button @click="nav('about')" class="transition-colors hover:text-[#f0a500]">About Me</button></li>
                <li><button @click="nav('services')" class="transition-colors hover:text-[#f0a500]">Services</button></li>
                <li><button @click="nav('portfolio')" class="transition-colors hover:text-[#f0a500]">Portfolio</button></li>
                <li><button @click="nav('blog')" class="transition-colors hover:text-[#f0a500]">Blog</button></li>
            </ul>

            <button @click="nav('contact')" class="hidden rounded bg-[#f0a500] px-5 py-2 text-sm font-semibold text-black transition-colors hover:bg-[#d4920a] md:block">
                Hire Me
            </button>

            <button class="text-white md:hidden" @click="isMenuOpen = !isMenuOpen">
                <svg v-if="!isMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div v-if="isMenuOpen" class="flex flex-col gap-4 bg-[#1a1a1a] px-6 py-4 text-sm text-gray-300 md:hidden">
            <button @click="nav('hero')" class="text-left hover:text-[#f0a500]">Home</button>
            <button @click="nav('about')" class="text-left hover:text-[#f0a500]">About Me</button>
            <button @click="nav('services')" class="text-left hover:text-[#f0a500]">Services</button>
            <button @click="nav('portfolio')" class="text-left hover:text-[#f0a500]">Portfolio</button>
            <button @click="nav('blog')" class="text-left hover:text-[#f0a500]">Blog</button>
            <button @click="nav('contact')" class="text-left font-semibold text-[#f0a500]">Hire Me</button>
        </div>
    </nav>
</template>
