<script setup>
import { useSetting } from '@/composables/useSettings.js';

const props = defineProps({
    settings: Object,
});

const s = useSetting(props);
</script>

<template>
    <section id="contact" class="py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex flex-col gap-16 lg:flex-row">

                <!-- Info -->
                <div class="flex-1">
                    <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">Get In Touch</p>
                    <h2 class="mb-4 text-3xl font-bold lg:text-4xl">
                        Feel Free To Reach &amp;<br /><span class="text-[#f0a500]">Contact!</span>
                    </h2>
                    <p class="mb-8 leading-relaxed text-gray-400">
                        Have a project in mind? Whether you're a startup in Pune, an enterprise in Delhi, or building something new from scratch — let's talk!
                    </p>
                    <div class="flex flex-col gap-4 text-sm text-gray-400">
                        <div v-if="s('contact_email')" class="flex items-center gap-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            {{ s('contact_email') }}
                        </div>
                        <div v-if="s('contact_phone')" class="flex items-center gap-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            {{ s('contact_phone') }}
                        </div>
                        <div v-if="s('contact_address')" class="flex items-center gap-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ s('contact_address') }}
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="flex-1">
                    <form class="flex flex-col gap-4" @submit.prevent>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <input type="text" placeholder="Enter Name" class="rounded border border-white/10 bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:border-[#f0a500] focus:outline-none" />
                            <input type="email" placeholder="Enter Email" class="rounded border border-white/10 bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:border-[#f0a500] focus:outline-none" />
                        </div>
                        <input type="text" placeholder="Enter Phone Number" class="rounded border border-white/10 bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:border-[#f0a500] focus:outline-none" />
                        <textarea placeholder="Enter Message" rows="5" class="resize-none rounded border border-white/10 bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:border-[#f0a500] focus:outline-none"></textarea>
                        <button type="submit" class="self-end rounded bg-[#f0a500] px-8 py-3 text-sm font-semibold text-black transition-colors hover:bg-[#d4920a]">
                            Submit Message
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>
</template>
