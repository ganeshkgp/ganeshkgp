<script setup>
import { ref, computed } from 'vue';
import { useSetting } from '@/composables/useSettings.js';

const props = defineProps({
    settings: Object,
});

const emit = defineEmits(['scroll-to']);

const s = useSetting(props);

const mapOpen = ref(false);
const mapSrc = computed(() =>
    `https://maps.google.com/maps?q=${encodeURIComponent(s('contact_address'))}&output=embed&z=14`
);
</script>

<template>
    <footer class="bg-[#111111] py-12">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex flex-col items-start justify-between gap-8 lg:flex-row">

                <!-- Brand + Social -->
                <div>
                    <a href="#" class="text-2xl font-bold text-[#f0a500]">{{ s('site_name') }}</a>
                    <p class="mt-2 max-w-xs text-sm leading-relaxed text-gray-500">{{ s('footer_description') }}</p>
                    <div class="mt-4 flex gap-3">
                        <a v-if="s('social_facebook')" :href="s('social_facebook')" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/5 text-gray-400 transition-colors hover:bg-[#f0a500] hover:text-black" aria-label="Facebook">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                        </a>
                        <a v-if="s('social_twitter')" :href="s('social_twitter')" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/5 text-gray-400 transition-colors hover:bg-[#f0a500] hover:text-black" aria-label="Twitter">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a v-if="s('social_instagram')" :href="s('social_instagram')" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/5 text-gray-400 transition-colors hover:bg-[#f0a500] hover:text-black" aria-label="Instagram">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg>
                        </a>
                        <a v-if="s('social_linkedin')" :href="s('social_linkedin')" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/5 text-gray-400 transition-colors hover:bg-[#f0a500] hover:text-black" aria-label="LinkedIn">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a v-if="s('social_github')" :href="s('social_github')" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/5 text-gray-400 transition-colors hover:bg-[#f0a500] hover:text-black" aria-label="GitHub">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div class="grid grid-cols-2 gap-8 text-sm sm:grid-cols-3">
                    <div>
                        <h4 class="mb-3 font-semibold text-white">Navigation</h4>
                        <ul class="space-y-2 text-gray-500">
                            <li><button @click="emit('scroll-to', 'hero')" class="transition-colors hover:text-[#f0a500]">Home</button></li>
                            <li><button @click="emit('scroll-to', 'about')" class="transition-colors hover:text-[#f0a500]">About</button></li>
                            <li><button @click="emit('scroll-to', 'services')" class="transition-colors hover:text-[#f0a500]">Services</button></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-3 font-semibold text-white">Work</h4>
                        <ul class="space-y-2 text-gray-500">
                            <li><button @click="emit('scroll-to', 'portfolio')" class="transition-colors hover:text-[#f0a500]">Portfolio</button></li>
                            <li><button @click="emit('scroll-to', 'pricing')" class="transition-colors hover:text-[#f0a500]">Pricing</button></li>
                            <li><button @click="emit('scroll-to', 'blog')" class="transition-colors hover:text-[#f0a500]">Blog</button></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-3 font-semibold text-white">Contact</h4>
                        <ul class="space-y-2 text-gray-500">
                            <li v-if="s('contact_email')">
                                <a :href="`mailto:${s('contact_email')}`" class="transition-colors hover:text-[#f0a500]">
                                    {{ s('contact_email') }}
                                </a>
                            </li>
                            <li v-if="s('contact_phone')">
                                <a :href="`tel:${s('contact_phone')}`" class="transition-colors hover:text-[#f0a500]">
                                    {{ s('contact_phone') }}
                                </a>
                            </li>
                            <li v-if="s('contact_address')">
                                <button @click="mapOpen = true" class="text-left transition-colors hover:text-[#f0a500]">
                                    {{ s('contact_address') }}
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="mt-10 border-t border-white/5 pt-6 text-center text-xs text-gray-600">
                &copy; {{ new Date().getFullYear() }} {{ s('site_name') }}. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Map popup -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="mapOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="mapOpen = false">
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-xl border border-white/10 shadow-2xl">
                    <div class="flex items-center justify-between bg-[#111] px-4 py-3">
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="h-4 w-4 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ s('contact_address') }}
                        </div>
                        <button @click="mapOpen = false" class="text-gray-500 transition-colors hover:text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <iframe
                        :src="mapSrc"
                        class="h-80 w-full border-0"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
