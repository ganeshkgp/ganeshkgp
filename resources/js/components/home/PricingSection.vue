<script setup>
defineProps({
    pricingPlans: { type: Array, default: () => [] },
});

const emit = defineEmits(['scroll-to']);

const staticPricing = [
    {
        id: 1,
        name: 'Starter',
        price: '39999',
        period: 'project',
        is_featured: false,
        features: [
            'Single web app (Laravel or MEVN)',
            'Up to 10 pages / screens',
            'REST API integration',
            'MySQL or MongoDB database',
            '30 days post-delivery support',
            'Source code handover',
        ],
    },
    {
        id: 2,
        name: 'Professional',
        price: '89999',
        period: 'project',
        is_featured: true,
        features: [
            'Full-stack web + Flutter mobile app',
            'Laravel or MEVN backend',
            'Android & iOS (single codebase)',
            'Admin panel included',
            'CI/CD & cloud deployment',
            '60 days priority support',
        ],
    },
    {
        id: 3,
        name: 'Enterprise',
        price: '199999',
        period: 'project',
        is_featured: false,
        features: [
            'Custom solution — any tech stack',
            'Laravel · MEVN · Flutter · Unity · Python',
            'Scalable microservices architecture',
            'Dedicated communication channel',
            '6 months maintenance included',
            'NDA available on request',
        ],
    },
];
</script>

<template>
    <section id="pricing" class="py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center">
                <p class="mb-2 text-xs font-semibold tracking-widest text-[#f0a500] uppercase">Pricing Table</p>
                <h2 class="text-3xl font-bold lg:text-4xl">
                    Pricing List For <span class="text-[#f0a500]">My Services</span>
                </h2>
            </div>

            <div class="mx-auto grid max-w-4xl grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="plan in pricingPlans.length ? pricingPlans : staticPricing"
                    :key="plan.id"
                    :class="[
                        'rounded-lg border p-8 text-center transition-all',
                        plan.is_featured
                            ? 'border-[#f0a500] bg-[#f0a500] text-black'
                            : 'border-white/10 bg-[#1a1a1a] hover:border-[#f0a500]/40',
                    ]"
                >
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full" :class="plan.is_featured ? 'bg-black/20' : 'bg-[#f0a500]/10'">
                        <svg class="h-6 w-6" :class="plan.is_featured ? 'text-black' : 'text-[#f0a500]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m-6 8a2 2 0 1 0 0-4m0 4a2 2 0 1 1 0-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 1 0 0-4m0 4a2 2 0 1 1 0-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="mb-1 text-lg font-bold">{{ plan.name }}</h3>
                    <div class="mb-1 text-4xl font-extrabold">₹{{ Number(plan.price).toLocaleString('en-IN') }}</div>
                    <p class="mb-6 text-xs" :class="plan.is_featured ? 'text-black/70' : 'text-gray-500'">Per {{ plan.period }}</p>
                    <ul v-if="plan.features?.length" class="mb-8 space-y-2 text-left text-sm">
                        <li v-for="(feature, i) in plan.features" :key="i" class="flex items-center gap-2">
                            <svg class="h-4 w-4 flex-shrink-0" :class="plan.is_featured ? 'text-black' : 'text-[#f0a500]'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                            {{ feature }}
                        </li>
                    </ul>
                    <button
                        @click="emit('scroll-to', 'contact')"
                        :class="['w-full rounded py-3 text-sm font-semibold transition-colors', plan.is_featured ? 'bg-black text-white hover:bg-gray-900' : 'border border-[#f0a500] text-[#f0a500] hover:bg-[#f0a500] hover:text-black']"
                    >
                        Hire Me
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
