<script setup>
const emit = defineEmits(['scroll-to']);
</script>

<template>
    <section class="bg-[#161616] py-16">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-6 lg:flex-row">
            <div>
                <h2 class="text-2xl font-bold lg:text-3xl">Let's Make Something Together</h2>
                <p class="mt-1 text-gray-400">Start a <span class="text-[#f0a500]">Conversation</span></p>
            </div>
            <button
                @click="emit('scroll-to', 'contact')"
                class="whitespace-nowrap rounded bg-[#f0a500] px-8 py-3 font-semibold text-black transition-colors hover:bg-[#d4920a]"
            >
                Hire Me Now
            </button>
        </div>
    </section>
</template>
