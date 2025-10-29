<template>
  <AppLayout>
    <!-- Projects Header -->
    <header class="projects-header">
      <h1 class="projects-title">Projects Portfolio</h1>
      <p class="projects-subtitle">Explore my latest work and technical achievements</p>
    </header>

    <!-- 3D Iron Man Style Wireframe Sphere -->
    <section class="scifi-grid-section">
      <div class="scifi-grid-container" ref="scifiGrid">
        <!-- Wireframe Sphere Structure -->
        <div class="wireframe-sphere">
          <!-- Latitude lines -->
          <div v-for="i in 8" :key="'lat-' + i"
               class="wireframe-circle wireframe-latitude"
               :style="{ transform: `rotateX(${i * 22.5}deg)` }">
          </div>
          <!-- Longitude lines -->
          <div v-for="i in 12" :key="'lon-' + i"
               class="wireframe-circle wireframe-longitude"
               :style="{ transform: `rotateY(${i * 15}deg)` }">
          </div>
          <!-- Central core -->
          <div class="sphere-core"></div>
        </div>

        <div class="scifi-grid">
          <div
            v-for="(project, index) in projects"
            :key="project.id"
            class="scifi-project-card"
            :class="{ active: expandedProject?.id === project.id, expanded: expandedProject?.id === project.id }"
            :style="{
              transform: `translate3d(${getSpherePosition(index).x}px, ${getSpherePosition(index).y}px, ${getSpherePosition(index).z}px) rotateX(${getCardRotation(index).x}deg) rotateY(${getCardRotation(index).y}deg) rotateZ(${getCardRotation(index).z}deg)`,
              animationDelay: `${index * 0.1}s`,
              '--icon-color': getProjectColor(project.id),
              '--curvature': getCardCurvature(index)
            }"
            @click="toggleProject(project)"
          >
            <!-- Card Frame with Curved Surface -->
            <div class="scifi-frame">
              <!-- Curved Surface Effect -->
              <div class="scifi-curved-surface"></div>

              <!-- Project Icon with 3D effect -->
              <div class="scifi-icon-container">
                <div class="scifi-icon-bg" :style="{ backgroundColor: getProjectColor(project.id) }">
                  <div class="scifi-icon">{{ getProjectIcon(project.id) }}</div>
                </div>
                <div class="scifi-icon-glow"></div>
              </div>

              <!-- Compact View -->
              <div class="scifi-compact">
                <h3 class="scifi-project-name">{{ project.name }}</h3>
                <div class="scifi-tech-compact">
                  <span v-for="tech in project.technologies.slice(0, 3)" :key="tech" class="scifi-tech-tag">
                    {{ tech }}
                  </span>
                  <span v-if="project.technologies.length > 3" class="scifi-tech-tag more">
                    +{{ project.technologies.length - 3 }}
                  </span>
                </div>
              </div>

              <!-- Expanded View -->
              <div class="scifi-expanded" v-if="expandedProject?.id === project.id">
                <p class="scifi-description">{{ project.description }}</p>
                <div class="scifi-tech">
                  <span v-for="tech in project.technologies" :key="tech" class="scifi-tech-tag">
                    {{ tech }}
                  </span>
                </div>
                <div class="scifi-links">
                  <a v-if="project.liveUrl" :href="project.liveUrl" target="_blank" class="scifi-link live-link" @click.stop>
                    <span class="scifi-link-icon">🚀</span> Launch
                  </a>
                  <a v-if="project.githubUrl" :href="project.githubUrl" target="_blank" class="scifi-link github-link" @click.stop>
                    <span class="scifi-link-icon">💻</span> Source
                  </a>
                </div>
              </div>
            </div>

            <!-- Hover Effects -->
            <div class="scifi-hover-effect"></div>
            <div class="scifi-corner-decoration top-left"></div>
            <div class="scifi-corner-decoration top-right"></div>
            <div class="scifi-corner-decoration bottom-left"></div>
            <div class="scifi-corner-decoration bottom-right"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Project Modal -->
    <div class="project-modal" :class="{ active: selectedProject }" v-if="selectedProject">
      <div class="modal-overlay" @click="closeProjectModal"></div>
      <div class="modal-content">
        <button class="modal-close" @click="closeProjectModal">×</button>
        <div class="modal-project-details">
          <div class="modal-header">
            <div class="modal-icon" :style="{ backgroundColor: getProjectColor(selectedProject.id) }">
              {{ getProjectIcon(selectedProject.id) }}
            </div>
            <h2>{{ selectedProject.name }}</h2>
          </div>

          <div class="modal-body">
            <p class="modal-description">{{ selectedProject.description }}</p>

            <div class="modal-tech">
              <h3>🚀 Technologies Used</h3>
              <div class="tech-tags">
                <span v-for="tech in selectedProject.technologies" :key="tech" class="tech-tag">
                  {{ tech }}
                </span>
              </div>
            </div>

            <div class="modal-links">
              <h3>🔗 Links</h3>
              <div class="link-buttons">
                <a v-if="selectedProject.liveUrl" :href="selectedProject.liveUrl" target="_blank" class="modal-link live-link">
                  <span class="link-icon">🚀</span> Live Demo
                </a>
                <a v-if="selectedProject.githubUrl" :href="selectedProject.githubUrl" target="_blank" class="modal-link github-link">
                  <span class="link-icon">💻</span> View Source
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '../components/AppLayout.vue'

const selectedProject = ref(null)
const expandedProject = ref(null)
const scifiGrid = ref(null)

// Enhanced projects data with real examples
const projects = ref([
  {
    id: 1,
    name: 'E-Commerce Platform',
    description: 'Full-stack e-commerce solution with real-time inventory management, secure payment processing, and advanced analytics dashboard.',
    image: '/images/projects/ecommerce.jpg',
    technologies: ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'Stripe API'],
    liveUrl: 'https://example-ecommerce.com',
    githubUrl: 'https://github.com/example/ecommerce'
  },
  {
    id: 2,
    name: 'Mobile Banking App',
    description: 'Cross-platform mobile banking application with biometric authentication, real-time transactions, and budget tracking features.',
    image: '/images/projects/banking.jpg',
    technologies: ['Flutter', 'Node.js', 'PostgreSQL', 'JWT'],
    liveUrl: 'https://example-banking.com',
    githubUrl: 'https://github.com/example/banking'
  },
  {
    id: 3,
    name: '3D Game Engine',
    description: 'Custom Unity game engine with physics simulation, multiplayer support, and cross-platform deployment capabilities.',
    image: '/images/projects/game.jpg',
    technologies: ['Unity', 'C#', 'WebSocket', 'Redis'],
    liveUrl: 'https://example-game.com',
    githubUrl: 'https://github.com/example/game'
  },
  {
    id: 4,
    name: 'AI Analytics Dashboard',
    description: 'Real-time analytics dashboard with machine learning predictions, data visualization, and automated reporting features.',
    image: '/images/projects/analytics.jpg',
    technologies: ['Python', 'Vue.js', 'TensorFlow', 'D3.js'],
    liveUrl: 'https://example-analytics.com',
    githubUrl: 'https://github.com/example/analytics'
  },
  {
    id: 5,
    name: 'Social Media Platform',
    description: 'Scalable social networking platform with real-time messaging, content sharing, and community features.',
    image: '/images/projects/social.jpg',
    technologies: ['Laravel', 'Vue.js', 'Socket.io', 'MySQL'],
    liveUrl: 'https://example-social.com',
    githubUrl: 'https://github.com/example/social'
  },
  {
    id: 6,
    name: 'IoT Control System',
    description: 'Smart home automation system with voice control, mobile app integration, and sensor monitoring capabilities.',
    image: '/images/projects/iot.jpg',
    technologies: ['Flutter', 'Python', 'MQTT', 'Raspberry Pi'],
    liveUrl: 'https://example-iot.com',
    githubUrl: 'https://github.com/example/iot'
  }
])

const toggleProject = (project) => {
  // On mobile, expand inline instead of opening modal
  if (window.innerWidth <= 768) {
    if (expandedProject.value?.id === project.id) {
      expandedProject.value = null
    } else {
      expandedProject.value = project
    }
  } else {
    // On desktop, use the modal
    selectProject(project)
  }
}

const selectProject = (project) => {
  selectedProject.value = project
  expandedProject.value = null // Close expanded view when modal opens
}

const closeProjectModal = () => {
  selectedProject.value = null
}

const getProjectColor = (id) => {
  const colors = [
    '#00ffff', // Cyan
    '#ff00ff', // Magenta
    '#ffff00', // Yellow
    '#00ff00', // Green
    '#ff6b6b', // Red
    '#4ecdc4'  // Teal
  ]
  return colors[id - 1] || '#00ffff'
}

const getProjectIcon = (id) => {
  const icons = ['🛒', '📱', '🎮', '📊', '🌐', '🏠']
  return icons[id - 1] || '🚀'
}

const getSpherePosition = (index) => {
  const totalCards = projects.value.length
  const radius = 300

  // Distribute points evenly on sphere surface using golden ratio
  const goldenRatio = (1 + Math.sqrt(5)) / 2
  const angleIncrement = Math.PI * 2 * goldenRatio

  const y = 1 - (index / (totalCards - 1)) * 2 // y goes from 1 to -1
  const radiusAtY = Math.sqrt(1 - y * y)
  const theta = angleIncrement * index

  const x = Math.cos(theta) * radiusAtY
  const z = Math.sin(theta) * radiusAtY

  return {
    x: x * radius,
    y: y * radius,
    z: z * radius
  }
}

const getCardRotation = (index) => {
  const pos = getSpherePosition(index)
  // Calculate rotation to face outward from sphere center
  const yaw = Math.atan2(pos.x, pos.z) * (180 / Math.PI)
  const pitch = Math.asin(pos.y / 300) * (180 / Math.PI)
  return { x: -pitch, y: yaw, z: 0 }
}

const getCardCurvature = (index) => {
  const pos = getSpherePosition(index)
  // Calculate the amount of curvature needed based on position
  const distance = Math.sqrt(pos.x * pos.x + pos.z * pos.z)
  const curvatureFactor = 1 - (distance / 300) * 0.3 // Less curvature on edges, more on center
  return curvatureFactor
}
</script>

<style scoped>

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

/* 3D Iron Man Style Wireframe Sphere Section */
.scifi-grid-section {
  padding: 0 2rem 4rem;
  max-width: 1200px;
  margin: 0 auto;
  perspective: 1200px;
  min-height: 700px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: radial-gradient(ellipse at center, #001d3d 0%, #000814 100%);
  position: relative;
}

.scifi-grid-container {
  width: 100%;
  height: 600px;
  position: relative;
  transform-style: preserve-3d;
  animation: sphereRotate 40s linear infinite;
}

@keyframes sphereRotate {
  from {
    transform: rotateY(0deg) rotateX(-10deg);
  }
  to {
    transform: rotateY(360deg) rotateX(-10deg);
  }
}

/* Wireframe Sphere Structure */
.wireframe-sphere {
  position: absolute;
  width: 600px;
  height: 600px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  transform-style: preserve-3d;
  pointer-events: none;
}

.wireframe-circle {
  position: absolute;
  width: 100%;
  height: 100%;
  border: 1px solid rgba(255, 214, 10, 0.3);
  border-radius: 50%;
  box-shadow:
    0 0 10px rgba(255, 214, 10, 0.2),
    inset 0 0 10px rgba(255, 214, 10, 0.1);
  animation: wireframePulse 3s ease-in-out infinite;
}

.wireframe-latitude {
  border-style: dashed;
}

.wireframe-longitude {
  border-style: solid;
}

.sphere-core {
  position: absolute;
  width: 60px;
  height: 60px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: radial-gradient(circle, rgba(255, 214, 10, 0.8) 0%, rgba(255, 214, 10, 0.3) 50%, transparent 70%);
  border-radius: 50%;
  box-shadow:
    0 0 30px rgba(255, 214, 10, 0.6),
    0 0 60px rgba(255, 214, 10, 0.3),
    inset 0 0 20px rgba(255, 255, 255, 0.5);
  animation: corePulse 2s ease-in-out infinite;
}

@keyframes wireframePulse {
  0%, 100% {
    opacity: 0.3;
    border-color: rgba(255, 214, 10, 0.3);
  }
  50% {
    opacity: 0.8;
    border-color: rgba(255, 214, 10, 0.6);
  }
}

@keyframes corePulse {
  0%, 100% {
    transform: translate(-50%, -50%) scale(1);
    box-shadow:
      0 0 30px rgba(255, 214, 10, 0.6),
      0 0 60px rgba(255, 214, 10, 0.3),
      inset 0 0 20px rgba(255, 255, 255, 0.5);
  }
  50% {
    transform: translate(-50%, -50%) scale(1.2);
    box-shadow:
      0 0 40px rgba(255, 214, 10, 0.8),
      0 0 80px rgba(255, 214, 10, 0.5),
      inset 0 0 30px rgba(255, 255, 255, 0.7);
  }
}

.scifi-grid {
  width: 100%;
  height: 100%;
  position: relative;
  transform-style: preserve-3d;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* 3D Sci-Fi Project Cards on Sphere */
.scifi-project-card {
  position: absolute;
  width: 180px;
  height: 220px;
  transform-style: preserve-3d;
  transition: all 0.4s ease;
  cursor: pointer;
  animation: sphereCardPulse 4s ease-in-out infinite;
  left: 50%;
  top: 50%;
  margin-left: -90px;
  margin-top: -110px;
  perspective: 400px;
}

.scifi-project-card:hover {
  filter: brightness(1.3) drop-shadow(0 0 20px rgba(0, 255, 255, 0.6));
  animation-play-state: paused;
  z-index: 100;
}

.scifi-project-card:hover .scifi-frame {
  transform: perspective(300px) rotateY(0deg) translateZ(10px) scale(1.05);
  border-radius: calc(12px * var(--curvature));
}

.scifi-project-card.expanded {
  transform: scale(1.5);
  z-index: 200;
  filter: brightness(1.5) drop-shadow(0 0 30px rgba(0, 255, 255, 0.8));
}

.scifi-project-card.active {
  z-index: 150;
}

@keyframes sphereCardPulse {
  0%, 100% {
    filter: brightness(1) drop-shadow(0 0 10px rgba(0, 255, 255, 0.3));
  }
  50% {
    filter: brightness(1.1) drop-shadow(0 0 15px rgba(0, 255, 255, 0.5));
  }
}

.scifi-frame {
  width: 100%;
  height: 100%;
  background: rgba(0, 8, 20, 0.95);
  border: 1px solid rgba(255, 214, 10, 0.4);
  border-radius: 15px;
  position: relative;
  overflow: hidden;
  backdrop-filter: blur(15px);
  box-shadow:
    0 0 20px rgba(255, 214, 10, 0.3),
    inset 0 0 15px rgba(255, 214, 10, 0.1);
  transform-style: preserve-3d;
  transform: perspective(300px) rotateY(0deg) translateZ(0px);
  transition: transform 0.4s ease;
  border-radius: calc(15px * var(--curvature));
}

.scifi-frame::before {
  content: '';
  position: absolute;
  top: -1px;
  left: -1px;
  right: -1px;
  bottom: -1px;
  background: linear-gradient(45deg, transparent, rgba(255, 214, 10, 0.2), transparent);
  border-radius: calc(15px * var(--curvature));
  z-index: -1;
  animation: frameScan 2s linear infinite;
}

/* Curved card content overlay */
.scifi-frame::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(ellipse at center, transparent 0%, rgba(0, 10, 30, 0.2) 100%);
  border-radius: calc(15px * var(--curvature));
  pointer-events: none;
  z-index: 1;
}

@keyframes frameScan {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Curved Surface Effect */
.scifi-curved-surface {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: calc(15px * var(--curvature));
  background: linear-gradient(135deg,
    rgba(255, 214, 10, 0.05) 0%,
    transparent 30%,
    transparent 70%,
    rgba(255, 214, 10, 0.05) 100%);
  transform-style: preserve-3d;
  transform: perspective(200px) rotateX(calc(5deg * var(--curvature)));
  pointer-events: none;
  z-index: 2;
}

.scifi-curved-surface::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: calc(15px * var(--curvature));
  background:
    radial-gradient(ellipse at 30% 30%, rgba(255, 214, 10, 0.1) 0%, transparent 50%),
    radial-gradient(ellipse at 70% 70%, rgba(255, 140, 0, 0.05) 0%, transparent 50%);
  animation: curvedSurfaceShimmer 3s ease-in-out infinite;
}

@keyframes curvedSurfaceShimmer {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.7;
    transform: scale(1.02);
  }
}

.scifi-icon-container {
  position: absolute;
  top: 15px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
}

.scifi-icon-bg {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  position: relative;
  background: rgba(0, 20, 40, 0.8);
  border: 1px solid var(--icon-color);
  box-shadow: 0 0 15px var(--icon-color);
  animation: iconPulse 2.5s ease-in-out infinite;
}

@keyframes iconPulse {
  0%, 100% {
    box-shadow: 0 0 15px var(--icon-color);
    border-color: var(--icon-color);
  }
  50% {
    box-shadow: 0 0 25px var(--icon-color), 0 0 40px var(--icon-color);
    border-color: rgba(255, 255, 255, 0.8);
  }
}

.scifi-icon-glow {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 70px;
  height: 70px;
  background: radial-gradient(circle, var(--icon-color) 0%, transparent 70%);
  border-radius: 50%;
  opacity: 0.4;
  animation: glowExpand 3s ease-in-out infinite;
}

@keyframes glowExpand {
  0%, 100% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0.4;
  }
  50% {
    transform: translate(-50%, -50%) scale(1.3);
    opacity: 0.1;
  }
}

.scifi-compact {
  position: absolute;
  top: 75px;
  left: 10px;
  right: 10px;
  text-align: center;
}

.scifi-project-name {
  color: #ffffff;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  background: linear-gradient(45deg, #00ffff, #0099cc);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
  line-height: 1.2;
}

.scifi-tech-compact {
  display: flex;
  flex-wrap: wrap;
  gap: 0.2rem;
  justify-content: center;
}

.scifi-tech-tag {
  background: rgba(0, 100, 150, 0.2);
  border: 1px solid rgba(0, 200, 255, 0.4);
  color: #00ccff;
  padding: 0.1rem 0.3rem;
  border-radius: 8px;
  font-size: 0.55rem;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  font-weight: 500;
}

.scifi-tech-tag.more {
  background: rgba(0, 150, 200, 0.2);
  border-color: rgba(0, 200, 255, 0.6);
  color: #00ffff;
}

.scifi-expanded {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90%;
  background: rgba(0, 0, 0, 0.95);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 15px;
  padding: 1.5rem;
  backdrop-filter: blur(20px);
  z-index: 20;
  animation: expandIn 0.5s ease-out;
}

@keyframes expandIn {
  from {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.8);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
}

.scifi-description {
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.5;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.scifi-tech {
  display: flex;
  flex-wrap: wrap;
  gap: 0.3rem;
  margin-bottom: 1rem;
  justify-content: center;
}

.scifi-links {
  display: flex;
  gap: 0.8rem;
  justify-content: center;
}

.scifi-link {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.6rem 1.2rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  color: #000000;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.8rem;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.scifi-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 255, 255, 0.4);
  background: linear-gradient(45deg, #ff00ff, #00ffff);
}

.scifi-link-icon {
  font-size: 0.9rem;
}

.scifi-hover-effect {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, transparent, rgba(0, 255, 255, 0.1), transparent);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
  border-radius: 20px;
}

.scifi-project-card:hover .scifi-hover-effect {
  opacity: 1;
  animation: hoverSweep 0.6s ease-out;
}

@keyframes hoverSweep {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(100%);
  }
}

.scifi-corner-decoration {
  position: absolute;
  width: 20px;
  height: 20px;
  border: 2px solid #00ffff;
  opacity: 0.6;
}

.scifi-corner-decoration::before,
.scifi-corner-decoration::after {
  content: '';
  position: absolute;
  background: #00ffff;
}

.scifi-corner-decoration.top-left {
  top: 10px;
  left: 10px;
  border-right: none;
  border-bottom: none;
}

.scifi-corner-decoration.top-right {
  top: 10px;
  right: 10px;
  border-left: none;
  border-bottom: none;
}

.scifi-corner-decoration.bottom-left {
  bottom: 10px;
  left: 10px;
  border-right: none;
  border-top: none;
}

.scifi-corner-decoration.bottom-right {
  bottom: 10px;
  right: 10px;
  border-left: none;
  border-top: none;
}



/* Project Modal */
.project-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.project-modal.active {
  opacity: 1;
  visibility: visible;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(5px);
}

.modal-content {
  position: relative;
  background: rgba(20, 20, 30, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  padding: 2.5rem;
  max-width: 600px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  backdrop-filter: blur(20px);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  transform: scale(0.9);
  transition: transform 0.3s ease;
}

.project-modal.active .modal-content {
  transform: scale(1);
}

.modal-close {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.8);
  font-size: 2rem;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  color: #ff00ff;
  background: rgba(255, 0, 255, 0.1);
}

.modal-header {
  text-align: center;
  margin-bottom: 2rem;
}

.modal-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  margin: 0 auto 1rem;
  filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.5));
  animation: float 3s ease-in-out infinite;
}

.modal-header h2 {
  color: #ffffff;
  font-size: 2rem;
  margin: 0;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.modal-body {
  text-align: left;
}

.modal-description {
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.6;
  margin-bottom: 2rem;
  font-size: 1rem;
}

.modal-tech {
  margin-bottom: 2rem;
}

.modal-tech h3 {
  color: #00ffff;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.modal-links h3 {
  color: #00ffff;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.link-buttons {
  display: flex;
  gap: 1rem;
}

.modal-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.8rem 1.5rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  color: #000000;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 600;
  transition: all 0.3s ease;
  flex: 1;
  justify-content: center;
}

.modal-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 255, 255, 0.4);
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

  .scifi-grid-section {
    padding: 1rem;
    perspective: 1000px;
    min-height: 600px;
  }

  .wireframe-sphere {
    width: 400px;
    height: 400px;
  }

  .scifi-grid-container {
    height: 500px;
    animation: sphereRotate 50s linear infinite;
  }

  .scifi-project-card {
    width: 140px;
    height: 170px;
    margin-left: -70px;
    margin-top: -85px;
  }

  .scifi-icon-bg {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
  }

  .scifi-compact {
    top: 60px;
    left: 8px;
    right: 8px;
  }

  .scifi-project-name {
    font-size: 0.7rem;
    margin-bottom: 0.4rem;
  }

  .scifi-tech-tag {
    font-size: 0.45rem;
    padding: 0.08rem 0.2rem;
  }

  .scifi-expanded {
    padding: 0.8rem;
    width: 95%;
  }

  .scifi-description {
    font-size: 0.75rem;
  }

  .scifi-link {
    padding: 0.4rem 0.8rem;
    font-size: 0.6rem;
  }
}

@media (max-width: 480px) {
  .scifi-grid-section {
    padding: 0.5rem;
    perspective: 800px;
    min-height: 500px;
  }

  .wireframe-sphere {
    width: 300px;
    height: 300px;
  }

  .sphere-core {
    width: 40px;
    height: 40px;
  }

  .scifi-grid-container {
    height: 400px;
    animation: sphereRotate 60s linear infinite;
  }

  .scifi-project-card {
    width: 120px;
    height: 150px;
    margin-left: -60px;
    margin-top: -75px;
  }

  .scifi-icon-bg {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }

  .scifi-compact {
    top: 50px;
    left: 6px;
    right: 6px;
  }

  .scifi-project-name {
    font-size: 0.6rem;
    margin-bottom: 0.3rem;
  }

  .scifi-tech-tag {
    font-size: 0.4rem;
    padding: 0.06rem 0.15rem;
  }

  .scifi-expanded {
    padding: 0.6rem;
  }

  .scifi-description {
    font-size: 0.7rem;
    line-height: 1.3;
  }

  .scifi-link {
    padding: 0.3rem 0.6rem;
    font-size: 0.55rem;
  }

  .scifi-links {
    flex-direction: column;
    gap: 0.4rem;
  }
}
</style>