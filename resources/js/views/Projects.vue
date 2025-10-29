<template>
  <div class="projects-container">
    <!-- Navigation -->
    <nav class="navbar">
      <div class="nav-content">
        <div class="logo">
          <span class="logo-text">GK</span>
        </div>
        <div class="nav-links">
          <router-link to="/" class="nav-link">Home</router-link>
          <router-link to="/projects" class="nav-link active">Projects</router-link>
          <router-link to="/contact" class="nav-link">Contact</router-link>
          <router-link to="/admin" class="nav-link">Admin</router-link>
        </div>
      </div>
    </nav>

    <!-- Projects Header -->
    <header class="projects-header">
      <h1 class="projects-title">3D Projects Gallery</h1>
      <p class="projects-subtitle">Navigate through my interactive project space</p>
    </header>

    <!-- 3D Gallery Component -->
    <section class="gallery-section">
      <ProjectsGallery3D @projectSelected="handleProjectSelection" />

      <!-- Project Info Panel -->
      <div class="project-info-panel" :class="{ active: selectedProject }">
        <button class="close-btn" @click="selectedProject = null">×</button>
        <div v-if="selectedProject" class="project-details">
          <h2>{{ selectedProject.name }}</h2>
          <img :src="selectedProject.image" :alt="selectedProject.name" class="project-image">
          <p class="project-description">{{ selectedProject.description }}</p>
          <div class="project-tech">
            <span v-for="tech in selectedProject.technologies" :key="tech" class="tech-tag">
              {{ tech }}
            </span>
          </div>
          <div class="project-links">
            <a v-if="selectedProject.liveUrl" :href="selectedProject.liveUrl" target="_blank" class="project-link">
              Live Demo
            </a>
            <a v-if="selectedProject.githubUrl" :href="selectedProject.githubUrl" target="_blank" class="project-link">
              GitHub
            </a>
          </div>
        </div>
      </div>

      <!-- Controls Info -->
      <div class="controls-info">
        <div class="control-item">
          <span class="control-key">W/A/S/D</span>
          <span class="control-desc">Move</span>
        </div>
        <div class="control-item">
          <span class="control-key">Mouse</span>
          <span class="control-desc">Look Around</span>
        </div>
        <div class="control-item">
          <span class="control-key">Click</span>
          <span class="control-desc">Select Project</span>
        </div>
      </div>
    </section>

    <!-- Loading State -->
    <div v-if="loading" class="loading-screen">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <p>Loading 3D Gallery...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ProjectsGallery3D from '../components/ProjectsGallery3D.vue'

const loading = ref(true)
const selectedProject = ref(null)

// Sample projects data - this will come from Laravel API
const projects = ref([
  {
    id: 1,
    name: 'E-Commerce Platform',
    description: 'Full-stack e-commerce solution with real-time inventory management and payment processing.',
    image: '/images/projects/ecommerce.jpg',
    technologies: ['Laravel', 'Vue.js', 'MySQL', 'Redis'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 0, y: 0, z: 0 }
  },
  {
    id: 2,
    name: 'Mobile Banking App',
    description: 'Cross-platform mobile banking application with biometric authentication.',
    image: '/images/projects/banking.jpg',
    technologies: ['Flutter', 'Node.js', 'PostgreSQL'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 5, y: 0, z: 0 }
  },
  {
    id: 3,
    name: '3D Game Engine',
    description: 'Custom Unity game engine with physics simulation and multiplayer support.',
    image: '/images/projects/game.jpg',
    technologies: ['Unity', 'C#', 'WebSocket'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: -5, y: 0, z: 0 }
  },
  {
    id: 4,
    name: 'AI Dashboard',
    description: 'Real-time analytics dashboard with machine learning predictions.',
    image: '/images/projects/analytics.jpg',
    technologies: ['Python', 'Vue.js', 'TensorFlow'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 0, y: 0, z: 5 }
  },
  {
    id: 5,
    name: 'Social Media Platform',
    description: 'Scalable social networking platform with real-time messaging.',
    image: '/images/projects/social.jpg',
    technologies: ['Laravel', 'Vue.js', 'Socket.io'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 5, y: 0, z: 5 }
  },
  {
    id: 6,
    name: 'IoT Control System',
    description: 'Smart home automation system with voice control and mobile app.',
    image: '/images/projects/iot.jpg',
    technologies: ['Flutter', 'Python', 'MQTT'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: -5, y: 0, z: 5 }
  }
])

onMounted(() => {
  // Gallery component will handle initialization
  console.log('Projects page mounted with 3D gallery')
})

const handleProjectSelection = (project) => {
  selectedProject.value = project
  console.log('Selected project:', project)
}
</script>

<style scoped>
.projects-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0f0f23 100%);
  position: relative;
  overflow: hidden;
}

/* Navigation */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(10, 10, 10, 0.8);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
}

.logo-text {
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
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
}

.nav-link:hover,
.nav-link.active {
  color: #00ffff;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #00ffff, #ff00ff);
  transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
  width: 100%;
}

/* Projects Header */
.projects-header {
  padding: 8rem 2rem 3rem;
  text-align: center;
  position: relative;
  z-index: 10;
}

.projects-title {
  font-size: clamp(2.5rem, 8vw, 4rem);
  font-weight: 900;
  margin-bottom: 1rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff, #ffff00);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: gradient-shift 3s ease infinite;
}

@keyframes gradient-shift {
  0%, 100% { filter: hue-rotate(0deg); }
  50% { filter: hue-rotate(30deg); }
}

.projects-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.7);
}

/* Gallery Section */
.gallery-section {
  position: relative;
  height: calc(100vh - 200px);
  min-height: 600px;
}

.projects-3d-container {
  width: 100%;
  height: 100%;
  position: relative;
}

/* Project Info Panel */
.project-info-panel {
  position: fixed;
  right: -400px;
  top: 0;
  width: 400px;
  height: 100vh;
  background: rgba(10, 10, 10, 0.95);
  backdrop-filter: blur(20px);
  border-left: 1px solid rgba(255, 255, 255, 0.1);
  padding: 2rem;
  transition: right 0.3s ease;
  z-index: 1001;
  overflow-y: auto;
}

.project-info-panel.active {
  right: 0;
}

.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.8);
  font-size: 2rem;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: #ff00ff;
}

.project-details h2 {
  color: #ffffff;
  font-size: 1.8rem;
  margin-bottom: 1rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.project-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 1rem;
}

.project-description {
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.project-tech {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 2rem;
}

.tech-tag {
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  color: #00ffff;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.8rem;
}

.project-links {
  display: flex;
  gap: 1rem;
}

.project-link {
  display: inline-block;
  padding: 0.8rem 1.5rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  color: #000000;
  text-decoration: none;
  border-radius: 25px;
  font-weight: bold;
  transition: all 0.3s ease;
  text-align: center;
  flex: 1;
}

.project-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 255, 255, 0.4);
}

/* Controls Info */
.controls-info {
  position: fixed;
  bottom: 2rem;
  left: 2rem;
  background: rgba(10, 10, 10, 0.8);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  padding: 1.5rem;
  z-index: 100;
}

.control-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.8rem;
}

.control-item:last-child {
  margin-bottom: 0;
}

.control-key {
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  color: #00ffff;
  padding: 0.3rem 0.6rem;
  border-radius: 5px;
  font-family: monospace;
  font-size: 0.9rem;
  margin-right: 1rem;
}

.control-desc {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
}

/* Loading Screen */
.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #0a0a0a;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  transition: opacity 0.5s ease;
}

.loading-content {
  text-align: center;
}

.loading-spinner {
  width: 60px;
  height: 60px;
  border: 3px solid rgba(0, 255, 255, 0.1);
  border-top: 3px solid #00ffff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading-content p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 1.1rem;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .nav-content {
    padding: 1rem;
  }

  .nav-links {
    gap: 1rem;
    font-size: 0.9rem;
  }

  .projects-header {
    padding: 6rem 1rem 2rem;
  }

  .project-info-panel {
    width: 100%;
    right: -100%;
  }

  .controls-info {
    bottom: 1rem;
    left: 1rem;
    right: 1rem;
    padding: 1rem;
  }

  .control-item {
    justify-content: space-between;
  }

  .control-key {
    margin-right: 0.5rem;
  }
}
</style>