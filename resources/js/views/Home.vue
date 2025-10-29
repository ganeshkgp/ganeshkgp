<template>
  <div class="home-container">
    <!-- Navigation -->
    <nav class="navbar">
      <div class="nav-content">
        <div class="logo">
          <span class="logo-text">GK</span>
        </div>
        <div class="nav-links">
          <router-link to="/" class="nav-link active">Work</router-link>
          <router-link to="/projects" class="nav-link">Projects</router-link>
          <router-link to="/contact" class="nav-link">Contact</router-link>
          <router-link to="/admin" class="nav-link">Admin</router-link>
        </div>
      </div>
    </nav>

    <!-- 3D Sci-Fi Building -->
    <section class="hero-section">
      <SciFiBuilding />

      <!-- Minimal text overlay -->
      <div class="hero-text">
        <h1 class="hero-title">
          Ganesh K P
        </h1>
        <p class="hero-subtitle">
          Full-Stack Developer & 3D Specialist
        </p>
      </div>

      <!-- Scroll indicator -->
      <div class="scroll-indicator">
        <div class="scroll-text">Scroll</div>
        <div class="scroll-arrow"></div>
      </div>
    </section>

    <!-- Projects Sections -->
    <section class="projects-section">
      <div class="projects-grid">
        <div
          v-for="project in projects"
          :key="project.id"
          class="project-card"
          :class="{ active: activeProject === project.id }"
          @mouseenter="activeProject = project.id"
          @mouseleave="activeProject = null"
        >
          <div class="project-number">{{ String(project.id).padStart(2, '0') }}</div>
          <div class="project-content">
            <h3>{{ project.title }}</h3>
            <p class="project-description">{{ project.description }}</p>
            <div class="project-tech">
              <span v-for="tech in project.technologies" :key="tech" class="tech-tag">{{ tech }}</span>
            </div>
            <div class="project-links">
              <a v-if="project.liveUrl" :href="project.liveUrl" target="_blank" class="project-link">
                Live
              </a>
              <a v-if="project.githubUrl" :href="project.githubUrl" target="_blank" class="project-link">
                GitHub
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
      <div class="about-content">
        <h2 class="section-number">01</h2>
        <h3 class="section-title">About</h3>
        <p class="about-text">
          Full-stack developer with 10+ years of experience building innovative web & mobile experiences.
          Specializing in Vue.js, Laravel, Flutter, Unity, and Three.js.
        </p>
        <div class="skills-list">
          <span v-for="skill in skills" :key="skill" class="skill-item">{{ skill }}</span>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
      <div class="contact-content">
        <h2 class="section-number">02</h2>
        <h3 class="section-title">Get In Touch</h3>
        <p class="contact-text">
          Interested in working together? Let's create something amazing.
        </p>
        <div class="contact-links">
          <a href="mailto:ganeshr848@gmail.com" class="contact-link">Email</a>
          <a href="https://linkedin.com/in/ganeshkp" target="_blank" class="contact-link">LinkedIn</a>
          <a href="https://github.com/ganeshkgp" target="_blank" class="contact-link">GitHub</a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <p>&copy; 2024 Ganesh K P. All rights reserved.</p>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import SciFiBuilding from '../components/SciFiBuilding.vue'

const activeProject = ref(null)

const projects = ref([
  {
    id: 1,
    title: 'E-Commerce Platform',
    description: 'Full-stack e-commerce solution with real-time inventory management and AI-powered recommendations.',
    technologies: ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'Stripe API'],
    liveUrl: 'https://example-ecommerce.com',
    githubUrl: 'https://github.com/ganeshkgp/ecommerce-platform'
  },
  {
    id: 2,
    title: 'Mobile Banking App',
    description: 'Cross-platform mobile banking application with biometric authentication and secure transactions.',
    technologies: ['Flutter', 'Node.js', 'PostgreSQL', 'JWT'],
    liveUrl: 'https://apps.apple.com/banking-app',
    githubUrl: 'https://github.com/ganeshkgp/mobile-banking'
  },
  {
    id: 3,
    title: '3D Game Engine',
    description: 'Custom Unity game engine with advanced physics simulation and multiplayer support.',
    technologies: ['Unity', 'C#', 'WebSocket', 'Photon'],
    liveUrl: 'https://steam.com/game-engine',
    githubUrl: 'https://github.com/ganeshkgp/3d-game-engine'
  },
  {
    id: 4,
    title: 'AI Analytics Dashboard',
    description: 'Real-time analytics dashboard with machine learning insights and data visualization.',
    technologies: ['Python', 'Vue.js', 'TensorFlow', 'D3.js', 'FastAPI'],
    liveUrl: 'https://analytics.example.com',
    githubUrl: 'https://github.com/ganeshkgp/ai-dashboard'
  },
  {
    id: 5,
    title: 'Social Media Platform',
    description: 'Scalable social networking platform with real-time messaging and content sharing.',
    technologies: ['Laravel', 'Vue.js', 'Socket.io', 'Elasticsearch', 'AWS S3'],
    liveUrl: 'https://social.example.com',
    githubUrl: 'https://github.com/ganeshkgp/social-platform'
  },
  {
    id: 6,
    title: 'IoT Control System',
    description: 'Smart home automation system with voice control and mobile app integration.',
    technologies: ['Flutter', 'Python', 'MQTT', 'Raspberry Pi', 'AWS IoT'],
    liveUrl: 'https://iot.example.com',
    githubUrl: 'https://github.com/ganeshkgp/iot-control'
  }
])

const skills = ref([
  'Vue.js', 'React', 'Laravel', 'Node.js',
  'Flutter', 'Unity', 'Three.js', 'AWS',
  'Docker', 'Python', 'TensorFlow', 'MySQL'
])

onMounted(() => {
  // Add smooth scroll behavior
  document.documentElement.style.scrollBehavior = 'smooth'
})
</script>

<style scoped>
.home-container {
  min-height: 100vh;
  background: #0a0a0a;
  color: #ffffff;
  overflow-x: hidden;
}

/* Navigation */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(10, 10, 10, 0.8);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding: 1rem 0;
}

.nav-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-text {
  font-size: 1.5rem;
  font-weight: bold;
  color: #ffffff;
}

.nav-links {
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s ease;
  position: relative;
  padding: 0.5rem 0;
  font-size: 0.9rem;
}

.nav-link:hover,
.nav-link.active {
  color: #00ffff;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: #00ffff;
  transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
  width: 100%;
}

/* Hero Section */
.hero-section {
  position: relative;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.hero-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  z-index: 2;
  pointer-events: none;
}

.hero-title {
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 300;
  margin-bottom: 0.5rem;
  color: #ffffff;
  letter-spacing: 2px;
}

.hero-subtitle {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.7);
  margin: 0;
  letter-spacing: 1px;
}

.scroll-indicator {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  z-index: 2;
}

.scroll-text {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.5);
  letter-spacing: 2px;
  text-transform: uppercase;
}

.scroll-arrow {
  width: 1px;
  height: 30px;
  background: linear-gradient(to bottom, transparent, #00ffff);
  position: relative;
}

.scroll-arrow::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: -4px;
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 8px solid #00ffff;
}

/* Projects Section */
.projects-section {
  padding: 6rem 2rem;
  background: #0a0a0a;
}

.projects-grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
}

.project-card {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  padding: 2rem;
  position: relative;
  transition: all 0.3s ease;
  cursor: pointer;
}

.project-card:hover {
  border-color: rgba(0, 255, 255, 0.3);
  transform: translateY(-5px);
}

.project-card.active {
  border-color: #00ffff;
  background: rgba(0, 255, 255, 0.05);
}

.project-number {
  position: absolute;
  top: 1rem;
  right: 1.5rem;
  font-size: 2rem;
  font-weight: bold;
  color: rgba(0, 255, 255, 0.2);
}

.project-content h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: #ffffff;
}

.project-description {
  color: rgba(255, 255, 255, 0.7);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.project-tech {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.tech-tag {
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.2);
  color: #00ffff;
  padding: 0.3rem 0.8rem;
  border-radius: 4px;
  font-size: 0.8rem;
}

.project-links {
  display: flex;
  gap: 1rem;
}

.project-link {
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  font-size: 0.9rem;
  position: relative;
  padding-bottom: 2px;
}

.project-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 1px;
  background: #00ffff;
  transition: width 0.3s ease;
}

.project-link:hover::after {
  width: 100%;
}

.project-link:hover {
  color: #00ffff;
}

/* About Section */
.about-section {
  padding: 6rem 2rem;
  background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
}

.about-content {
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

.section-number {
  font-size: 4rem;
  font-weight: bold;
  color: rgba(0, 255, 255, 0.2);
  margin-bottom: 1rem;
}

.section-title {
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #ffffff;
  font-weight: 300;
  letter-spacing: 1px;
}

.about-text {
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.8;
  margin-bottom: 3rem;
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: center;
}

.skill-item {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: rgba(255, 255, 255, 0.9);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
}

/* Contact Section */
.contact-section {
  padding: 6rem 2rem;
  background: #0a0a0a;
}

.contact-content {
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

.contact-text {
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.8;
  margin-bottom: 3rem;
}

.contact-links {
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.contact-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  font-size: 1rem;
  position: relative;
  padding-bottom: 4px;
  transition: color 0.3s ease;
}

.contact-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 1px;
  background: #00ffff;
  transition: width 0.3s ease;
}

.contact-link:hover::after {
  width: 100%;
}

.contact-link:hover {
  color: #00ffff;
}

/* Footer */
.footer {
  padding: 2rem;
  text-align: center;
  background: #0a0a0a;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.9rem;
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