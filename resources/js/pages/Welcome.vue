<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import api from '@/api/index.js';
import { defaultSettings } from '@/composables/useSettings.js';

import TheNavbar from '@/components/home/TheNavbar.vue';
import HeroSection from '@/components/home/HeroSection.vue';
import AboutSection from '@/components/home/AboutSection.vue';
import ServicesSection from '@/components/home/ServicesSection.vue';
import PortfolioSection from '@/components/home/PortfolioSection.vue';
import PricingSection from '@/components/home/PricingSection.vue';
import TestimonialsSection from '@/components/home/TestimonialsSection.vue';
import ContactSection from '@/components/home/ContactSection.vue';
import BlogSection from '@/components/home/BlogSection.vue';
import BrandsSection from '@/components/home/BrandsSection.vue';
import CtaSection from '@/components/home/CtaSection.vue';
import TheFooter from '@/components/home/TheFooter.vue';

// ── State ──────────────────────────────────────────────
const settings = ref(null);
const services = ref([]);
const portfolioItems = ref([]);
const pricingPlans = ref([]);
const testimonials = ref([]);
const blogPosts = ref([]);
const loading = ref(true);
const isScrolled = ref(false);

// ── Data fetching ──────────────────────────────────────
onMounted(async () => {
    try {
        const { data } = await api.get('/home');
        settings.value = data.settings ?? null;
        services.value = data.services ?? [];
        portfolioItems.value = data.portfolioItems ?? [];
        pricingPlans.value = data.pricingPlans ?? [];
        testimonials.value = data.testimonials ?? [];
        blogPosts.value = data.blogPosts ?? [];
    } catch {
        // Falls through to static placeholders in each component
    } finally {
        loading.value = false;
    }

    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => window.removeEventListener('scroll', handleScroll));

// ── Scroll ─────────────────────────────────────────────
function handleScroll() {
    isScrolled.value = window.scrollY > 50;
}

function scrollToSection(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
}

// ── Computed helpers ───────────────────────────────────
function get(key) {
    return settings.value?.[key] ?? defaultSettings[key];
}
</script>

<template>
    <div class="bg-[#111111] font-sans text-white antialiased">

        <TheNavbar
            :settings="settings"
            :is-scrolled="isScrolled"
            @scroll-to="scrollToSection"
        />

        <HeroSection
            :settings="settings"
            @scroll-to="scrollToSection"
        />

        <AboutSection :settings="settings" />

        <ServicesSection
            :loading="loading"
            :services="services"
        />

        <PortfolioSection
            :loading="loading"
            :portfolio-items="portfolioItems"
        />

        <PricingSection
            :pricing-plans="pricingPlans"
            @scroll-to="scrollToSection"
        />

        <TestimonialsSection :testimonials="testimonials" />

        <ContactSection :settings="settings" />

        <BlogSection :blog-posts="blogPosts" />

        <BrandsSection :brands="get('brands')" />

        <CtaSection @scroll-to="scrollToSection" />

        <TheFooter
            :settings="settings"
            @scroll-to="scrollToSection"
        />

    </div>
</template>
