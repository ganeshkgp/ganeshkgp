<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    src: { type: String, default: null },
    class: { type: String, default: 'w-8 h-8' },
});

// Module-level cache shared across all component instances
const cache = {};

const svgContent = ref('');

async function load(src) {
    if (!src) {
        svgContent.value = '';
        return;
    }

    if (cache[src] !== undefined) {
        svgContent.value = cache[src];
        return;
    }

    try {
        const response = await fetch(src);
        if (!response.ok) {
            throw new Error(`Failed to fetch SVG: ${response.status}`);
        }
        const text = await response.text();
        cache[src] = text;
        svgContent.value = text;
    } catch {
        cache[src] = '';
        svgContent.value = '';
    }
}

onMounted(() => load(props.src));
watch(() => props.src, load);
</script>

<template>
    <!--
        Wraps the fetched SVG in a span. CSS forces the inner <svg> to
        fill the wrapper so sizing is controlled via the `class` prop.
    -->
    <span
        :class="$props.class"
        class="inline-flex items-center justify-center [&>svg]:w-full [&>svg]:h-full [&>svg]:block"
        v-html="svgContent"
    />
</template>
