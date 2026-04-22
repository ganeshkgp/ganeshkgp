<script setup>
import InlineSvg from '@/components/InlineSvg.vue';

defineProps({
    loading: Boolean,
    services: { type: Array, default: () => [] },
});

const staticServices = [
    {
        title: 'Laravel & PHP Development',
        description: 'Scalable web apps, REST APIs, SaaS platforms, and custom admin panels built with Laravel — clean architecture, fast delivery.',
        icon_url: '/storage/icons/services/code-bracket.svg',
    },
    {
        title: 'MEVN Stack Development',
        description: 'Full-stack JavaScript apps with MongoDB, Express, Vue.js, and Node.js — real-time dashboards, APIs, and SPAs.',
        icon_url: '/storage/icons/services/cube.svg',
    },
    {
        title: 'Flutter Mobile Apps',
        description: 'Beautiful cross-platform Android & iOS apps with a single codebase — smooth animations, native performance, fast launches.',
        icon_url: '/storage/icons/services/device-phone-mobile.svg',
    },
    {
        title: 'C# & Unity Game Development',
        description: '2D and 3D games, AR/VR experiences, and interactive simulations built with C# and Unity for mobile, PC, and web.',
        icon_url: '/storage/icons/services/puzzle-piece.svg',
    },
    {
        title: 'Python Development',
        description: 'Automation scripts, data pipelines, ML model integration, web scraping, and backend APIs with Django or FastAPI.',
        icon_url: '/storage/icons/services/command-line.svg',
    },
    {
        title: 'REST API & System Design',
        description: 'Well-documented RESTful APIs, microservices architecture, database optimisation, and cloud deployment on AWS or DigitalOcean.',
        icon_url: '/storage/icons/services/circle-stack.svg',
    },
    {
        title: 'UI/UX Design & Prototyping',
        description: 'Figma wireframes, interactive prototypes, and polished UI — designed for real users, not just to look pretty.',
        icon_url: '/storage/icons/services/computer-desktop.svg',
    },
    {
        title: 'Maintenance & Code Review',
        description: 'Ongoing support, performance audits, bug fixing, refactoring, and code reviews for existing web and mobile projects.',
        icon_url: '/storage/icons/services/wrench-screwdriver.svg',
    },
];
</script>

<template>
    <section id="services" class="py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex flex-col items-start gap-8 lg:flex-row">
                <div class="lg:w-1/2">
                    <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">What I Promise</p>
                    <h2 class="text-3xl font-bold lg:text-4xl">
                        I Offer Wide Range Of Top<br />Notch <span class="text-[#f0a500]">Services</span>
                    </h2>
                </div>
                <div class="lg:w-1/2">
                    <p class="leading-relaxed text-gray-400">
                        From UI/UX design to full-stack development and digital branding — I deliver end-to-end solutions tailored for the Indian market and beyond.
                    </p>
                </div>
            </div>

            <!-- Loading skeleton -->
            <div v-if="loading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="i in 8" :key="i" class="h-40 animate-pulse rounded-lg border border-white/5 bg-[#1a1a1a]"></div>
            </div>

            <!-- DB data -->
            <div v-else-if="services.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="service in services"
                    :key="service.id"
                    class="group rounded-lg border border-white/5 bg-[#1a1a1a] p-6 transition-all hover:border-[#f0a500]/40 hover:bg-[#1e1e1e]"
                >
                    <div class="mb-4 text-[#f0a500]"><InlineSvg :src="service.icon_url" /></div>
                    <h3 class="mb-2 text-sm font-semibold transition-colors group-hover:text-[#f0a500]">{{ service.title }}</h3>
                    <p class="text-xs leading-relaxed text-gray-500">{{ service.description }}</p>
                    <button class="mt-4 text-xs text-[#f0a500] hover:underline">+ Learn More</button>
                </div>
            </div>

            <!-- Static fallback -->
            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="service in staticServices"
                    :key="service.title"
                    class="group rounded-lg border border-white/5 bg-[#1a1a1a] p-6 transition-all hover:border-[#f0a500]/40 hover:bg-[#1e1e1e]"
                >
                    <div class="mb-4 text-[#f0a500]"><InlineSvg :src="service.icon_url" /></div>
                    <h3 class="mb-2 text-sm font-semibold transition-colors group-hover:text-[#f0a500]">{{ service.title }}</h3>
                    <p class="text-xs leading-relaxed text-gray-500">{{ service.description }}</p>
                    <button class="mt-4 text-xs text-[#f0a500] hover:underline">+ Learn More</button>
                </div>
            </div>
        </div>
    </section>
</template>
