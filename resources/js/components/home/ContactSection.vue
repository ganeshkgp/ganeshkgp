<script setup>
import { reactive, ref, computed } from 'vue';
import axios from 'axios';
import { useSetting } from '@/composables/useSettings.js';
import { useToast } from '@/composables/useToast.js';

const props = defineProps({
    settings: Object,
});

const s = useSetting(props);
const { show: showToast } = useToast();

const mapOpen = ref(false);
const mapSrc = computed(() =>
    `https://maps.google.com/maps?q=${encodeURIComponent(s('contact_address'))}&output=embed&z=14`
);

const form = reactive({
    name: '',
    email: '',
    phone: '',
    message: '',
});

const loading = ref(false);
const errors = reactive({});

async function submit() {
    loading.value = true;
    Object.keys(errors).forEach((k) => delete errors[k]);

    try {
        await axios.post('/api/contact', form);
        form.name = '';
        form.email = '';
        form.phone = '';
        form.message = '';
        showToast('Message sent! I\'ll get back to you soon.', 'success');
    } catch (err) {
        if (err.response?.status === 422) {
            Object.assign(errors, err.response.data.errors ?? {});
        } else {
            showToast('Something went wrong. Please try again.', 'error');
        }
    } finally {
        loading.value = false;
    }
}
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
                        <a v-if="s('contact_email')" :href="`mailto:${s('contact_email')}`" class="flex items-center gap-3 transition-colors hover:text-[#f0a500]">
                            <svg class="h-5 w-5 flex-shrink-0 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            {{ s('contact_email') }}
                        </a>
                        <a v-if="s('contact_phone')" :href="`tel:${s('contact_phone')}`" class="flex items-center gap-3 transition-colors hover:text-[#f0a500]">
                            <svg class="h-5 w-5 flex-shrink-0 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            {{ s('contact_phone') }}
                        </a>
                        <button v-if="s('contact_address')" @click="mapOpen = true" class="flex items-center gap-3 text-left transition-colors hover:text-[#f0a500]">
                            <svg class="h-5 w-5 flex-shrink-0 text-[#f0a500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ s('contact_address') }}
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <div class="flex-1">
                    <form class="flex flex-col gap-4" @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter Name"
                                    :class="['w-full rounded border bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:outline-none', errors.name ? 'border-red-500' : 'border-white/10 focus:border-[#f0a500]']"
                                />
                                <p v-if="errors.name" class="mt-1 text-xs text-red-400">{{ errors.name[0] }}</p>
                            </div>
                            <div>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter Email"
                                    :class="['w-full rounded border bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:outline-none', errors.email ? 'border-red-500' : 'border-white/10 focus:border-[#f0a500]']"
                                />
                                <p v-if="errors.email" class="mt-1 text-xs text-red-400">{{ errors.email[0] }}</p>
                            </div>
                        </div>

                        <div>
                            <input
                                v-model="form.phone"
                                type="text"
                                placeholder="Enter Phone Number (optional)"
                                :class="['w-full rounded border bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:outline-none', errors.phone ? 'border-red-500' : 'border-white/10 focus:border-[#f0a500]']"
                            />
                            <p v-if="errors.phone" class="mt-1 text-xs text-red-400">{{ errors.phone[0] }}</p>
                        </div>

                        <div>
                            <textarea
                                v-model="form.message"
                                placeholder="Enter Message"
                                rows="5"
                                :class="['w-full resize-none rounded border bg-[#1a1a1a] px-4 py-3 text-sm text-white placeholder-gray-600 transition-colors focus:outline-none', errors.message ? 'border-red-500' : 'border-white/10 focus:border-[#f0a500]']"
                            ></textarea>
                            <p v-if="errors.message" class="mt-1 text-xs text-red-400">{{ errors.message[0] }}</p>
                        </div>

                        <button
                            type="submit"
                            :disabled="loading"
                            class="flex items-center justify-center gap-2 self-end rounded bg-[#f0a500] px-8 py-3 text-sm font-semibold text-black transition-colors hover:bg-[#d4920a] disabled:opacity-60"
                        >
                            <svg v-if="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ loading ? 'Sending…' : 'Submit Message' }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

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
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

                <!-- Modal -->
                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-xl border border-white/10 shadow-2xl">
                    <!-- Header -->
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
                    <!-- iframe -->
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
