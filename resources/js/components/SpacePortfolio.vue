<template>
  <div ref="spaceContainer" class="space-container">
    <!-- Loading Screen -->
    <div class="loading-screen" v-if="isLoading">
      <div class="loading-content">
        <div class="loading-stars">
          <div class="star" v-for="i in 50" :key="i" :style="getStarStyle(i)"></div>
        </div>
        <div class="loading-text">Initializing Space Portfolio...</div>
        <div class="loading-progress">{{ loadingProgress }}%</div>
      </div>
    </div>

    <!-- Space Scene -->
    <div class="space-scene" ref="sceneContainer">
      <!-- Controls Info -->
      <div class="space-controls-info" v-if="!isLoading && !isMobile">
        <div class="control-item">
          <span class="key">🖱️</span> Click & Drag to Rotate
        </div>
        <div class="control-item">
          <span class="key">🔍</span> Scroll to Zoom
        </div>
        <div class="control-item">
          <span class="key">🪐</span> Click Planets to Explore
        </div>
      </div>

      <!-- Mobile Controls -->
      <div class="mobile-space-controls" v-if="!isLoading && isMobile">
        <div class="touch-instruction">🪐 Tap planets to explore</div>
        <div class="pinch-instruction">🔍 Pinch to zoom</div>
      </div>

      <!-- Astronaut Stats -->
      <div class="astronaut-stats">
        <div class="stat-item">
          <span class="stat-icon">👨‍🚀</span>
          <span class="stat-label">Space Explorer</span>
        </div>
        <div class="stat-item">
          <span class="stat-icon">🌌</span>
          <span class="stat-value">{{ totalExperience }}+ years</span>
        </div>
        <div class="stat-item">
          <span class="stat-icon">🪐</span>
          <span class="stat-value">{{ discoveredPlanets }}/{{ totalPlanets }}</span>
        </div>
      </div>
    </div>

    <!-- Planet Popup -->
    <div class="planet-popup" :class="{ active: showPlanetPopup, 'contact-popup': selectedPlanet.isContact }" v-if="selectedPlanet">
      <div class="popup-space-background">
        <div class="stars-background">
          <div class="popup-star" v-for="i in 30" :key="i" :style="getPopupStarStyle(i)"></div>
        </div>
      </div>

      <div class="popup-content">
        <button class="close-popup" @click="closePlanetPopup">✕</button>

        <div class="planet-header">
          <div class="planet-icon" :style="{ backgroundColor: selectedPlanet.color }">
            {{ selectedPlanet.icon }}
          </div>
          <div class="planet-info">
            <h2>{{ selectedPlanet.name }}</h2>
            <p class="planet-subtitle">{{ selectedPlanet.subtitle }}</p>
            <div class="planet-stats">
              <span class="stat-badge">{{ selectedPlanet.experience }} years</span>
              <span class="stat-badge">{{ selectedPlanet.projects.length }} projects</span>
            </div>
          </div>
        </div>

        <div class="planet-description">
          <p>{{ selectedPlanet.description }}</p>
        </div>

        <div class="tech-stack">
          <h3>🚀 Technologies</h3>
          <div class="tech-tags">
            <span v-for="tech in selectedPlanet.technologies" :key="tech" class="tech-tag">
              {{ tech }}
            </span>
          </div>
        </div>

        <div class="missions" v-if="selectedPlanet.projects.length > 0">
          <h3>🛸 Featured Missions</h3>
          <div class="project-list">
            <div
              v-for="project in selectedPlanet.projects"
              :key="project.id"
              class="mission-card"
              @click="openProjectLink(project.link)"
            >
              <div class="mission-icon">🛰️</div>
              <div class="mission-content">
                <h4>{{ project.name }}</h4>
                <p>{{ project.description }}</p>
                <div class="mission-tech">
                  <span v-for="tech in project.technologies" :key="tech" class="mini-tech-tag">
                    {{ tech }}
                  </span>
                </div>
              </div>
              <div class="mission-link">→</div>
            </div>
          </div>
        </div>

        <div class="navigation-hint">
          <p>🌌 Explore other planets in the solar system</p>
        </div>
      </div>
    </div>

    <!-- Instructions -->
    <div class="space-instructions" v-if="showInstructions" @click="showInstructions = false">
      <div class="instructions-content" @click.stop>
        <div class="instruction-icon">🚀</div>
        <h3>Welcome to My Space Portfolio!</h3>
        <p>Explore the solar system where each planet represents my expertise in different technologies.</p>
        <p>Click on any planet to discover my projects and experience in that domain.</p>
        <button @click="showInstructions = false">Start Space Exploration</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js'

const spaceContainer = ref(null)
const sceneContainer = ref(null)
const isLoading = ref(true)
const loadingProgress = ref(0)
const isMobile = ref(false)
const showInstructions = ref(true)
const showPlanetPopup = ref(false)
const selectedPlanet = ref(null)
const discoveredPlanets = ref(0)
const totalPlanets = ref(0)
const totalExperience = ref(10)

// Three.js variables
let scene, camera, renderer, controls
let planets = []
let stars = []
let suns = []
let asteroids = []
let comets = []
let animationId
const raycaster = new THREE.Raycaster()
const mouse = new THREE.Vector2()

// Celestial bodies data representing different skills/experience
const planetsData = [
  {
    id: 1,
    name: 'Mercury Terminal',
    subtitle: 'Microservices & APIs',
    icon: '🚀',
    color: '#8c8c8c',
    position: { x: 6, y: 0, z: 0 },
    size: 1.2,
    experience: 1,
    description: 'Building microservices and API systems with Node.js and Express. Creating scalable backend architectures with real-time communication and comprehensive testing.',
    technologies: ['Node.js', 'Express', 'GraphQL', 'WebSocket', 'PostgreSQL'],
    projects: [
      {
        id: 1,
        name: 'API Gateway',
        description: 'Microservices API gateway with authentication',
        link: 'https://example.com/api-gateway',
        technologies: ['Node.js', 'JWT', 'Redis']
      }
    ]
  },
  {
    id: 2,
    name: 'Vue.js Nebula',
    subtitle: 'Frontend Development',
    icon: '⚛️',
    color: '#42b883',
    position: { x: 10, y: 0, z: 0 },
    size: 2.5,
    experience: 4,
    description: 'Mastering the reactive universe of Vue.js. Building scalable, performant frontend applications with modern composition API, state management, and ecosystem integration.',
    technologies: ['Vue.js', 'Vuex', 'Pinia', 'Vue Router', 'Nuxt.js', 'Vite'],
    projects: [
      {
        id: 2,
        name: 'E-Commerce Platform',
        description: 'Full-stack e-commerce with real-time inventory',
        link: 'https://example.com/ecommerce',
        technologies: ['Vue.js', 'Laravel', 'MySQL']
      },
      {
        id: 3,
        name: 'Admin Dashboard',
        description: 'Analytics dashboard with real-time data',
        link: 'https://example.com/dashboard',
        technologies: ['Vue.js', 'Chart.js', 'WebSocket']
      }
    ]
  },
  {
    id: 3,
    name: 'Laravel Galaxy',
    subtitle: 'Backend Development',
    icon: '🔧',
    color: '#ff2d20',
    position: { x: 15, y: 0, z: 0 },
    size: 3,
    experience: 5,
    description: 'Architecting robust backend systems with Laravel. Building RESTful APIs, microservices, and enterprise-level applications with elegant code and comprehensive testing.',
    technologies: ['Laravel', 'PHP', 'MySQL', 'Redis', 'Docker', 'API Platform'],
    projects: [
      {
        id: 4,
        name: 'CMS Platform',
        description: 'Enterprise content management system',
        link: 'https://example.com/cms',
        technologies: ['Laravel', 'Vue.js', 'MySQL']
      }
    ]
  },
  {
    id: 4,
    name: 'Dynamo Database Cluster',
    subtitle: 'Database Design',
    icon: '💾',
    color: '#4a90e2',
    position: { x: 20, y: 0, z: 0 },
    size: 2.8,
    experience: 4,
    description: 'Designing and optimizing database architectures for maximum performance. Working with SQL and NoSQL databases, query optimization, and data modeling.',
    technologies: ['PostgreSQL', 'MySQL', 'MongoDB', 'Redis', 'Elasticsearch'],
    projects: [
      {
        id: 5,
        name: 'Data Pipeline',
        description: 'Real-time data processing and analytics',
        link: 'https://example.com/data-pipeline',
        technologies: ['PostgreSQL', 'Python', 'Apache Kafka']
      }
    ]
  },
  {
    id: 5,
    name: 'Flutter Cosmos',
    subtitle: 'Mobile Development',
    icon: '📱',
    color: '#02569b',
    position: { x: 25, y: 0, z: 0 },
    size: 2.2,
    experience: 3,
    description: 'Creating beautiful cross-platform mobile experiences with Flutter. Building native-performance apps with beautiful UIs and seamless backend integration.',
    technologies: ['Flutter', 'Dart', 'Firebase', 'BLoC', 'Provider', 'HTTP'],
    projects: [
      {
        id: 6,
        name: 'Banking App',
        description: 'Secure mobile banking with biometric auth',
        link: 'https://example.com/banking',
        technologies: ['Flutter', 'Node.js', 'PostgreSQL']
      }
    ]
  },
  {
    id: 6,
    name: 'Unity Rings',
    subtitle: 'Game Development',
    icon: '🎮',
    color: '#000000',
    position: { x: 30, y: 0, z: 0 },
    size: 2.8,
    experience: 2,
    description: 'Crafting immersive gaming experiences with Unity. Developing 2D/3D games with physics simulation, multiplayer capabilities, and stunning visual effects.',
    technologies: ['Unity', 'C#', 'Blender', 'Photon', 'ARCore', 'Vuforia'],
    projects: [
      {
        id: 7,
        name: 'AR Puzzle Game',
        description: 'Augmented reality puzzle game',
        link: 'https://example.com/ar-game',
        technologies: ['Unity', 'C#', 'ARCore']
      }
    ]
  },
  {
    id: 7,
    name: 'Saturn Systems',
    subtitle: 'System Architecture',
    icon: '⚙️',
    color: '#f4e7d7',
    position: { x: 40, y: 0, z: 0 },
    size: 2.6,
    experience: 3,
    description: 'Designing complex system architectures and microservices. Building scalable, maintainable systems with proper separation of concerns and enterprise patterns.',
    technologies: ['Docker', 'Kubernetes', 'Nginx', 'Load Balancing', 'CI/CD'],
    projects: [
      {
        id: 8,
        name: 'Microservice Platform',
        description: 'Distributed microservices architecture',
        link: 'https://example.com/microservices',
        technologies: ['Docker', 'Kubernetes', 'gRPC']
      }
    ]
  },
  {
    id: 8,
    name: 'Neptune AI Sphere',
    subtitle: 'AI & Machine Learning',
    icon: '🤖',
    color: '#3776ab',
    position: { x: 50, y: 0, z: 0 },
    size: 2.4,
    experience: 1.5,
    description: 'Exploring the AI universe with Python and machine learning. Building intelligent systems with neural networks, computer vision, and natural language processing.',
    technologies: ['Python', 'TensorFlow', 'PyTorch', 'OpenCV', 'FastAPI', 'Pandas'],
    projects: [
      {
        id: 9,
        name: 'AI Chat System',
        description: 'Intelligent chatbot with NLP',
        link: 'https://example.com/ai-chat',
        technologies: ['Python', 'TensorFlow', 'React']
      }
    ]
  },
  {
    id: 9,
    name: 'Pluto Dwarf Planet',
    subtitle: 'IoT & Embedded Systems',
    icon: '🔌',
    color: '#d4a76a',
    position: { x: 55, y: 0, z: 0 },
    size: 1.5,
    experience: 2,
    description: 'Programming the Internet of Things with embedded systems. Creating smart devices, sensor networks, and edge computing solutions for real-world applications.',
    technologies: ['Arduino', 'Raspberry Pi', 'ESP32', 'MQTT', 'Embedded C/C++', 'Node-RED'],
    projects: [
      {
        id: 10,
        name: 'Smart Home System',
        description: 'IoT home automation with voice control',
        link: 'https://example.com/smart-home',
        technologies: ['Raspberry Pi', 'Python', 'MQTT']
      }
    ]
  },
  {
    id: 10,
    name: 'Cygnus Cloud Cluster',
    subtitle: 'DevOps & Cloud',
    icon: '☁️',
    color: '#ff9900',
    position: { x: 60, y: 0, z: 0 },
    size: 2.3,
    experience: 3,
    description: 'Navigating the cloud infrastructure with AWS and major cloud providers. Building scalable, reliable deployment pipelines with CI/CD automation and comprehensive monitoring.',
    technologies: ['AWS', 'Azure', 'Google Cloud', 'Terraform', 'Monitoring', 'Serverless'],
    projects: [
      {
        id: 11,
        name: 'Cloud Infrastructure',
        description: 'Multi-cloud deployment system',
        link: 'https://example.com/cloud-infra',
        technologies: ['AWS', 'Docker', 'Kubernetes']
      }
    ]
  }
]

// Sun data
const sunData = {
  name: 'Sol Central Star',
  subtitle: 'Core System Architecture',
  icon: '☀️',
  position: { x: 0, y: 0, z: 0 },
  size: 5,
  description: 'The heart of your technical universe. Core system architecture, fundamental principles, and cross-platform expertise that power all other systems.'
}

// Comet data
const cometsData = [
  {
    id: 1,
    name: 'Halley\'s Tech Comet',
    position: { x: -70, y: 10, z: 20 },
    orbitSpeed: 0.001,
    orbitRadius: 70,
    tailLength: 100
  },
  {
    id: 2,
    name: 'Swift Innovation Comet',
    position: { x: 70, y: -5, z: -30 },
    orbitSpeed: 0.0015,
    orbitRadius: 60,
    tailLength: 80
  }
]

// Check if mobile
const checkMobile = () => {
  isMobile.value = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768
}

onMounted(() => {
  checkMobile()
  totalPlanets.value = planetsData.length
  if (spaceContainer.value && sceneContainer.value) {
    initSpaceScene()
  }
})

onBeforeUnmount(() => {
  cleanup()
})

const initSpaceScene = () => {
  // Scene setup
  scene = new THREE.Scene()
  scene.background = new THREE.Color(0x0a0a0a) // Deep space color - updated to match theme

  // Camera setup
  const aspect = sceneContainer.value.clientWidth / sceneContainer.value.clientHeight
  camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000)
  camera.position.set(0, 15, 35)

  // Renderer setup
  renderer = new THREE.WebGLRenderer({
    antialias: true,
    alpha: true
  })
  renderer.setSize(sceneContainer.value.clientWidth, sceneContainer.value.clientHeight)
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
  renderer.shadowMap.enabled = true
  renderer.shadowMap.type = THREE.PCFSoftShadowMap
  renderer.toneMapping = THREE.ACESFilmicToneMapping
  renderer.toneMappingExposure = 1.2
  sceneContainer.value.appendChild(renderer.domElement)

  // Controls
  controls = new OrbitControls(camera, renderer.domElement)
  controls.enableDamping = true
  controls.dampingFactor = 0.05
  controls.maxDistance = 80
  controls.minDistance = 15
  controls.autoRotate = true
  controls.autoRotateSpeed = 0.3

  // Enhanced space lighting
  setupSpaceLighting()

  // Create space environment
  createStars()
  createSun()
  createOrbitalRings()
  createPlanets()
  createAsteroidBelt()
  createComets()
  createNebula()

  // Event listeners
  window.addEventListener('resize', onWindowResize)
  renderer.domElement.addEventListener('click', onMouseClick)
  renderer.domElement.addEventListener('mousemove', onMouseMove)

  // Start animation
  animate()

  // Simulate loading
  const loadingInterval = setInterval(() => {
    loadingProgress.value += 10
    if (loadingProgress.value >= 100) {
      clearInterval(loadingInterval)
      setTimeout(() => {
        isLoading.value = false
      }, 500)
    }
  }, 100)
}

const setupSpaceLighting = () => {
  // Main sun light (distant star)
  const sunLight = new THREE.DirectionalLight(0xffffff, 2.5)
  sunLight.position.set(50, 50, 50)
  sunLight.castShadow = true
  sunLight.shadow.camera.left = -50
  sunLight.shadow.camera.right = 50
  sunLight.shadow.camera.top = 50
  sunLight.shadow.camera.bottom = -20
  sunLight.shadow.mapSize.width = 2048
  sunLight.shadow.mapSize.height = 2048
  scene.add(sunLight)

  // Secondary star light (for fill)
  const starLight2 = new THREE.DirectionalLight(0x88ccff, 1.5)
  starLight2.position.set(-30, 30, -20)
  scene.add(starLight2)

  // Ambient space lighting
  const ambientLight = new THREE.AmbientLight(0x404080, 0.8)
  scene.add(ambientLight)

  // Add point lights for each planet (representing reflected starlight)
  planetsData.forEach((planetData, index) => {
    const planetLight = new THREE.PointLight(planetData.color, 0.5, planetData.size * 3)
    planetLight.position.set(planetData.position.x, planetData.position.y, planetData.position.z)
    scene.add(planetLight)
  })

  // Add a subtle blue fill light from the "north"
  const fillLight = new THREE.DirectionalLight(0x0066cc, 0.8)
  fillLight.position.set(0, 10, -30)
  scene.add(fillLight)

  // Add warm fill light from the "south"
  const warmFillLight = new THREE.DirectionalLight(0xff9966, 0.6)
  warmFillLight.position.set(0, 10, 30)
  scene.add(warmFillLight)
}

const createStars = () => {
  const starsGeometry = new THREE.BufferGeometry()
  const starsMaterial = new THREE.PointsMaterial({
    color: 0xffffff,
    size: 0.1,
    transparent: true,
    opacity: 0.8
  })

  const starsVertices = []
  for (let i = 0; i < 10000; i++) {
    const x = (Math.random() - 0.5) * 200
    const y = (Math.random() - 0.5) * 200
    const z = (Math.random() - 0.5) * 200
    starsVertices.push(x, y, z)
  }

  starsGeometry.setAttribute('position', new THREE.Float32BufferAttribute(starsVertices, 3))
  const starField = new THREE.Points(starsGeometry, starsMaterial)
  scene.add(starField)
  stars.push(starField)
}

const createPlanets = () => {
  planetsData.forEach((planetData, index) => {
    const planetGroup = new THREE.Group()

    // Create planet with realistic surface
    const planetGeometry = new THREE.SphereGeometry(planetData.size, 64, 64)

    // Create realistic material based on planet type
    const planetMaterial = createRealisticPlanetMaterial(planetData, index)

    const planet = new THREE.Mesh(planetGeometry, planetMaterial)
    planet.userData = planetData
    planetGroup.add(planet)

    // Add surface details (terrain features)
    addPlanetSurfaceDetails(planet, planetData, index)

    // Add realistic atmosphere
    addPlanetAtmosphere(planetGroup, planetData, planetData.size)

    // Add rings for specific planets (like Saturn)
    if (index === 0 || index === 2) { // Vue.js and Flutter get rings
      addPlanetRings(planetGroup, planetData, planetData.size)
    }

    // Add moons for some planets
    if (index === 1 || index === 4) { // Laravel and Python get moons
      addPlanetMoons(planetGroup, planetData, planetData.size)
    }

    // Add planet-specific features
    addPlanetSpecificFeatures(planetGroup, planetData, index)

    // Position planet in orbital ring around sun
    const orbitRadius = 8 + (index * 4) // Increasing orbital radius
    const orbitAngle = (index / planetsData.length) * Math.PI * 2 // Spread evenly around circle
    const height = (Math.random() - 0.5) * 1 // Slight vertical variation

    planetGroup.position.set(
      Math.cos(orbitAngle) * orbitRadius,
      height,
      Math.sin(orbitAngle) * orbitRadius
    )

    // Store orbital data for animation
    planetGroup.userData = {
      planet: planet,
      data: planetData,
      orbitRadius: orbitRadius,
      orbitAngle: orbitAngle,
      orbitSpeed: 0.001 + (Math.random() * 0.002), // Variable orbital speeds
      height: height,
      rotationSpeed: 0.005 + Math.random() * 0.01 // Planet rotation speed
    }

    scene.add(planetGroup)
    planets.push(planetGroup)
  })
}

const createRealisticPlanetMaterial = (planetData, index) => {
  // Create procedurally generated textures for realistic planet surfaces
  const canvas = document.createElement('canvas')
  canvas.width = 512
  canvas.height = 256
  const context = canvas.getContext('2d')

  // Generate different surface patterns based on planet type
  switch(index) {
    case 0: // Vue.js Nebula - Earth-like with continents
      generateEarthLikeTexture(context, planetData.color)
      break
    case 1: // Laravel Galaxy - Mars-like with craters
      generateMarsLikeTexture(context, planetData.color)
      break
    case 2: // Flutter Cosmos - Jupiter-like with bands
      generateGasGiantTexture(context, planetData.color)
      break
    case 3: // Unity Stars - Rocky/moon-like
      generateMoonLikeTexture(context, planetData.color)
      break
    case 4: // Python Asteroid Belt - Ice planet
      generateIcePlanetTexture(context, planetData.color)
      break
    case 5: // Cloud Constellation - Desert planet
      generateDesertPlanetTexture(context, planetData.color)
      break
    case 6: // Mercury Terminal - Metallic with hexagonal patterns
      generateMetallicTexture(context, planetData.color)
      break
    case 7: // Dynamo Database Cluster - Crystal-like formations
      generateCrystalTexture(context, planetData.color)
      break
    case 8: // Saturn Systems - Ringed gas giant with storms
      generateGasGiantTexture(context, planetData.color)
      break
    case 9: // Neptune AI Sphere - Ethereal with neural patterns
      generateNeuralTexture(context, planetData.color)
      break
  }

  const texture = new THREE.CanvasTexture(canvas)
  texture.needsUpdate = true

  // Create realistic material with the generated texture
  const material = new THREE.MeshPhongMaterial({
    map: texture,
    bumpMap: generateBumpMap(context, index),
    bumpScale: 0.05,
    specularMap: generateSpecularMap(context, index),
    shininess: 10 + Math.random() * 20,
    emissive: planetData.color,
    emissiveIntensity: 0.02
  })

  return material
}

const generateEarthLikeTexture = (context, baseColor) => {
  // Base ocean color
  context.fillStyle = '#0066cc'
  context.fillRect(0, 0, 512, 256)

  // Add continents using noise-like patterns
  for (let i = 0; i < 8; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 30 + Math.random() * 50

    const gradient = context.createRadialGradient(x, y, 0, x, y, radius)
    gradient.addColorStop(0, '#228b22')
    gradient.addColorStop(0.7, '#32cd32')
    gradient.addColorStop(1, '#006400')

    context.fillStyle = gradient
    context.fillRect(x - radius, y - radius, radius * 2, radius * 2)
  }

  // Add cloud layers
  context.fillStyle = 'rgba(255, 255, 255, 0.3)'
  for (let i = 0; i < 15; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 10 + Math.random() * 25

    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()
  }
}

const generateMarsLikeTexture = (context, baseColor) => {
  // Base Mars color
  context.fillStyle = '#cd5c5c'
  context.fillRect(0, 0, 512, 256)

  // Add darker regions
  for (let i = 0; i < 12; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const size = 20 + Math.random() * 40

    context.fillStyle = '#8b4513'
    context.fillRect(x, y, size, size * 0.6)
  }

  // Add craters
  for (let i = 0; i < 20; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 5 + Math.random() * 15

    context.fillStyle = 'rgba(139, 69, 19, 0.5)'
    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()

    // Crater rim
    context.strokeStyle = '#a0522d'
    context.lineWidth = 2
    context.stroke()
  }

  // Add dust storms
  context.fillStyle = 'rgba(205, 92, 92, 0.2)'
  for (let i = 0; i < 8; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 20 + Math.random() * 40

    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()
  }
}

const generateGasGiantTexture = (context, baseColor) => {
  // Create banded atmosphere
  const colors = ['#ff9900', '#ffcc00', '#ff6600', '#ffaa00', '#ff7700']

  for (let i = 0; i < colors.length; i++) {
    context.fillStyle = colors[i]
    context.fillRect(0, i * (256 / colors.length), 512, 256 / colors.length)
  }

  // Add storm spots (like Jupiter's Great Red Spot)
  for (let i = 0; i < 3; i++) {
    const x = 100 + Math.random() * 312
    const y = 50 + Math.random() * 156
    const radius = 20 + Math.random() * 30

    const gradient = context.createRadialGradient(x, y, 0, x, y, radius)
    gradient.addColorStop(0, '#ff0000')
    gradient.addColorStop(0.5, '#cc0000')
    gradient.addColorStop(1, '#990000')

    context.fillStyle = gradient
    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()
  }
}

const generateMoonLikeTexture = (context, baseColor) => {
  // Base moon color
  context.fillStyle = '#c0c0c0'
  context.fillRect(0, 0, 512, 256)

  // Add craters
  for (let i = 0; i < 30; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 3 + Math.random() * 12

    // Crater shadow
    const gradient = context.createRadialGradient(x, y, 0, x, y, radius)
    gradient.addColorStop(0, '#808080')
    gradient.addColorStop(0.7, '#a0a0a0')
    gradient.addColorStop(1, '#c0c0c0')

    context.fillStyle = gradient
    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()
  }

  // Add mare (dark regions)
  for (let i = 0; i < 5; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const width = 50 + Math.random() * 100
    const height = 30 + Math.random() * 60

    context.fillStyle = 'rgba(96, 96, 96, 0.5)'
    context.ellipse(x, y, width/2, height/2, Math.random() * Math.PI, 0, Math.PI * 2)
    context.fill()
  }
}

const generateIcePlanetTexture = (context, baseColor) => {
  // Ice base color
  context.fillStyle = '#e0f7ff'
  context.fillRect(0, 0, 512, 256)

  // Add ice cracks
  context.strokeStyle = '#b3d9ff'
  context.lineWidth = 2
  for (let i = 0; i < 15; i++) {
    context.beginPath()
    context.moveTo(Math.random() * 512, Math.random() * 256)

    for (let j = 0; j < 3; j++) {
      context.lineTo(Math.random() * 512, Math.random() * 256)
    }

    context.stroke()
  }

  // Add frozen regions
  for (let i = 0; i < 10; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 15 + Math.random() * 35

    const gradient = context.createRadialGradient(x, y, 0, x, y, radius)
    gradient.addColorStop(0, 'rgba(255, 255, 255, 0.8)')
    gradient.addColorStop(1, 'rgba(224, 247, 255, 0.4)')

    context.fillStyle = gradient
    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()
  }
}

const generateDesertPlanetTexture = (context, baseColor) => {
  // Desert sand base
  context.fillStyle = '#daa520'
  context.fillRect(0, 0, 512, 256)

  // Add dunes
  for (let i = 0; i < 8; i++) {
    const y = i * 32
    const gradient = context.createLinearGradient(0, y, 512, y + 20)
    gradient.addColorStop(0, '#cd853f')
    gradient.addColorStop(0.5, '#daa520')
    gradient.addColorStop(1, '#d2691e')

    context.fillStyle = gradient
    context.fillRect(0, y, 512, 25)
  }

  // Add rocky outcrops
  for (let i = 0; i < 12; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const size = 10 + Math.random() * 20

    context.fillStyle = '#8b7355'
    context.fillRect(x, y, size, size * 0.7)
  }

  // Add canyon
  context.strokeStyle = '#a0522d'
  context.lineWidth = 8
  context.beginPath()
  context.moveTo(100, 50)
  context.quadraticCurveTo(256, 100, 400, 200)
  context.stroke()
}

const generateMetallicTexture = (context, baseColor) => {
  // Metallic base color
  context.fillStyle = '#c0c0c0'
  context.fillRect(0, 0, 512, 256)

  // Add hexagonal grid pattern
  const hexSize = 15
  const hexHeight = hexSize * 2
  const hexWidth = Math.sqrt(3) * hexSize

  for (let row = 0; row < 256 / hexHeight + 1; row++) {
    for (let col = 0; col < 512 / hexWidth + 1; col++) {
      const x = col * hexWidth + (row % 2) * (hexWidth / 2)
      const y = row * hexHeight * 0.75

      // Draw hexagon
      context.strokeStyle = 'rgba(169, 169, 169, 0.5)'
      context.lineWidth = 1
      context.beginPath()
      for (let i = 0; i < 6; i++) {
        const angle = (Math.PI / 3) * i
        const hx = x + hexSize * Math.cos(angle)
        const hy = y + hexSize * Math.sin(angle)
        if (i === 0) {
          context.moveTo(hx, hy)
        } else {
          context.lineTo(hx, hy)
        }
      }
      context.closePath()
      context.stroke()
    }
  }

  // Add metallic sheen
  for (let i = 0; i < 20; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 10 + Math.random() * 30

    const gradient = context.createRadialGradient(x, y, 0, x, y, radius)
    gradient.addColorStop(0, 'rgba(255, 255, 255, 0.6)')
    gradient.addColorStop(0.5, 'rgba(192, 192, 192, 0.3)')
    gradient.addColorStop(1, 'rgba(128, 128, 128, 0)')

    context.fillStyle = gradient
    context.beginPath()
    context.arc(x, y, radius, 0, Math.PI * 2)
    context.fill()
  }
}

const generateCrystalTexture = (context, baseColor) => {
  // Dark crystal base
  context.fillStyle = '#1a1a2e'
  context.fillRect(0, 0, 512, 256)

  // Add crystal formations
  for (let i = 0; i < 40; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const size = 5 + Math.random() * 20

    // Crystal clusters
    const gradient = context.createRadialGradient(x, y, 0, x, y, size)
    const colors = [
      [0, 255, 255],   // Cyan
      [147, 112, 219], // Medium Purple
      [255, 182, 193]  // Light Pink
    ]
    const color = colors[Math.floor(Math.random() * colors.length)]

    gradient.addColorStop(0, `rgba(${color[0]}, ${color[1]}, ${color[2]}, 0.9)`)
    gradient.addColorStop(0.5, `rgba(${color[0]}, ${color[1]}, ${color[2]}, 0.5)`)
    gradient.addColorStop(1, `rgba(${color[0]}, ${color[1]}, ${color[2]}, 0)`)

    context.fillStyle = gradient
    context.beginPath()
    context.arc(x, y, size, 0, Math.PI * 2)
    context.fill()
  }

  // Add crystalline lines
  for (let i = 0; i < 25; i++) {
    const x1 = Math.random() * 512
    const y1 = Math.random() * 256
    const x2 = x1 + (Math.random() - 0.5) * 100
    const y2 = y1 + (Math.random() - 0.5) * 50

    context.strokeStyle = `rgba(${100 + Math.random() * 155}, ${100 + Math.random() * 155}, 255, 0.4)`
    context.lineWidth = 1
    context.beginPath()
    context.moveTo(x1, y1)
    context.lineTo(x2, y2)
    context.stroke()
  }
}

const generateNeuralTexture = (context, baseColor) => {
  // Ethereal dark blue base
  context.fillStyle = '#0a0a2e'
  context.fillRect(0, 0, 512, 256)

  // Add neural network patterns
  for (let i = 0; i < 30; i++) {
    const centerX = Math.random() * 512
    const centerY = Math.random() * 256
    const connections = 3 + Math.floor(Math.random() * 5)

    // Neural nodes
    const nodeGradient = context.createRadialGradient(centerX, centerY, 0, centerX, centerY, 8)
    nodeGradient.addColorStop(0, 'rgba(100, 200, 255, 0.9)')
    nodeGradient.addColorStop(0.5, 'rgba(50, 150, 255, 0.6)')
    nodeGradient.addColorStop(1, 'rgba(0, 100, 255, 0)')

    context.fillStyle = nodeGradient
    context.beginPath()
    context.arc(centerX, centerY, 8, 0, Math.PI * 2)
    context.fill()

    // Neural connections
    for (let j = 0; j < connections; j++) {
      const targetX = Math.random() * 512
      const targetY = Math.random() * 256

      context.strokeStyle = `rgba(100, 200, 255, ${0.2 + Math.random() * 0.4})`
      context.lineWidth = 1 + Math.random() * 2
      context.beginPath()
      context.moveTo(centerX, centerY)
      context.lineTo(targetX, targetY)
      context.stroke()
    }
  }

  // Add energy pulses
  for (let i = 0; i < 15; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const pulseGradient = context.createRadialGradient(x, y, 0, x, y, 20)

    pulseGradient.addColorStop(0, 'rgba(255, 255, 255, 0.4)')
    pulseGradient.addColorStop(0.5, 'rgba(100, 200, 255, 0.2)')
    pulseGradient.addColorStop(1, 'rgba(0, 100, 255, 0)')

    context.fillStyle = pulseGradient
    context.beginPath()
    context.arc(x, y, 20, 0, Math.PI * 2)
    context.fill()
  }
}

const generateBumpMap = (context, index) => {
  const canvas = document.createElement('canvas')
  canvas.width = 512
  canvas.height = 256
  const bumpContext = canvas.getContext('2d')

  // Generate bump map based on planet type
  const intensity = 0.3 + Math.random() * 0.4
  bumpContext.fillStyle = `rgb(${128 + Math.random() * 127}, ${128 + Math.random() * 127}, ${128 + Math.random() * 127})`
  bumpContext.fillRect(0, 0, 512, 256)

  // Add noise for texture
  for (let i = 0; i < 100; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = Math.random() * 10
    const brightness = Math.random() * 255

    bumpContext.fillStyle = `rgb(${brightness}, ${brightness}, ${brightness})`
    bumpContext.beginPath()
    bumpContext.arc(x, y, radius, 0, Math.PI * 2)
    bumpContext.fill()
  }

  return new THREE.CanvasTexture(canvas)
}

const generateSpecularMap = (context, index) => {
  const canvas = document.createElement('canvas')
  canvas.width = 512
  canvas.height = 256
  const specContext = canvas.getContext('2d')

  // Generate specular map (reflection areas)
  specContext.fillStyle = 'rgb(50, 50, 50)'
  specContext.fillRect(0, 0, 512, 256)

  // Add reflective areas (ice, water, metal surfaces)
  const reflectionAreas = index === 0 ? 20 : index === 4 ? 30 : 10
  for (let i = 0; i < reflectionAreas; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 256
    const radius = 5 + Math.random() * 20

    const gradient = specContext.createRadialGradient(x, y, 0, x, y, radius)
    gradient.addColorStop(0, 'rgb(200, 200, 200)')
    gradient.addColorStop(1, 'rgb(100, 100, 100)')

    specContext.fillStyle = gradient
    specContext.beginPath()
    specContext.arc(x, y, radius, 0, Math.PI * 2)
    specContext.fill()
  }

  return new THREE.CanvasTexture(canvas)
}

const addPlanetSurfaceDetails = (planet, planetData, index) => {
  // Add additional surface features based on planet type
  if (index === 3) { // Unity Stars - add mountain ridges
    const mountainGeometry = new THREE.ConeGeometry(0.3, 0.8, 8, 1, false, 0, Math.PI * 2)
    const mountainMaterial = new THREE.MeshPhongMaterial({
      color: 0x8b7355,
      shininess: 5
    })

    for (let i = 0; i < 5; i++) {
      const mountain = new THREE.Mesh(mountainGeometry, mountainMaterial)
      const theta = (i / 5) * Math.PI * 2
      const phi = Math.random() * Math.PI
      mountain.position.setFromSphericalCoords(planetData.size * 1.01, phi, theta)
      mountain.lookAt(0, 0, 0)
      planet.add(mountain)
    }
  }
}

const addPlanetAtmosphere = (planetGroup, planetData, size) => {
  // Multi-layered atmosphere for realism
  const atmosphereLayers = [
    { scale: 1.15, opacity: 0.05, color: planetData.color },
    { scale: 1.25, opacity: 0.03, color: planetData.color },
    { scale: 1.35, opacity: 0.01, color: 0xffffff }
  ]

  atmosphereLayers.forEach((layer, index) => {
    const atmosphereGeometry = new THREE.SphereGeometry(size * layer.scale, 32, 32)
    const atmosphereMaterial = new THREE.MeshBasicMaterial({
      color: layer.color,
      transparent: true,
      opacity: layer.opacity,
      side: THREE.BackSide,
      blending: THREE.AdditiveBlending
    })

    const atmosphere = new THREE.Mesh(atmosphereGeometry, atmosphereMaterial)
    planetGroup.add(atmosphere)
  })
}

const addPlanetRings = (planetGroup, planetData, size) => {
  // Realistic ring system
  const ringGeometry = new THREE.RingGeometry(size * 1.4, size * 2.2, 64)

  // Create gradient material for rings
  const canvas = document.createElement('canvas')
  canvas.width = 512
  canvas.height = 512
  const context = canvas.getContext('2d')

  // Create ring gradient
  const gradient = context.createRadialGradient(256, 256, 0, 256, 256, 256)
  gradient.addColorStop(0, 'rgba(255, 255, 255, 0.8)')
  gradient.addColorStop(0.3, planetData.color)
  gradient.addColorStop(0.7, planetData.color + '80')
  gradient.addColorStop(1, 'rgba(255, 255, 255, 0.1)')

  context.fillStyle = gradient
  context.fillRect(0, 0, 512, 512)

  // Add ring particles
  for (let i = 0; i < 200; i++) {
    const x = Math.random() * 512
    const y = Math.random() * 512
    const size = Math.random() * 3

    context.fillStyle = 'rgba(255, 255, 255, 0.6)'
    context.fillRect(x, y, size, size)
  }

  const ringTexture = new THREE.CanvasTexture(canvas)
  ringTexture.needsUpdate = true

  const ringMaterial = new THREE.MeshBasicMaterial({
    map: ringTexture,
    transparent: true,
    opacity: 0.8,
    side: THREE.DoubleSide,
    blending: THREE.AdditiveBlending
  })

  const ring = new THREE.Mesh(ringGeometry, ringMaterial)
  ring.rotation.x = Math.PI / 2 + (Math.random() - 0.5) * 0.2
  ring.rotation.z = Math.random() * Math.PI * 2
  planetGroup.add(ring)

  // Add multiple ring layers for complexity
  for (let i = 0; i < 2; i++) {
    const innerRing = new THREE.RingGeometry(
      size * (1.8 + i * 0.2),
      size * (2.0 + i * 0.2),
      32
    )
    const innerRingMaterial = new THREE.MeshBasicMaterial({
      color: planetData.color,
      transparent: true,
      opacity: 0.3 - i * 0.1,
      side: THREE.DoubleSide
    })
    const innerRingMesh = new THREE.Mesh(innerRing, innerRingMaterial)
    innerRingMesh.rotation.x = Math.PI / 2
    planetGroup.add(innerRingMesh)
  }
}

const addPlanetMoons = (planetGroup, planetData, planetSize) => {
  const moonCount = 1 + Math.floor(Math.random() * 2)

  for (let i = 0; i < moonCount; i++) {
    const moonSize = planetSize * (0.1 + Math.random() * 0.15)
    const moonGeometry = new THREE.SphereGeometry(moonSize, 16, 16)
    const moonMaterial = new THREE.MeshPhongMaterial({
      color: 0xcccccc,
      emissive: 0x444444,
      emissiveIntensity: 0.1,
      shininess: 5
    })

    const moon = new THREE.Mesh(moonGeometry, moonMaterial)

    // Position moon in orbit
    const orbitRadius = planetSize * (2.5 + i * 0.8)
    const moonOrbit = new THREE.Object3D()
    moonOrbit.add(moon)
    moon.position.x = orbitRadius

    // Add some rotation to the moon orbit
    moonOrbit.rotation.x = Math.random() * 0.5
    moonOrbit.rotation.z = Math.random() * Math.PI

    planetGroup.add(moonOrbit)

    // Store reference for animation
    moon.userData = { orbit: moonOrbit, speed: 0.001 + Math.random() * 0.003 }
  }
}

const addPlanetSpecificFeatures = (planetGroup, planetData, index) => {
  switch(index) {
    case 0: // Vue.js Nebula - add city lights
      addCityLights(planetGroup, planetData)
      break
    case 2: // Flutter Cosmos - add storm systems
      addStormSystems(planetGroup, planetData)
      break
    case 5: // Cloud Constellation - add sandstorms
      addSandstorms(planetGroup, planetData)
      break
  }
}

const addCityLights = (planetGroup, planetData) => {
  // Add glowing city lights on the dark side
  const lightsGeometry = new THREE.BufferGeometry()
  const lightsPositions = []

  for (let i = 0; i < 50; i++) {
    const theta = Math.random() * Math.PI * 2
    const phi = Math.acos(Math.random() * 2 - 1)
    const radius = planetData.size * 1.01

    const x = radius * Math.sin(phi) * Math.cos(theta)
    const y = radius * Math.sin(phi) * Math.sin(theta)
    const z = radius * Math.cos(phi)

    lightsPositions.push(x, y, z)
  }

  lightsGeometry.setAttribute('position', new THREE.Float32BufferAttribute(lightsPositions, 3))

  const lightsMaterial = new THREE.PointsMaterial({
    color: 0xffff00,
    size: 0.1,
    transparent: true,
    opacity: 0.8,
    blending: THREE.AdditiveBlending
  })

  const cityLights = new THREE.Points(lightsGeometry, lightsMaterial)
  planetGroup.add(cityLights)
}

const addStormSystems = (planetGroup, planetData) => {
  // Add rotating storm systems
  for (let i = 0; i < 2; i++) {
    const stormGeometry = new THREE.SphereGeometry(planetData.size * 0.3, 16, 16)
    const stormMaterial = new THREE.MeshBasicMaterial({
      color: 0xff6600,
      transparent: true,
      opacity: 0.3,
      blending: THREE.AdditiveBlending
    })

    const storm = new THREE.Mesh(stormGeometry, stormMaterial)
    const theta = Math.random() * Math.PI * 2
    const phi = (Math.random() - 0.5) * Math.PI
    storm.position.setFromSphericalCoords(planetData.size * 0.8, phi, theta)

    storm.userData = { rotationSpeed: 0.01 + Math.random() * 0.02 }
    planetGroup.add(storm)
  }
}

const addSandstorms = (planetGroup, planetData) => {
  // Add sandstorm particles
  const sandGeometry = new THREE.BufferGeometry()
  const sandPositions = []

  for (let i = 0; i < 30; i++) {
    const theta = Math.random() * Math.PI * 2
    const radius = planetData.size * (1.1 + Math.random() * 0.2)
    const height = (Math.random() - 0.5) * planetData.size * 0.3

    sandPositions.push(
      radius * Math.cos(theta),
      height,
      radius * Math.sin(theta)
    )
  }

  sandGeometry.setAttribute('position', new THREE.Float32BufferAttribute(sandPositions, 3))

  const sandMaterial = new THREE.PointsMaterial({
    color: 0xdaa520,
    size: 0.15,
    transparent: true,
    opacity: 0.6,
    blending: THREE.AdditiveBlending
  })

  const sandstorm = new THREE.Points(sandGeometry, sandMaterial)
  planetGroup.add(sandstorm)
}

const createNebula = () => {
  // Create nebula effect with particles
  const nebulaGeometry = new THREE.BufferGeometry()
  const nebulaMaterial = new THREE.PointsMaterial({
    color: 0x4169e1,
    size: 0.3,
    transparent: true,
    opacity: 0.1,
    blending: THREE.AdditiveBlending
  })

  const nebulaVertices = []
  for (let i = 0; i < 1000; i++) {
    const x = (Math.random() - 0.5) * 100
    const y = (Math.random() - 0.5) * 20
    const z = (Math.random() - 0.5) * 100
    nebulaVertices.push(x, y, z)
  }

  nebulaGeometry.setAttribute('position', new THREE.Float32BufferAttribute(nebulaVertices, 3))
  const nebula = new THREE.Points(nebulaGeometry, nebulaMaterial)
  scene.add(nebula)
}

const onMouseClick = (event) => {
  const rect = renderer.domElement.getBoundingClientRect()
  mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
  mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1

  raycaster.setFromCamera(mouse, camera)

  // Check for sun clicks first
  const sunIntersects = raycaster.intersectObjects(suns)
  if (sunIntersects.length > 0) {
    selectSun(sunIntersects[0].object.userData)
    return
  }

  // Then check for planet clicks
  const intersects = raycaster.intersectObjects(planets.map(p => p.userData.planet))

  if (intersects.length > 0) {
    const clickedPlanet = intersects[0].object.userData
    selectPlanet(clickedPlanet)
  }
}

const onMouseMove = (event) => {
  const rect = renderer.domElement.getBoundingClientRect()
  mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
  mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1

  raycaster.setFromCamera(mouse, camera)

  // Check for sun hover first
  const sunIntersects = raycaster.intersectObjects(suns)
  if (sunIntersects.length > 0) {
    renderer.domElement.style.cursor = 'pointer'
    return
  }

  // Then check for planet hovers
  const intersects = raycaster.intersectObjects(planets.map(p => p.userData.planet))

  // Reset all planet scales
  planets.forEach(planetGroup => {
    const planet = planetGroup.userData.planet
    planet.scale.setScalar(1)
    renderer.domElement.style.cursor = 'default'
  })

  // Highlight hovered planet
  if (intersects.length > 0) {
    const hoveredPlanet = intersects[0].object
    hoveredPlanet.scale.setScalar(1.1)
    renderer.domElement.style.cursor = 'pointer'
  }
}

const selectPlanet = (planetData) => {
  selectedPlanet.value = planetData
  showPlanetPopup.value = true

  // Mark planet as discovered
  if (!planetData.discovered) {
    planetData.discovered = true
    discoveredPlanets.value++
  }

  // Stop auto rotation when popup is open
  controls.autoRotate = false
}

const closePlanetPopup = () => {
  showPlanetPopup.value = false
  selectedPlanet.value = null
  controls.autoRotate = true
}

const selectSun = (sunData) => {
  selectedPlanet.value = {
    ...sunData,
    name: 'Contact Solar Core',
    subtitle: 'Get In Touch',
    icon: '☀️',
    color: '#ffd700',
    description: 'Reach out to discuss your next project or collaboration opportunity.',
    technologies: ['Email', 'LinkedIn', 'GitHub', 'Portfolio', 'Remote Available'],
    projects: [
      {
        id: 'contact-1',
        name: '📧 Email Contact',
        description: 'Direct email for project inquiries and collaborations',
        link: 'mailto:ganesh@example.com',
        technologies: ['Professional', 'Quick Response']
      },
      {
        id: 'contact-2',
        name: '💼 LinkedIn Profile',
        description: 'Professional network and detailed work history',
        link: 'https://linkedin.com/in/ganeshkp',
        technologies: ['Career History', 'Recommendations']
      },
      {
        id: 'contact-3',
        name: '🐙 GitHub Portfolio',
        description: 'Code repositories and open source contributions',
        link: 'https://github.com/ganeshkgp',
        technologies: ['Source Code', 'Projects', 'Contributions']
      },
      {
        id: 'contact-4',
        name: '🌐 Personal Website',
        description: 'Complete portfolio and project showcase',
        link: 'https://ganeshkp.com',
        technologies: ['Projects', 'Case Studies', 'Testimonials']
      }
    ],
    experience: 10,
    isContact: true
  }
  showPlanetPopup.value = true
  controls.autoRotate = false
}

const openProjectLink = (link) => {
  if (link) {
    window.open(link, '_blank')
  }
}

const createSun = () => {
  // Create the main sun sphere with realistic solar texture
  const sunGeometry = new THREE.SphereGeometry(sunData.size, 64, 64)

  // Create procedural sun texture
  const sunCanvas = document.createElement('canvas')
  sunCanvas.width = 1024
  sunCanvas.height = 512
  const sunContext = sunCanvas.getContext('2d')

  // Base sun color with gradient
  const sunGradient = sunContext.createRadialGradient(512, 256, 0, 512, 256, 256)
  sunGradient.addColorStop(0, '#fff5ee')
  sunGradient.addColorStop(0.3, '#ffeb3b')
  sunGradient.addColorStop(0.6, '#ffc107')
  sunGradient.addColorStop(0.8, '#ff9800')
  sunGradient.addColorStop(1, '#ff5722')

  sunContext.fillStyle = sunGradient
  sunContext.fillRect(0, 0, 1024, 512)

  // Add sunspots and surface detail
  for (let i = 0; i < 25; i++) {
    const x = Math.random() * 1024
    const y = Math.random() * 512
    const size = 5 + Math.random() * 20

    // Dark sunspots
    const spotGradient = sunContext.createRadialGradient(x, y, 0, x, y, size)
    spotGradient.addColorStop(0, 'rgba(139, 69, 19, 0.8)')
    spotGradient.addColorStop(0.5, 'rgba(160, 82, 45, 0.5)')
    spotGradient.addColorStop(1, 'rgba(255, 140, 0, 0)')

    sunContext.fillStyle = spotGradient
    sunContext.beginPath()
    sunContext.arc(x, y, size, 0, Math.PI * 2)
    sunContext.fill()
  }

  // Add bright surface features
  for (let i = 0; i < 30; i++) {
    const x = Math.random() * 1024
    const y = Math.random() * 512
    const size = 3 + Math.random() * 15

    const brightGradient = sunContext.createRadialGradient(x, y, 0, x, y, size)
    brightGradient.addColorStop(0, 'rgba(255, 255, 255, 0.9)')
    brightGradient.addColorStop(0.5, 'rgba(255, 255, 224, 0.6)')
    brightGradient.addColorStop(1, 'rgba(255, 235, 59, 0)')

    sunContext.fillStyle = brightGradient
    sunContext.beginPath()
    sunContext.arc(x, y, size, 0, Math.PI * 2)
    sunContext.fill()
  }

  const sunTexture = new THREE.CanvasTexture(sunCanvas)
  sunTexture.needsUpdate = true

  // Create sun material with emissive properties
  const sunMaterial = new THREE.MeshStandardMaterial({
    map: sunTexture,
    emissive: 0xffaa00,
    emissiveIntensity: 1,
    transparent: true,
    opacity: 0.95
  })

  const sun = new THREE.Mesh(sunGeometry, sunMaterial)
  sun.userData = sunData

  // Create corona effect (outer atmosphere)
  const coronaGeometry = new THREE.SphereGeometry(sunData.size * 1.3, 32, 32)
  const coronaMaterial = new THREE.ShaderMaterial({
    uniforms: {
      time: { value: 0 },
      glowColor: { value: new THREE.Color(0xffaa00) },
      viewVector: { value: camera.position }
    },
    vertexShader: `
      uniform vec3 viewVector;
      varying float intensity;
      void main() {
        vec3 nNormal = normalize(normal);
        vec3 nViewVec = normalize(viewVector - position);
        intensity = pow(1.0 - abs(dot(nNormal, nViewVec)), 2.0);
        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
      }
    `,
    fragmentShader: `
      uniform vec3 glowColor;
      uniform float time;
      varying float intensity;
      void main() {
        float noise = sin(time * 2.0 + gl_FragCoord.x * 0.1) * 0.1 +
                      cos(time * 1.5 + gl_FragCoord.y * 0.1) * 0.1;
        vec3 finalColor = glowColor + vec3(noise * 0.2);
        float r = length(gl_PointCoord - vec2(0.5, 0.5)) * 2.0;
        float a = pow(r, 2.0);
        vec3 color = mix(glowColor, vec3(0.0), a);
        gl_FragColor = vec4(color * intensity, intensity * 0.5);
      }
    `,
    side: THREE.BackSide,
    blending: THREE.AdditiveBlending,
    transparent: true
  })

  const corona = new THREE.Mesh(coronaGeometry, coronaMaterial)
  sun.add(corona)

  // Create solar flares
  const solarFlares = []
  for (let i = 0; i < 8; i++) {
    const flareGeometry = new THREE.ConeGeometry(
      sunData.size * 0.1,
      sunData.size * (0.5 + Math.random() * 0.8),
      8
    )

    const flareMaterial = new THREE.MeshBasicMaterial({
      color: new THREE.Color().setHSL(0.08 + Math.random() * 0.08, 1, 0.6 + Math.random() * 0.3),
      transparent: true,
      opacity: 0.7,
      blending: THREE.AdditiveBlending
    })

    const flare = new THREE.Mesh(flareGeometry, flareMaterial)

    // Position flares around the sun surface
    const theta = Math.random() * Math.PI * 2
    const phi = Math.random() * Math.PI

    flare.position.x = sunData.size * 1.1 * Math.sin(phi) * Math.cos(theta)
    flare.position.y = sunData.size * 1.1 * Math.cos(phi)
    flare.position.z = sunData.size * 1.1 * Math.sin(phi) * Math.sin(theta)

    // Orient flare outward from sun center
    flare.lookAt(new THREE.Vector3(0, 0, 0))
    flare.rotateX(Math.PI / 2)

    // Store flare data for animation
    flare.userData = {
      rotationSpeed: (Math.random() - 0.5) * 0.02,
      pulseSpeed: 0.001 + Math.random() * 0.002,
      initialScale: flare.scale.x,
      maxScale: 1 + Math.random() * 0.5
    }

    sun.add(flare)
    solarFlares.push(flare)
  }

  // Create outer glow layers
  for (let i = 0; i < 3; i++) {
    const glowGeometry = new THREE.SphereGeometry(
      sunData.size * (1.5 + i * 0.3),
      16,
      16
    )

    const glowMaterial = new THREE.MeshBasicMaterial({
      color: new THREE.Color().setHSL(0.08, 1, 0.5 - i * 0.1),
      transparent: true,
      opacity: 0.2 - i * 0.05,
      blending: THREE.AdditiveBlending,
      side: THREE.BackSide
    })

    const glow = new THREE.Mesh(glowGeometry, glowMaterial)
    sun.add(glow)
  }

  // Add sun light source
  const sunLight = new THREE.PointLight(0xffffff, 3, 100)
  sunLight.position.set(sunData.position.x, sunData.position.y, sunData.position.z)
  scene.add(sunLight)

  // Add additional light for better scene illumination
  const sunAmbientLight = new THREE.AmbientLight(0xffd700, 0.5)
  scene.add(sunAmbientLight)

  // Position sun
  sun.position.set(sunData.position.x, sunData.position.y, sunData.position.z)

  // Store sun and corona for animation
  sun.userData = {
    ...sunData,
    corona: corona,
    solarFlares: solarFlares,
    sunLight: sunLight
  }

  scene.add(sun)
  suns.push(sun)
}

const createAsteroidBelt = () => {
  const asteroidCount = 500
  // Position between 2nd and 3rd planets (Vue.js Nebula at 12 and Flutter Cosmos at 16)
  const innerRadius = 13
  const outerRadius = 15

  for (let i = 0; i < asteroidCount; i++) {
    // Create random asteroid size
    const size = 0.05 + Math.random() * 0.3

    // Create asteroid geometry (irregular shape)
    const asteroidGeometry = new THREE.DodecahedronGeometry(size, 0)

    // Create asteroid material with rocky appearance
    const asteroidMaterial = new THREE.MeshPhongMaterial({
      color: new THREE.Color().setHSL(0.08, 0.3, 0.3 + Math.random() * 0.3),
      shininess: 1,
      bumpScale: 0.1
    })

    const asteroid = new THREE.Mesh(asteroidGeometry, asteroidMaterial)

    // Random position in asteroid belt (between Mars and Jupiter)
    const angle = Math.random() * Math.PI * 2
    const radius = innerRadius + Math.random() * (outerRadius - innerRadius)
    const height = (Math.random() - 0.5) * 2 // Add some vertical spread

    asteroid.position.x = Math.cos(angle) * radius
    asteroid.position.z = Math.sin(angle) * radius
    asteroid.position.y = height

    // Random rotation
    asteroid.rotation.x = Math.random() * Math.PI * 2
    asteroid.rotation.y = Math.random() * Math.PI * 2
    asteroid.rotation.z = Math.random() * Math.PI * 2

    // Random rotation speed for animation
    asteroid.userData = {
      rotationSpeed: {
        x: (Math.random() - 0.5) * 0.01,
        y: (Math.random() - 0.5) * 0.01,
        z: (Math.random() - 0.5) * 0.01
      },
      orbitSpeed: 0.0001 + Math.random() * 0.0002,
      orbitRadius: radius,
      orbitAngle: angle,
      height: height
    }

    scene.add(asteroid)
    asteroids.push(asteroid)
  }
}

const createOrbitalRings = () => {
  planetsData.forEach((planetData, index) => {
    const orbitRadius = 8 + (index * 4)

    // Create orbital ring geometry with subtle gaps for better visual effect
    const segments = 128
    const orbitGeometry = new THREE.RingGeometry(orbitRadius - 0.05, orbitRadius + 0.05, segments)

    // Create gradient material based on planet distance
    const hue = 0.6 - (index * 0.05) // Blue to red gradient from inner to outer planets
    const orbitMaterial = new THREE.MeshBasicMaterial({
      color: new THREE.Color().setHSL(hue, 0.3, 0.5),
      transparent: true,
      opacity: 0.15 + (index * 0.02), // Slightly more visible for outer planets
      side: THREE.DoubleSide
    })

    const orbitRing = new THREE.Mesh(orbitGeometry, orbitMaterial)
    orbitRing.rotation.x = Math.PI / 2 // Lay flat on XZ plane
    orbitRing.position.y = 0

    // Add subtle pulsing effect to orbital rings
    orbitRing.userData = {
      pulseSpeed: 0.0005 + (index * 0.0001),
      baseOpacity: orbitMaterial.opacity
    }

    scene.add(orbitRing)
  })
}

const createComets = () => {
  cometsData.forEach((cometData, index) => {
    // Create comet nucleus
    const nucleusGeometry = new THREE.SphereGeometry(0.3, 16, 16)
    const nucleusMaterial = new THREE.MeshPhongMaterial({
      color: 0xe0f7fa,
      emissive: 0x81d4fa,
      emissiveIntensity: 0.5,
      shininess: 100
    })

    const nucleus = new THREE.Mesh(nucleusGeometry, nucleusMaterial)

    // Create comet tail (multiple layers for realistic effect)
    const tailGroup = new THREE.Group()

    for (let i = 0; i < 5; i++) {
      const tailLength = 8 + i * 4
      const tailGeometry = new THREE.ConeGeometry(0.8 - i * 0.15, tailLength, 8)
      const tailMaterial = new THREE.MeshBasicMaterial({
        color: new THREE.Color().setHSL(0.55, 0.8, 0.9 - i * 0.15),
        transparent: true,
        opacity: 0.6 - i * 0.1,
        blending: THREE.AdditiveBlending
      })

      const tail = new THREE.Mesh(tailGeometry, tailMaterial)
      tail.position.z = -tailLength / 2 + 2
      tail.rotation.x = Math.PI / 2
      tailGroup.add(tail)
    }

    // Add particle system for comet tail
    const tailParticles = createCometTailParticles()
    tailGroup.add(tailParticles)

    // Create comet group
    const cometGroup = new THREE.Group()
    cometGroup.add(nucleus)
    cometGroup.add(tailGroup)

    // Position comet at starting point
    cometGroup.position.set(cometData.position.x, cometData.position.y, cometData.position.z)

    // Store comet data for animation
    cometGroup.userData = {
      ...cometData,
      nucleus: nucleus,
      tailGroup: tailGroup,
      tailParticles: tailParticles,
      angle: Math.random() * Math.PI * 2,
      orbitSpeed: cometData.orbitSpeed,
      orbitRadius: cometData.orbitRadius,
      ellipseRatio: 0.6 + Math.random() * 0.3
    }

    scene.add(cometGroup)
    comets.push(cometGroup)
  })
}

const createCometTailParticles = () => {
  const particleCount = 200
  const particles = new THREE.BufferGeometry()
  const positions = new Float32Array(particleCount * 3)
  const colors = new Float32Array(particleCount * 3)
  const sizes = new Float32Array(particleCount)

  for (let i = 0; i < particleCount; i++) {
    // Position particles in cone shape behind comet
    const distance = Math.random() * 20
    const angle = Math.random() * Math.PI * 2
    const radius = Math.random() * 2

    positions[i * 3] = Math.cos(angle) * radius * (distance / 20)
    positions[i * 3 + 1] = Math.sin(angle) * radius * (distance / 20)
    positions[i * 3 + 2] = -distance

    // Cyan to blue gradient colors
    const color = new THREE.Color().setHSL(0.55, 0.8, 0.7 + Math.random() * 0.3)
    colors[i * 3] = color.r
    colors[i * 3 + 1] = color.g
    colors[i * 3 + 2] = color.b

    sizes[i] = Math.random() * 0.5 + 0.1
  }

  particles.setAttribute('position', new THREE.BufferAttribute(positions, 3))
  particles.setAttribute('color', new THREE.BufferAttribute(colors, 3))
  particles.setAttribute('size', new THREE.BufferAttribute(sizes, 1))

  const particleMaterial = new THREE.PointsMaterial({
    size: 0.3,
    vertexColors: true,
    transparent: true,
    opacity: 0.8,
    blending: THREE.AdditiveBlending,
    sizeAttenuation: true
  })

  return new THREE.Points(particles, particleMaterial)
}

const onWindowResize = () => {
  if (!sceneContainer.value) return

  const width = sceneContainer.value.clientWidth
  const height = sceneContainer.value.clientHeight

  camera.aspect = width / height
  camera.updateProjectionMatrix()
  renderer.setSize(width, height)
}

const animate = () => {
  animationId = requestAnimationFrame(animate)

  // Animate planets with orbital motion
  planets.forEach((planetGroup, index) => {
    if (planetGroup.userData && planetGroup.userData.orbitRadius !== undefined) {
      // Update orbital angle
      planetGroup.userData.orbitAngle += planetGroup.userData.orbitSpeed

      // Calculate new position based on orbital motion
      planetGroup.position.x = Math.cos(planetGroup.userData.orbitAngle) * planetGroup.userData.orbitRadius
      planetGroup.position.z = Math.sin(planetGroup.userData.orbitAngle) * planetGroup.userData.orbitRadius
      planetGroup.position.y = planetGroup.userData.height + Math.sin(Date.now() * 0.0005) * 0.2 // Gentle vertical bobbing

      // Rotate planet on its axis
      planetGroup.rotation.y += planetGroup.userData.rotationSpeed
    } else {
      // Fallback to simple rotation for backward compatibility
      planetGroup.rotation.y += 0.005
    }

    // Animate moons
    planetGroup.traverse((child) => {
      if (child.userData && child.userData.orbit) {
        const moon = child
        const orbit = moon.userData.orbit
        orbit.rotation.y += moon.userData.speed
        orbit.rotation.x += moon.userData.speed * 0.1
      }

      // Animate storm systems
      if (child.userData && child.userData.rotationSpeed) {
        child.rotation.z += child.userData.rotationSpeed
      }
    })

    // Animate city lights (flickering effect)
    if (index === 0) { // Vue.js planet
      planetGroup.children.forEach(child => {
        if (child instanceof THREE.Points) {
          child.material.opacity = 0.6 + Math.sin(Date.now() * 0.002) * 0.2
        }
      })
    }

    // Animate sandstorms
    if (index === 5) { // Cloud Constellation planet
      planetGroup.children.forEach(child => {
        if (child instanceof THREE.Points) {
          const positions = child.geometry.attributes.position.array
          for (let i = 1; i < positions.length; i += 3) {
            positions[i + 1] += (Math.random() - 0.5) * 0.01
          }
          child.geometry.attributes.position.needsUpdate = true
        }
      })
    }
  })

  // Animate stars
  stars.forEach(starField => {
    starField.rotation.y += 0.0001
  })

  // Animate nebula
  scene.children.forEach(child => {
    if (child instanceof THREE.Points && child.material.opacity === 0.1) {
      child.rotation.y += 0.0002
      child.rotation.x += 0.0001
    }
  })

  // Animate orbital rings
  scene.children.forEach(child => {
    if (child instanceof THREE.Mesh && child.geometry instanceof THREE.RingGeometry && child.userData.pulseSpeed) {
      const time = Date.now() * 0.001
      const pulseFactor = Math.sin(time * child.userData.pulseSpeed * Math.PI * 2) * 0.3 + 0.7
      child.material.opacity = child.userData.baseOpacity * pulseFactor
    }
  })

  // Animate sun
  if (suns && suns.length > 0) {
    suns.forEach(sun => {
      if (sun.userData && sun.userData.solarFlares) {
        const time = Date.now() * 0.001

        // Animate corona shader uniforms
        if (sun.userData.corona && sun.userData.corona.material.uniforms) {
          sun.userData.corona.material.uniforms.time.value = time
          sun.userData.corona.material.uniforms.viewVector.value = camera.position
        }

        // Animate solar flares
        sun.userData.solarFlares.forEach(flare => {
          if (flare.userData) {
            // Rotate flares
            flare.rotation.z += flare.userData.rotationSpeed

            // Pulse flares
            const pulseScale = flare.userData.initialScale +
              Math.sin(time * flare.userData.pulseSpeed) *
              (flare.userData.maxScale - flare.userData.initialScale)

            flare.scale.set(pulseScale, pulseScale, pulseScale)

            // Vary opacity
            if (flare.material) {
              flare.material.opacity = 0.5 + Math.sin(time * 0.002 + flare.userData.rotationSpeed * 10) * 0.3
            }
          }
        })

        // Rotate sun slowly
        sun.rotation.y += 0.001
      }
    })
  }

  // Animate asteroids
  if (asteroids && asteroids.length > 0) {
    asteroids.forEach(asteroid => {
      if (asteroid && asteroid.userData && asteroid.userData.rotationSpeed && asteroid.userData.orbitSpeed !== undefined) {
        // Rotate asteroid
        asteroid.rotation.x += asteroid.userData.rotationSpeed.x || 0
        asteroid.rotation.y += asteroid.userData.rotationSpeed.y || 0
        asteroid.rotation.z += asteroid.userData.rotationSpeed.z || 0

        // Orbit around sun
        asteroid.userData.orbitAngle = (asteroid.userData.orbitAngle || 0) + asteroid.userData.orbitSpeed
        asteroid.position.x = Math.cos(asteroid.userData.orbitAngle) * asteroid.userData.orbitRadius
        asteroid.position.z = Math.sin(asteroid.userData.orbitAngle) * asteroid.userData.orbitRadius
      }
    })
  }

  // Animate comets
  if (comets && comets.length > 0) {
    comets.forEach(comet => {
    if (comet && comet.userData && comet.userData.orbitSpeed !== undefined && comet.userData.orbitRadius !== undefined) {
      const time = Date.now() * 0.001

      // Update comet angle for elliptical orbit
      comet.userData.angle = (comet.userData.angle || 0) + comet.userData.orbitSpeed

      // Calculate elliptical orbit position
      const x = Math.cos(comet.userData.angle) * comet.userData.orbitRadius
      const z = Math.sin(comet.userData.angle) * comet.userData.orbitRadius * (comet.userData.ellipseRatio || 0.7)
      const y = Math.sin(time * 0.1) * 2 // Slight vertical movement

      comet.position.set(x, y, z)

      // Orient comet tail away from sun
      const directionFromSun = new THREE.Vector3(x, y, z).normalize()
      const lookAtPosition = directionFromSun.multiplyScalar(50)
      if (comet.userData.tailGroup) {
        comet.userData.tailGroup.lookAt(lookAtPosition)
      }

      // Animate tail particles
      if (comet.userData.tailParticles) {
        const particles = comet.userData.tailParticles
        particles.rotation.z = time * 0.5

        // Fade tail particles based on distance from sun
        const distanceFromSun = Math.sqrt(x * x + y * y + z * z)
        const opacity = Math.max(0.2, Math.min(0.8, 1 - (distanceFromSun / 60)))
        particles.material.opacity = opacity
      }

      // Rotate nucleus
      comet.userData.nucleus.rotation.y += 0.02
      comet.userData.nucleus.rotation.x += 0.01
    }
    })
  }

  // Update controls
  controls.update()

  renderer.render(scene, camera)
}

const cleanup = () => {
  if (animationId) {
    cancelAnimationFrame(animationId)
  }
  if (renderer) {
    renderer.dispose()
  }

  // Clean up arrays
  planets.length = 0
  stars.length = 0
  suns.length = 0
  asteroids.length = 0
  comets.length = 0

  window.removeEventListener('resize', onWindowResize)
}

// Star styles for loading screen
const getStarStyle = (index) => {
  return {
    left: `${Math.random() * 100}%`,
    top: `${Math.random() * 100}%`,
    animationDelay: `${Math.random() * 3}s`,
    animationDuration: `${2 + Math.random() * 3}s`
  }
}

const getPopupStarStyle = (index) => {
  return {
    left: `${Math.random() * 100}%`,
    top: `${Math.random() * 100}%`,
    animationDelay: `${Math.random() * 2}s`
  }
}
</script>

<style scoped>
.space-container {
  width: 100vw;
  height: 100vh;
  position: relative;
  overflow: hidden;
  background: radial-gradient(ellipse at 20% 30%, rgba(65, 105, 225, 0.15) 0%, transparent 50%),
              radial-gradient(ellipse at 80% 70%, rgba(138, 43, 226, 0.1) 0%, transparent 50%),
              radial-gradient(ellipse at 50% 50%, rgba(255, 0, 255, 0.05) 0%, transparent 70%),
              linear-gradient(135deg, #0a0a0a 0%, #1a0033 50%, #000814 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Add animated starfield background */
.space-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image:
    radial-gradient(2px 2px at 20% 30%, white, transparent),
    radial-gradient(2px 2px at 60% 70%, white, transparent),
    radial-gradient(1px 1px at 50% 50%, white, transparent),
    radial-gradient(1px 1px at 80% 10%, white, transparent),
    radial-gradient(2px 2px at 90% 60%, white, transparent);
  background-size: 200% 200%;
  animation: starfield 120s linear infinite;
  pointer-events: none;
  z-index: 1;
}

@keyframes starfield {
  0% {
    background-position: 0% 0%, 30% 30%, 60% 60%, 90% 90%, 120% 120%;
    opacity: 0.8;
  }
  50% {
    opacity: 1;
  }
  100% {
    background-position: 200% 200%, 230% 230%, 260% 260%, 290% 290%, 320% 320%;
    opacity: 0.8;
  }
}

/* Loading Screen */
.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #000000;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.loading-content {
  text-align: center;
  position: relative;
}

.loading-stars {
  position: absolute;
  width: 300px;
  height: 300px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.star {
  position: absolute;
  width: 2px;
  height: 2px;
  background: white;
  border-radius: 50%;
  animation: twinkle linear infinite;
}

@keyframes twinkle {
  0%, 100% { opacity: 0; }
  50% { opacity: 1; }
}

.loading-text {
  color: #ffd60a;
  font-size: 1.5rem;
  margin-bottom: 1rem;
  z-index: 1;
  position: relative;
  text-shadow: 0 0 10px rgba(255, 214, 10, 0.5);
  background: linear-gradient(45deg, #ffd60a, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.loading-progress {
  color: #ffffff;
  font-size: 1.2rem;
  z-index: 1;
  position: relative;
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
}

/* Space Scene */
.space-scene {
  width: 100%;
  height: 100%;
  position: relative;
  z-index: 2;
}

.space-controls-info {
  position: absolute;
  top: 20px;
  left: 20px;
  background: rgba(0, 8, 20, 0.8);
  color: white;
  padding: 15px;
  border-radius: 10px;
  z-index: 100;
  border: 1px solid rgba(255, 214, 10, 0.3);
}

.control-item {
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.key {
  background: rgba(255, 214, 10, 0.2);
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.9rem;
}

.mobile-space-controls {
  position: absolute;
  bottom: 20px;
  left: 20px;
  right: 20px;
  background: rgba(0, 8, 20, 0.8);
  color: white;
  padding: 15px;
  border-radius: 10px;
  z-index: 100;
  border: 1px solid rgba(255, 214, 10, 0.3);
  text-align: center;
}

/* Astronaut Stats */
.astronaut-stats {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(0, 8, 20, 0.8);
  color: white;
  padding: 15px;
  border-radius: 10px;
  z-index: 100;
  border: 1px solid rgba(255, 214, 10, 0.3);
}

.stat-item {
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.stat-icon {
  font-size: 1.2rem;
}

.stat-label {
  color: #ffd60a;
}

.stat-value {
  font-weight: bold;
  color: #ffd60a;
}

/* Planet Popup */
.planet-popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 8, 20, 0.95);
  z-index: 200;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.planet-popup.active {
  opacity: 1;
  visibility: visible;
}

.popup-space-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(ellipse at 20% 30%, rgba(65, 105, 225, 0.2) 0%, transparent 50%),
              radial-gradient(ellipse at 80% 70%, rgba(138, 43, 226, 0.15) 0%, transparent 50%),
              radial-gradient(ellipse at center, #1a0033 0%, #0a0a0a 100%);
}

.stars-background {
  position: absolute;
  width: 100%;
  height: 100%;
}

.popup-star {
  position: absolute;
  width: 1px;
  height: 1px;
  background: white;
  border-radius: 50%;
  animation: twinkle 2s linear infinite;
}

.popup-content {
  background: rgba(0, 0, 0, 0.3);
  border: 2px solid rgba(255, 214, 10, 0.3);
  border-radius: 20px;
  padding: 40px;
  max-width: 600px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  z-index: 1;
  backdrop-filter: blur(15px);
  color: white;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.close-popup {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  font-size: 1.5rem;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.close-popup:hover {
  background: rgba(255, 214, 10, 0.2);
  border-color: #ffd60a;
}

.planet-header {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
}

.planet-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  flex-shrink: 0;
}

.planet-info h2 {
  margin: 0 0 5px 0;
  font-size: 2rem;
  color: #ffd60a;
}

.planet-subtitle {
  margin: 0 0 10px 0;
  color: #ffffff;
  font-size: 1.1rem;
}

.planet-stats {
  display: flex;
  gap: 10px;
}

.stat-badge {
  background: rgba(255, 214, 10, 0.2);
  border: 1px solid rgba(255, 214, 10, 0.5);
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 0.9rem;
  color: #ffd60a;
}

.planet-description {
  margin-bottom: 30px;
  line-height: 1.6;
  color: rgba(255, 255, 255, 0.9);
}

.tech-stack h3 {
  color: #ffd60a;
  margin-bottom: 15px;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 30px;
}

.tech-tag {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
  padding: 6px 15px;
  border-radius: 20px;
  font-size: 0.9rem;
  color: #ffffff;
}

.missions h3 {
  color: #ffd60a;
  margin-bottom: 20px;
}

.mission-card {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 15px;
}

.mission-card:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 214, 10, 0.5);
  transform: translateY(-2px);
}

.mission-icon {
  font-size: 2rem;
  flex-shrink: 0;
}

.mission-content {
  flex: 1;
}

.mission-content h4 {
  margin: 0 0 8px 0;
  color: #ffd60a;
}

.mission-content p {
  margin: 0 0 10px 0;
  color: rgba(255, 255, 255, 0.8);
}

.mission-tech {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.mini-tech-tag {
  background: rgba(255, 214, 10, 0.1);
  color: #ffd60a;
  padding: 2px 8px;
  border-radius: 10px;
  font-size: 0.8rem;
}

.mission-link {
  font-size: 1.5rem;
  color: #ffd60a;
  flex-shrink: 0;
}

.navigation-hint {
  text-align: center;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  color: rgba(255, 255, 255, 0.7);
}

/* Instructions */
.space-instructions {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 8, 20, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 300;
}

.instructions-content {
  background: linear-gradient(135deg, rgba(10, 10, 10, 0.9), rgba(26, 0, 51, 0.9));
  border: 2px solid rgba(255, 214, 10, 0.5);
  border-radius: 20px;
  padding: 40px;
  max-width: 500px;
  text-align: center;
  color: white;
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px rgba(65, 105, 225, 0.2),
              0 8px 32px rgba(138, 43, 226, 0.1);
}

.instruction-icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.instructions-content h3 {
  margin: 0 0 20px 0;
  color: #ffd60a;
  font-size: 1.8rem;
}

.instructions-content p {
  margin-bottom: 15px;
  line-height: 1.6;
  color: rgba(255, 255, 255, 0.9);
}

.instructions-content button {
  background: #ffd60a;
  color: #000814;
  border: none;
  padding: 15px 30px;
  border-radius: 25px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 20px;
}

.instructions-content button:hover {
  background: #ffed4e;
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .space-controls-info {
    display: none;
  }

  .astronaut-stats {
    top: auto;
    bottom: 80px;
    right: 10px;
    left: 10px;
    background: rgba(0, 8, 20, 0.7);
    padding: 10px;
  }

  .stat-item {
    margin-bottom: 5px;
    font-size: 0.9rem;
  }

  .popup-content {
    padding: 30px 20px;
    margin: 20px;
  }

  .planet-header {
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }

  .planet-icon {
    width: 60px;
    height: 60px;
    font-size: 2rem;
  }

  .planet-info h2 {
    font-size: 1.5rem;
  }

  .mission-card {
    flex-direction: column;
    text-align: center;
  }

  .mobile-space-controls {
    font-size: 0.9rem;
  }

  .instructions-content {
    margin: 20px;
    padding: 30px 20px;
  }
}

@media (max-width: 480px) {
  .popup-content {
    padding: 20px 15px;
  }

  .planet-icon {
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
  }

  .tech-tags {
    gap: 5px;
  }

  .tech-tag {
    padding: 4px 10px;
    font-size: 0.8rem;
  }
}

/* Contact Popup Special Styles */
.contact-popup .popup-content {
  background: rgba(0, 0, 0, 0.2);
  border: 2px solid rgba(255, 215, 0, 0.4);
  box-shadow: 0 8px 32px rgba(255, 215, 0, 0.15),
              inset 0 0 50px rgba(255, 215, 0, 0.05);
  backdrop-filter: blur(20px);
}

.contact-popup .planet-icon {
  background: linear-gradient(135deg, #ffd700, #ffed4e, #ffd700) !important;
  box-shadow: 0 0 30px rgba(255, 215, 0, 0.6),
              0 0 60px rgba(255, 215, 0, 0.3),
              inset 0 0 20px rgba(255, 255, 255, 0.2);
  animation: sunPulse 2s ease-in-out infinite;
}

.contact-popup .planet-info h2 {
  background: linear-gradient(135deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
}

.contact-popup .mission-card {
  background: rgba(255, 215, 0, 0.1);
  border: 1px solid rgba(255, 215, 0, 0.3);
  box-shadow: 0 4px 15px rgba(255, 215, 0, 0.1);
  transition: all 0.3s ease;
}

.contact-popup .mission-card:hover {
  background: rgba(255, 215, 0, 0.2);
  border-color: rgba(255, 215, 0, 0.6);
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
}

.contact-popup .stat-badge {
  background: rgba(255, 215, 0, 0.25);
  border: 1px solid rgba(255, 215, 0, 0.6);
  color: #ffd700;
  text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
}

.contact-popup .tech-tag {
  background: rgba(255, 215, 0, 0.15);
  border: 1px solid rgba(255, 215, 0, 0.4);
  color: #ffd700;
}

@keyframes sunPulse {
  0%, 100% {
    transform: scale(1);
    box-shadow: 0 0 30px rgba(255, 215, 0, 0.6),
                0 0 60px rgba(255, 215, 0, 0.3),
                inset 0 0 20px rgba(255, 255, 255, 0.2);
  }
  50% {
    transform: scale(1.05);
    box-shadow: 0 0 40px rgba(255, 215, 0, 0.8),
                0 0 80px rgba(255, 215, 0, 0.4),
                inset 0 0 25px rgba(255, 255, 255, 0.3);
  }
}
</style>
