<script setup>
defineProps({
    brands: { type: Array, default: () => [] },
});
</script>

<template>
    <section class="border-t border-b border-white/5 py-12">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex flex-wrap items-center justify-center gap-10 opacity-40">
                <span
                    v-for="brand in brands"
                    :key="brand"
                    class="text-xl font-bold tracking-widest text-gray-300"
                >{{ brand }}</span>
            </div>
        </div>
    </section>
</template>
