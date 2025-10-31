<template>
  <AppLayout>
    <!-- Space Portfolio -->
    <section class="hero-section">
      <SpacePortfolio />
    </section>




  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import SpacePortfolio from '../components/SpacePortfolio.vue'


onMounted(() => {
  // Add smooth scroll behavior
  document.documentElement.style.scrollBehavior = 'smooth'
})
</script>

<style scoped>


/* Footer */
.footer {
  padding: 2rem;
  text-align: center;
 background:transparent;
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.9rem;
  position: relative;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .nav-content {
    padding: 0 1rem;
  }

  .nav-links {
    gap: 1rem;
    font-size: 0.8rem;
  }

  .logo-text {
    font-size: 1.2rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .projects-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .project-card {
    padding: 1.5rem;
  }

  .section-number {
    font-size: 3rem;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .about-text,
  .contact-text {
    font-size: 1rem;
  }

  .contact-links {
    flex-direction: column;
    gap: 1rem;
  }
}
</style>
