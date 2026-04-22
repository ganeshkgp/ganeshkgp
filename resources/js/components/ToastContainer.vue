<script setup>
import { useToast } from '@/composables/useToast.js';

const { toasts, dismiss } = useToast();
</script>

<template>
    <Teleport to="body">
        <div class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3">
            <Transition
                v-for="toast in toasts"
                :key="toast.id"
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="translate-y-4 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="translate-y-4 opacity-0"
                appear
            >
                <div
                    :class="[
                        'flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium shadow-lg',
                        toast.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white',
                    ]"
                >
                    <!-- Icon -->
                    <svg v-if="toast.type === 'success'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <svg v-else class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>

                    <span>{{ toast.message }}</span>

                    <button @click="dismiss(toast.id)" class="ml-2 opacity-70 hover:opacity-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </Transition>
        </div>
    </Teleport>
</template>
