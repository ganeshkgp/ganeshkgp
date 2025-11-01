<template>
  <AppLayout>
    <!-- Space Portfolio -->
    <section class="hero-section">
      <SpacePortfolio />
    </section>

    <!-- Services Section -->
    <section class="services-section" v-if="services.length > 0">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">🌟 Services</h2>
          <p class="section-subtitle">Explore my expertise and services</p>
        </div>

        <div class="services-grid">
          <div
            v-for="service in services"
            :key="service.id"
            class="service-card"
            :style="{ '--service-color': service.color }"
          >
            <div class="service-icon">
              {{ service.icon }}
            </div>
            <div class="service-content">
              <h3>{{ service.title }}</h3>
              <p>{{ service.description }}</p>
              <div class="service-features" v-if="service.features && service.features.length > 0">
                <div v-for="feature in service.features" :key="feature" class="feature-item">
                  <span class="feature-bullet">✦</span>
                  {{ feature }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import SpacePortfolio from '../components/SpacePortfolio.vue'

// Services data
const services = ref([])

// Fetch services from API
const fetchServices = async () => {
  try {
    const response = await fetch('/api/v1/home/services')
    const servicesData = await response.json()
    services.value = servicesData
  } catch (error) {
    console.error('Error fetching services:', error)
    // Fallback to default services
    services.value = [
      {
        id: 1,
        title: 'Web Development',
        description: 'Creating modern, responsive web applications with cutting-edge technologies',
        icon: '🌐',
        color: '#00ffff',
        features: ['Frontend Development', 'Backend APIs', 'Database Design']
      },
      {
        id: 2,
        title: 'Mobile Apps',
        description: 'Building cross-platform mobile applications with native performance',
        icon: '📱',
        color: '#ff00ff',
        features: ['Flutter Development', 'React Native', 'iOS & Android']
      }
    ]
  }
}

onMounted(async () => {
  // Add smooth scroll behavior
  document.documentElement.style.scrollBehavior = 'smooth'

  // Fetch services
  await fetchServices()
})
</script>

<style scoped>
/* Services Section */
.services-section {
  min-height: 100vh;
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  color: white;
  padding: 5rem 2rem;
  position: relative;
  overflow: hidden;
}

.services-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background:
    radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 0, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 40% 80%, rgba(255, 214, 10, 0.05) 0%, transparent 50%);
  pointer-events: none;
  z-index: 1;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

.section-header {
  text-align: center;
  margin-bottom: 4rem;
}

.section-title {
  font-size: clamp(2.5rem, 5vw, 4rem);
  font-weight: 900;
  margin: 0 0 1rem 0;
  background: linear-gradient(45deg, #00ffff, #ff00ff, #ffd60a);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 50px rgba(0, 255, 255, 0.5);
  animation: titlePulse 4s ease-in-out infinite;
}

@keyframes titlePulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.02); }
}

.section-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 2rem;
  text-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
}

.service-card {
  background: linear-gradient(135deg,
    rgba(10, 10, 30, 0.9) 0%,
    rgba(26, 0, 51, 0.8) 50%,
    rgba(0, 8, 20, 0.9) 100%);
  border: 2px solid var(--service-color, rgba(255, 255, 255, 0.2));
  border-radius: 20px;
  padding: 2.5rem;
  backdrop-filter: blur(20px);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.service-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(
    circle at var(--service-x, 50%) var(--service-y, 50%),
    var(--service-color, rgba(0, 255, 255, 0.1)) 0%,
    transparent 70%
  );
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.service-card:hover {
  transform: translateY(-10px) scale(1.02);
  border-color: var(--service-color);
  box-shadow:
    0 20px 40px rgba(0, 0, 0, 0.3),
    0 0 50px var(--service-color, rgba(0, 255, 255, 0.2));
}

.service-card:hover::before {
  opacity: 1;
}

.service-icon {
  font-size: 3.5rem;
  margin-bottom: 1.5rem;
  text-align: center;
  filter: drop-shadow(0 0 20px var(--service-color, #00ffff));
  animation: iconFloat 3s ease-in-out infinite;
}

@keyframes iconFloat {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.service-content h3 {
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0 0 1rem 0;
  color: var(--service-color, #00ffff);
  text-shadow: 0 0 10px currentColor;
}

.service-content p {
  font-size: 1.1rem;
  line-height: 1.6;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 1.5rem;
}

.service-features {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.8);
}

.feature-bullet {
  color: var(--service-color, #00ffff);
  font-size: 1.2rem;
  text-shadow: 0 0 5px currentColor;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .services-section {
    padding: 3rem 1rem;
  }

  .section-title {
    font-size: 2rem;
  }

  .section-subtitle {
    font-size: 1rem;
  }

  .services-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .service-card {
    padding: 2rem;
  }

  .service-icon {
    font-size: 2.5rem;
  }

  .service-content h3 {
    font-size: 1.5rem;
  }

  .service-content p {
    font-size: 1rem;
  }

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

  .about-text,
  .contact-text {
    font-size: 1rem;
  }

  .contact-links {
    flex-direction: column;
    gap: 1rem;
  }
}

@media (max-width: 480px) {
  .services-section {
    padding: 2rem 1rem;
  }

  .service-card {
    padding: 1.5rem;
  }

  .service-icon {
    font-size: 2rem;
  }

  .service-content h3 {
    font-size: 1.3rem;
  }
}
</style>
