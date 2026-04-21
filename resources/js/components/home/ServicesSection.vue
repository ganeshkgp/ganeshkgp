<script setup>
defineProps({
    loading: Boolean,
    services: { type: Array, default: () => [] },
});

const staticServices = [
    {
        title: 'Laravel & PHP Development',
        description: 'Scalable web apps, REST APIs, SaaS platforms, and custom admin panels built with Laravel — clean architecture, fast delivery.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>`,
    },
    {
        title: 'MEVN Stack Development',
        description: 'Full-stack JavaScript apps with MongoDB, Express, Vue.js, and Node.js — real-time dashboards, APIs, and SPAs.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>`,
    },
    {
        title: 'Flutter Mobile Apps',
        description: 'Beautiful cross-platform Android & iOS apps with a single codebase — smooth animations, native performance, fast launches.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3" /></svg>`,
    },
    {
        title: 'C# & Unity Game Development',
        description: '2D and 3D games, AR/VR experiences, and interactive simulations built with C# and Unity for mobile, PC, and web.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" /></svg>`,
    },
    {
        title: 'Python Development',
        description: 'Automation scripts, data pipelines, ML model integration, web scraping, and backend APIs with Django or FastAPI.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>`,
    },
    {
        title: 'REST API & System Design',
        description: 'Well-documented RESTful APIs, microservices architecture, database optimisation, and cloud deployment on AWS or DigitalOcean.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 6 0m-6 0H3m16.5 0a3 3 0 0 0 3-3m-3 3a3 3 0 1 1-6 0m6 0h1.5m-7.5 0V5.625m0 0a3 3 0 0 1 3-3m-3 3a3 3 0 1 1-6 0m6 0H9m4.5 0H21m-9 0V3" /></svg>`,
    },
    {
        title: 'UI/UX Design & Prototyping',
        description: 'Figma wireframes, interactive prototypes, and polished UI — designed for real users, not just to look pretty.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" /></svg>`,
    },
    {
        title: 'Maintenance & Code Review',
        description: 'Ongoing support, performance audits, bug fixing, refactoring, and code reviews for existing web and mobile projects.',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" /></svg>`,
    },
];

function iconFor(title) {
    return staticServices.find((s) => s.title === title)?.icon ?? staticServices[0].icon;
}
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
                    <div class="mb-4 text-[#f0a500]" v-html="iconFor(service.title)"></div>
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
                    <div class="mb-4 text-[#f0a500]" v-html="service.icon"></div>
                    <h3 class="mb-2 text-sm font-semibold transition-colors group-hover:text-[#f0a500]">{{ service.title }}</h3>
                    <p class="text-xs leading-relaxed text-gray-500">{{ service.description }}</p>
                    <button class="mt-4 text-xs text-[#f0a500] hover:underline">+ Learn More</button>
                </div>
            </div>
        </div>
    </section>
</template>
