<template>
  <AppLayout>
    <!-- Space Shooter Game -->
    <section class="space-game-section">
      <div class="game-header">
        <h1 class="game-title">Project Asteroid Field</h1>

      </div>

      <!-- Game Canvas Container -->
      <div class="game-container">
        <canvas id="game-canvas" class="game-canvas"></canvas>

        <!-- Game UI Overlay -->
        <div class="game-ui">
          <!-- Health Bar -->
          <div class="health-bar-container">
            <div class="health-label">Player Health</div>
            <div class="health-bar">
              <div class="health-fill" :style="{ width: (playerHealth / maxHealth) * 100 + '%' }"></div>
            </div>
          </div>

          <!-- Desktop Controls Info -->
          <div class="controls-info desktop-only">
            <div class="control-item">
              <span class="key">←→</span>
              <span class="control-desc">Move</span>
            </div>
            <div class="control-item">
              <span class="key">SPACE</span>
              <span class="control-desc">Shoot</span>
            </div>
          </div>

          <!-- Game Over Screen -->
          <div v-if="gameOver && !victory" class="game-over-screen">
            <div class="game-over-content">
              <h2 class="game-over-title">💥 GAME OVER 💥</h2>
              <p class="game-over-message">Your spaceship was destroyed!</p>
              <div class="game-over-stats">
                <div class="stat-row">
                  <span class="stat-label">Final Score:</span>
                  <span class="stat-value">{{ score }}</span>
                </div>
                <div class="stat-row">
                  <span class="stat-label">Projects Discovered:</span>
                  <span class="stat-value">{{ projectsFound }}/{{ projects.length }}</span>
                </div>
              </div>
              <button @click="restartGame" class="restart-btn">🚀 Play Again</button>
            </div>
          </div>

          <!-- Victory Screen -->
          <div v-if="victory" class="victory-screen">
            <div class="victory-content">
              <h2 class="victory-title">🎉 VICTORY! 🎉</h2>
              <p class="victory-message">All projects discovered!</p>
              <div class="victory-stats">
                <div class="stat-row">
                  <span class="stat-label">Final Score:</span>
                  <span class="stat-value">{{ score }}</span>
                </div>
                <div class="stat-row">
                  <span class="stat-label">Projects Discovered:</span>
                  <span class="stat-value">{{ projectsFound }}/{{ projects.length }}</span>
                </div>
              </div>
              <button @click="restartGame" class="restart-btn">🎮 Play Again</button>
            </div>
          </div>
        </div>

        <!-- Mobile Controls (outside game-ui) -->
        <div class="mobile-controls mobile-only">
          <!-- Left Side - Movement Controls (DPAD style) -->
          <div class="movement-controls left-controls">
            <button
              class="mobile-btn move-btn left-btn"
              @touchstart="handleMobileMoveStart('left')"
              @touchend="handleMobileMoveEnd('left')"
              @mousedown="handleMobileMoveStart('left')"
              @mouseup="handleMobileMoveEnd('left')"
              @mouseleave="handleMobileMoveEnd('left')"
            >
              <span class="btn-icon">←</span>
            </button>
            <button
              class="mobile-btn move-btn right-btn"
              @touchstart="handleMobileMoveStart('right')"
              @touchend="handleMobileMoveEnd('right')"
              @mousedown="handleMobileMoveStart('right')"
              @mouseup="handleMobileMoveEnd('right')"
              @mouseleave="handleMobileMoveEnd('right')"
            >
              <span class="btn-icon">→</span>
            </button>
          </div>

          <!-- Right Side - Fire Control -->
          <div class="fire-controls right-controls">
            <button
              class="mobile-btn fire-btn"
              @touchstart="handleMobileFire"
              @touchend="handleMobileFireEnd"
              @mousedown="handleMobileFire"
              @mouseup="handleMobileFireEnd"
            >
              <span class="btn-icon">🔥</span>
              <span class="btn-label">FIRE</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Project Details Modal -->
      <div v-if="selectedProject" class="project-modal" @click="closeProjectModal">
        <div class="project-card" @click.stop>
          <div class="project-header">
            <h2>{{ selectedProject.name }}</h2>
            <button class="close-btn" @click="closeProjectModal">&times;</button>
          </div>
          <div class="project-content">
            <img :src="selectedProject.image" :alt="selectedProject.name" class="project-image" />
            <p class="project-description">{{ selectedProject.description }}</p>
            <div class="project-tech">
              <span v-for="tech in selectedProject.technologies" :key="tech" class="tech-tag">{{ tech }}</span>
            </div>
            <div class="project-links">
              <a v-if="selectedProject.live_url" :href="selectedProject.live_url" target="_blank" class="project-link">Live Demo</a>
              <a v-if="selectedProject.github_url" :href="selectedProject.github_url" target="_blank" class="project-link">GitHub</a>
              <a v-if="selectedProject.demo_url" :href="selectedProject.demo_url" target="_blank" class="project-link">Demo</a>
            </div>
          </div>
          <button class="resume-btn" @click="resumeGame">Resume Game</button>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import AppLayout from '../components/AppLayout.vue'

// Game state
const score = ref(0)
const playerHealth = ref(100)
const maxHealth = ref(100)
const projectsFound = ref(0)
const gameOver = ref(false)
const victory = ref(false)
const selectedProject = ref(null)
const gamePaused = ref(false)

// 2D Game variables
let gameCanvas, gameCtx
let player, bullets = [], asteroids = [], stars = [], particles = []
let keys = {}
let gameLoop
let lastShootTime = 0
const shootCooldown = 250 // milliseconds
let gameWidth = 800
let gameHeight = 600

// Image assets
let images = {
  spaceship: null,
  background: null,
  explosion: null,
  asteroids: []
}
let imagesLoaded = false

// Projects data for asteroids
const projects = ref([])

// Fetch projects from API
const fetchProjects = async () => {
  try {
    const response = await fetch('/api/v1/home/projects')
    const projectsData = await response.json()

    // Transform project data for asteroids
    projects.value = projectsData.map((project, index) => ({
      id: project.id,
      name: project.name,
      description: project.description,
      image: project.image || `https://via.placeholder.com/400x300/0a0a0a/${encodeURIComponent(project.color || '#00ffff').replace('#', '')}?text=${encodeURIComponent(project.name)}`,
      technologies: project.technologies || [],
      live_url: project.live_url,
      github_url: project.github_url,
      demo_url: project.demo_url,
      maxHealth: project.maxHealth || 3,
      color: project.color || '#00ffff'
    }))
  } catch (error) {
    console.error('Error fetching projects:', error)
    // Fallback to default data
    projects.value = [
      {
        id: 1,
        name: 'Sample Project',
        description: 'A sample project placeholder',
        image: 'https://via.placeholder.com/400x300/0a0a0a/00ffff?text=Sample',
        technologies: ['Vue.js', 'Laravel'],
        live_url: '#',
        github_url: '#',
        demo_url: '#',
        maxHealth: 3,
        color: '#00ffff'
      }
    ]
  }
}

// Load game images
const loadImages = async () => {
  const imagePaths = {
    spaceship: '/images/game/spaceship.png',
    background: '/images/game/space-background.jpg',
    explosion: '/images/game/explosion.png'
  }

  // Load asteroid images
  for (let i = 1; i <= 6; i++) {
    imagePaths[`asteroid-${i}`] = `/images/game/asteroid-${i}.png`
  }

  const loadImage = (src) => {
    return new Promise((resolve, reject) => {
      const img = new Image()
      img.onload = () => resolve(img)
      img.onerror = () => {
        console.warn(`Failed to load image: ${src}`)
        resolve(null) // Resolve with null to continue without this image
      }
      img.src = src
    })
  }

  try {
    // Load all images
    const loadedImages = await Promise.all(
      Object.entries(imagePaths).map(async ([key, path]) => {
        const img = await loadImage(path)
        return [key, img]
      })
    )

    // Store loaded images
    loadedImages.forEach(([key, img]) => {
      if (key.startsWith('asteroid-')) {
        const index = parseInt(key.split('-')[1])
        images.asteroids[index] = img
      } else {
        images[key] = img
      }
    })

    imagesLoaded = true
    console.log('All game images loaded successfully')
  } catch (error) {
    console.error('Error loading images:', error)
  }
}

// Initialize 2D Canvas game
const initGame = async () => {
  // Get game canvas
  gameCanvas = document.getElementById('game-canvas')
  if (!gameCanvas) return

  // Set canvas size
  gameWidth = window.innerWidth
  gameHeight = window.innerHeight * 0.6
  gameCanvas.width = gameWidth
  gameCanvas.height = gameHeight

  // Get 2D context
  gameCtx = gameCanvas.getContext('2d')

  // Load images first
  await loadImages()

  // Initialize game objects
  createStars()
  createPlayer()
  createAsteroids()
}

// Create background stars
const createStars = () => {
  for (let i = 0; i < 200; i++) {
    stars.push({
      x: Math.random() * gameWidth,
      y: Math.random() * gameHeight,
      size: Math.random() * 2,
      speed: Math.random() * 0.5 + 0.1,
      brightness: Math.random()
    })
  }
}

// Create player spaceship
const createPlayer = () => {
  player = {
    x: gameWidth / 2,
    y: gameHeight - 100,
    width: 40,
    height: 50,
    speed: 5,
    color: '#00ffff'
  }
}

// Create asteroids from project data
const createAsteroids = () => {
  projects.value.forEach((project, index) => {
    const asteroid = {
      x: Math.random() * (gameWidth - 60) + 30,
      y: -100 - (index * 150),
      width: 60,
      height: 60,
      speed: Math.random() * 1.5 + 0.5,
      rotation: 0,
      rotationSpeed: (Math.random() - 0.5) * 0.05,
      color: project.color,
      projectId: project.id,
      project: project,
      health: project.maxHealth,
      maxHealth: project.maxHealth,
      discovered: false,
      vertices: generateAsteroidShape()
    }
    asteroids.push(asteroid)
  })
}

// Generate random asteroid shape
const generateAsteroidShape = () => {
  const vertices = []
  const points = 8
  for (let i = 0; i < points; i++) {
    const angle = (i / points) * Math.PI * 2
    const radius = 25 + Math.random() * 10
    vertices.push({
      x: Math.cos(angle) * radius,
      y: Math.sin(angle) * radius
    })
  }
  return vertices
}

// Create bullet
const createBullet = () => {
  const currentTime = Date.now()
  if (currentTime - lastShootTime < shootCooldown) return

  lastShootTime = currentTime

  const bullet = {
    x: player.x,
    y: player.y - 20,
    width: 4,
    height: 10,
    speed: 10,
    color: '#00ff00'
  }

  bullets.push(bullet)

  // Add muzzle flash effect
  createMuzzleFlash(player.x, player.y - 20)
}

// Create muzzle flash effect
const createMuzzleFlash = (x, y) => {
  for (let i = 0; i < 5; i++) {
    particles.push({
      x: x + (Math.random() - 0.5) * 10,
      y: y,
      vx: (Math.random() - 0.5) * 2,
      vy: Math.random() * 2,
      size: Math.random() * 3 + 1,
      color: '#ffff00',
      life: 10
    })
  }
}

// Handle keyboard input
const handleKeyDown = (event) => {
  keys[event.code] = true

  if (event.code === 'Space') {
    event.preventDefault()
    if (!gameOver.value && !gamePaused.value) {
      createBullet()
    }
  }
}

const handleKeyUp = (event) => {
  keys[event.code] = false
}

// Mobile control handlers
const handleMobileMoveStart = (direction) => {
  keys[`mobile-${direction}`] = true
  // Prevent default touch behavior
  if (event && event.preventDefault) {
    event.preventDefault()
  }
}

const handleMobileMoveEnd = (direction) => {
  keys[`mobile-${direction}`] = false
  // Prevent default touch behavior
  if (event && event.preventDefault) {
    event.preventDefault()
  }
}

const handleMobileFire = () => {
  if (!gameOver.value && !gamePaused.value) {
    createBullet()
  }
  // Prevent default touch behavior
  if (event && event.preventDefault) {
    event.preventDefault()
  }
}

const handleMobileFireEnd = () => {
  // Prevent default touch behavior
  if (event && event.preventDefault) {
    event.preventDefault()
  }
}

// Update player movement
const updatePlayer = () => {
  if (!player || gameOver.value || gamePaused.value) return

  // Handle keyboard input
  if (keys['ArrowLeft'] && player.x > 20) {
    player.x -= player.speed
  }
  if (keys['ArrowRight'] && player.x < gameWidth - 20) {
    player.x += player.speed
  }

  // Handle mobile touch input
  if (keys['mobile-left'] && player.x > 20) {
    player.x -= player.speed
  }
  if (keys['mobile-right'] && player.x < gameWidth - 20) {
    player.x += player.speed
  }
}

// Update bullets
const updateBullets = () => {
  if (gameOver.value || gamePaused.value) return

  for (let i = bullets.length - 1; i >= 0; i--) {
    const bullet = bullets[i]
    bullet.y -= bullet.speed

    // Remove bullets that go off screen
    if (bullet.y < -10) {
      bullets.splice(i, 1)
      continue
    }

    // Check collision with asteroids
    for (let j = asteroids.length - 1; j >= 0; j--) {
      const asteroid = asteroids[j]

      if (checkCollision(bullet, asteroid)) {
        // Hit asteroid
        asteroid.health--

        // Create hit effect
        createHitEffect(asteroid.x, asteroid.y)

        // Remove bullet
        bullets.splice(i, 1)

        // Check if asteroid is destroyed
        if (asteroid.health <= 0) {
          destroyAsteroid(asteroid, j)
        }

        break
      }
    }
  }
}

// Check collision between two objects
const checkCollision = (obj1, obj2) => {
  return obj1.x < obj2.x + obj2.width &&
         obj1.x + obj1.width > obj2.x &&
         obj1.y < obj2.y + obj2.height &&
         obj1.y + obj1.height > obj2.y
}

// Create hit effect
const createHitEffect = (x, y) => {
  for (let i = 0; i < 10; i++) {
    particles.push({
      x: x,
      y: y,
      vx: (Math.random() - 0.5) * 5,
      vy: (Math.random() - 0.5) * 5,
      size: Math.random() * 4 + 2,
      color: '#ffaa00',
      life: 20
    })
  }
}

// Destroy asteroid and show project
const destroyAsteroid = (asteroid, index) => {
  const project = asteroid.project

  // Create explosion effect
  createExplosionEffect(asteroid.x, asteroid.y)

  // Remove asteroid
  asteroids.splice(index, 1)

  // Update score and projects found
  score.value += 100
  projectsFound.value++

  // Mark project as discovered
  project.discovered = true

  // Pause game and show project details
  gamePaused.value = true
  selectedProject.value = project

  // Check victory condition
  if (projectsFound.value >= projects.value.length) {
    victory.value = true
    gameOver.value = true
  }
}

// Create explosion effect
const createExplosionEffect = (x, y) => {
  // Create explosion sprite if image is available
  if (images.explosion && imagesLoaded) {
    particles.push({
      x: x,
      y: y,
      vx: 0,
      vy: 0,
      size: 80,
      image: images.explosion,
      isExplosion: true,
      life: 20
    })
  }

  // Also create particle effects for additional visual impact
  for (let i = 0; i < 20; i++) {
    particles.push({
      x: x,
      y: y,
      vx: (Math.random() - 0.5) * 8,
      vy: (Math.random() - 0.5) * 8,
      size: Math.random() * 6 + 2,
      color: Math.random() > 0.5 ? '#ff6600' : '#ffaa00',
      life: 30
    })
  }
}

// Update asteroids
const updateAsteroids = () => {
  if (gameOver.value || gamePaused.value) return

  for (let i = asteroids.length - 1; i >= 0; i--) {
    const asteroid = asteroids[i]

    // Move asteroid
    asteroid.y += asteroid.speed
    asteroid.rotation += asteroid.rotationSpeed

    // Check collision with player
    if (checkSpaceshipCollision(asteroid)) {
      handleSpaceshipCollision()
      return
    }

    // Reset asteroid position if it goes past player
    if (asteroid.y > gameHeight + 100) {
      asteroids.splice(i, 1)

      // Damage player if asteroid gets past
      playerHealth.value = Math.max(0, playerHealth.value - 10)
      if (playerHealth.value <= 0) {
        handleSpaceshipCollision()
      }
    }
  }

  // Spawn new asteroids if needed
  if (asteroids.length < projects.value.length && Math.random() < 0.01) {
    spawnNewAsteroid()
  }
}

// Check collision between spaceship and asteroid
const checkSpaceshipCollision = (asteroid) => {
  const distance = Math.sqrt(
    Math.pow(asteroid.x - player.x, 2) +
    Math.pow(asteroid.y - player.y, 2)
  )

  // Use collision radius based on asteroid and player size
  const collisionRadius = (asteroid.width / 2) + (player.width / 2) - 10 // Small buffer for better gameplay

  return distance < collisionRadius
}

// Handle spaceship collision with asteroid
const handleSpaceshipCollision = () => {
  gameOver.value = true

  // Create large explosion at spaceship position
  createSpaceshipExplosion()

  // Stop the game
  gamePaused.value = true
}

// Create spaceship explosion effect
const createSpaceshipExplosion = () => {
  // Create explosion sprite if image is available
  if (images.explosion && imagesLoaded) {
    particles.push({
      x: player.x,
      y: player.y,
      vx: 0,
      vy: 0,
      size: 120, // Larger explosion for spaceship
      image: images.explosion,
      isExplosion: true,
      life: 40, // Longer lasting explosion
      isPlayerExplosion: true
    })
  }

  // Create many particle effects for spaceship explosion
  for (let i = 0; i < 50; i++) {
    const angle = (Math.PI * 2 * i) / 50
    const speed = Math.random() * 10 + 5
    particles.push({
      x: player.x,
      y: player.y,
      vx: Math.cos(angle) * speed,
      vy: Math.sin(angle) * speed,
      size: Math.random() * 8 + 2,
      color: Math.random() > 0.5 ? '#ff0000' : '#ff6600',
      life: 60,
      isPlayerExplosion: true
    })
  }

  // Create debris particles (ship fragments)
  for (let i = 0; i < 20; i++) {
    particles.push({
      x: player.x,
      y: player.y,
      vx: (Math.random() - 0.5) * 15,
      vy: (Math.random() - 0.5) * 15,
      size: Math.random() * 6 + 3,
      color: '#00ffff',
      life: 40,
      isPlayerExplosion: true
    })
  }
}

// Spawn new asteroid
const spawnNewAsteroid = () => {
  const availableProjects = projects.value.filter(p => !p.discovered)
  if (availableProjects.length === 0) return

  const project = availableProjects[Math.floor(Math.random() * availableProjects.length)]
  const asteroid = {
    x: Math.random() * (gameWidth - 60) + 30,
    y: -60,
    width: 60,
    height: 60,
    speed: Math.random() * 1.5 + 0.5,
    rotation: 0,
    rotationSpeed: (Math.random() - 0.5) * 0.05,
    color: project.color,
    projectId: project.id,
    project: project,
    health: project.maxHealth,
    maxHealth: project.maxHealth,
    discovered: false,
    vertices: generateAsteroidShape()
  }
  asteroids.push(asteroid)
}

// Update particles
const updateParticles = () => {
  for (let i = particles.length - 1; i >= 0; i--) {
    const particle = particles[i]

    particle.x += particle.vx
    particle.y += particle.vy
    particle.life--

    if (particle.life <= 0) {
      particles.splice(i, 1)
    }
  }
}

// Update stars
const updateStars = () => {
  stars.forEach(star => {
    star.y += star.speed
    if (star.y > gameHeight) {
      star.y = 0
      star.x = Math.random() * gameWidth
    }
  })
}

// Draw game objects
const draw = () => {
  // Clear canvas
  gameCtx.fillStyle = '#000814'
  gameCtx.fillRect(0, 0, gameWidth, gameHeight)

  // Draw background image if available
  if (images.background && imagesLoaded) {
    gameCtx.drawImage(images.background, 0, 0, gameWidth, gameHeight)
  } else {
    // Fallback: draw gradient background
    const gradient = gameCtx.createLinearGradient(0, 0, 0, gameHeight)
    gradient.addColorStop(0, '#000814')
    gradient.addColorStop(1, '#001122')
    gameCtx.fillStyle = gradient
    gameCtx.fillRect(0, 0, gameWidth, gameHeight)
  }

  // Draw stars (only if no background image)
  if (!images.background || !imagesLoaded) {
    stars.forEach(star => {
      gameCtx.fillStyle = `rgba(255, 255, 255, ${star.brightness})`
      gameCtx.beginPath()
      gameCtx.arc(star.x, star.y, star.size, 0, Math.PI * 2)
      gameCtx.fill()
    })
  }

  // Draw particles
  particles.forEach(particle => {
    if (particle.isExplosion && particle.image) {
      // Draw explosion image
      const opacity = particle.life / (particle.isPlayerExplosion ? 40 : 20)
      gameCtx.globalAlpha = opacity
      gameCtx.drawImage(
        particle.image,
        particle.x - particle.size / 2,
        particle.y - particle.size / 2,
        particle.size,
        particle.size
      )
      gameCtx.globalAlpha = 1
    } else {
      // Draw regular particle
      const opacity = particle.life / (particle.isPlayerExplosion ? 60 : 30)
      gameCtx.fillStyle = particle.color + Math.floor(opacity * 255).toString(16).padStart(2, '0')
      gameCtx.beginPath()
      gameCtx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2)
      gameCtx.fill()
    }
  })

  // Draw asteroids
  asteroids.forEach(asteroid => {
    drawAsteroid(asteroid)
  })

  // Draw bullets
  bullets.forEach(bullet => {
    gameCtx.fillStyle = bullet.color
    gameCtx.shadowColor = bullet.color
    gameCtx.shadowBlur = 10
    gameCtx.fillRect(bullet.x - bullet.width/2, bullet.y - bullet.height/2, bullet.width, bullet.height)
    gameCtx.shadowBlur = 0
  })

  // Draw player (only if game is not over)
  if (!gameOver.value) {
    drawPlayer()
  }

  // Draw health bars above asteroids
  asteroids.forEach(asteroid => {
    drawHealthBar(asteroid)
  })
}

// Draw player spaceship
const drawPlayer = () => {
  gameCtx.save()
  gameCtx.translate(player.x, player.y)

  // Draw spaceship image if available
  if (images.spaceship && imagesLoaded) {
    gameCtx.drawImage(
      images.spaceship,
      -player.width / 2,
      -player.height / 2,
      player.width,
      player.height
    )
  } else {
    // Fallback: draw hand-drawn spaceship
    gameCtx.fillStyle = player.color
    gameCtx.shadowColor = player.color
    gameCtx.shadowBlur = 15

    // Main body (triangle)
    gameCtx.beginPath()
    gameCtx.moveTo(0, -25)
    gameCtx.lineTo(-15, 15)
    gameCtx.lineTo(15, 15)
    gameCtx.closePath()
    gameCtx.fill()

    // Wings
    gameCtx.fillStyle = '#0099cc'
    gameCtx.beginPath()
    gameCtx.moveTo(-15, 10)
    gameCtx.lineTo(-25, 20)
    gameCtx.lineTo(-10, 20)
    gameCtx.closePath()
    gameCtx.fill()

    gameCtx.beginPath()
    gameCtx.moveTo(15, 10)
    gameCtx.lineTo(25, 20)
    gameCtx.lineTo(10, 20)
    gameCtx.closePath()
    gameCtx.fill()

    // Engine glow
    gameCtx.fillStyle = '#ff6600'
    gameCtx.shadowColor = '#ff6600'
    gameCtx.beginPath()
    gameCtx.arc(0, 15, 5, 0, Math.PI * 2)
    gameCtx.fill()
  }

  gameCtx.restore()
}

// Draw asteroid
const drawAsteroid = (asteroid) => {
  gameCtx.save()
  gameCtx.translate(asteroid.x, asteroid.y)
  gameCtx.rotate(asteroid.rotation)

  // Draw asteroid image if available
  const asteroidImage = images.asteroids[asteroid.projectId]
  if (asteroidImage && imagesLoaded) {
    gameCtx.drawImage(
      asteroidImage,
      -asteroid.width / 2,
      -asteroid.height / 2,
      asteroid.width,
      asteroid.height
    )
  } else {
    // Fallback: draw hand-drawn asteroid
    gameCtx.fillStyle = asteroid.color
    gameCtx.strokeStyle = asteroid.color
    gameCtx.shadowColor = asteroid.color
    gameCtx.shadowBlur = 20

    gameCtx.beginPath()
    asteroid.vertices.forEach((vertex, index) => {
      if (index === 0) {
        gameCtx.moveTo(vertex.x, vertex.y)
      } else {
        gameCtx.lineTo(vertex.x, vertex.y)
      }
    })
    gameCtx.closePath()
    gameCtx.stroke()

    // Fill with transparency
    gameCtx.fillStyle = asteroid.color + '40'
    gameCtx.fill()
  }

  // Draw project name
  gameCtx.restore()
  gameCtx.save()
  gameCtx.translate(asteroid.x, asteroid.y)
  gameCtx.fillStyle = '#ffffff'
  gameCtx.font = 'bold 12px Arial'
  gameCtx.textAlign = 'center'
  gameCtx.shadowBlur = 5
  gameCtx.shadowColor = '#000000'
  gameCtx.fillText(asteroid.project.name, 0, -asteroid.height / 2 - 10)

  gameCtx.restore()
}

// Draw health bar above asteroid
const drawHealthBar = (asteroid) => {
  const barWidth = 40
  const barHeight = 4
  const x = asteroid.x - barWidth / 2
  const y = asteroid.y - 35

  // Background
  gameCtx.fillStyle = '#333333'
  gameCtx.fillRect(x, y, barWidth, barHeight)

  // Health fill
  const healthPercent = asteroid.health / asteroid.maxHealth
  const healthColor = healthPercent > 0.5 ? '#00ff00' : healthPercent > 0.25 ? '#ffff00' : '#ff0000'
  gameCtx.fillStyle = healthColor
  gameCtx.fillRect(x, y, barWidth * healthPercent, barHeight)

  // Border
  gameCtx.strokeStyle = '#ffffff'
  gameCtx.strokeRect(x, y, barWidth, barHeight)
}

// Main game loop
const animate = () => {
  if (!gamePaused.value && !gameOver.value) {
    updatePlayer()
    updateBullets()
    updateAsteroids()
    updateParticles()
    updateStars()
  }

  draw()
  requestAnimationFrame(animate)
}

// Handle window resize
const handleResize = () => {
  gameWidth = window.innerWidth
  gameHeight = window.innerHeight * 0.6

  if (gameCanvas) {
    gameCanvas.width = gameWidth
    gameCanvas.height = gameHeight
  }
}

// Project modal methods
const closeProjectModal = () => {
  selectedProject.value = null
  resumeGame()
}

const resumeGame = () => {
  gamePaused.value = false
  selectedProject.value = null
}

// Restart game
const restartGame = () => {
  // Reset game state
  score.value = 0
  playerHealth.value = maxHealth.value
  projectsFound.value = 0
  gameOver.value = false
  victory.value = false
  gamePaused.value = false
  selectedProject.value = null

  // Reset projects discovered status
  projects.value.forEach(project => {
    project.discovered = false
  })

  // Reset arrays
  bullets = []
  asteroids = []
  stars = []
  particles = []

  // Reinitialize game
  initGame()
}

// Initialize game
onMounted(async () => {
  await nextTick()
  await fetchProjects()
  await initGame()
  animate()

  // Add event listeners
  window.addEventListener('keydown', handleKeyDown)
  window.addEventListener('keyup', handleKeyUp)
  window.addEventListener('resize', handleResize)
})

// Cleanup
onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
  window.removeEventListener('keyup', handleKeyUp)
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
/* Space Game Section */
.space-game-section {
  min-height: 100vh;
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  color: white;
  padding: 2rem;
  position: relative;
  overflow: hidden;
}

.space-game-section::before {
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

/* Game Header */
.game-header {
  text-align: center;
  margin-bottom: 2rem;
  position: relative;
  z-index: 2;
}

.game-title {
  font-size: clamp(2.5rem, 6vw, 4rem);
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

.game-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 2rem;
  text-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
}

.game-stats {
  display: flex;
  justify-content: center;
  gap: 3rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}

.stat {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem 2rem;
  background: rgba(0, 0, 0, 0.6);
  border: 2px solid rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.stat:hover {
  border-color: rgba(0, 255, 255, 0.5);
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
}

.stat-label {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #00ffff;
  text-shadow: 0 0 15px currentColor;
}

/* Game Container */
.game-container {
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
  z-index: 2;
  height: 60vh;
  min-height: 400px;
}

.game-canvas {
  width: 100%;
  height: 100%;
  border-radius: 20px;
  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.5),
    0 0 100px rgba(0, 255, 255, 0.1),
    inset 0 0 50px rgba(255, 255, 255, 0.05);
  border: 2px solid rgba(0, 255, 255, 0.3);
  background: #000814;
  display: block;
  image-rendering: optimizeSpeed;
  image-rendering: -moz-crisp-edges;
  image-rendering: -webkit-optimize-contrast;
  image-rendering: pixelated;
}

/* Game UI Overlay */
.game-ui {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  padding: 2rem;
}

.game-ui > * {
  pointer-events: auto;
}

/* Health Bar */
.health-bar-container {
  position: absolute;
  top: 2rem;
  left: 2rem;
  background: rgba(0, 0, 0, 0.8);
  padding: 1rem;
  border-radius: 10px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  min-width: 200px;
}

.health-label {
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.health-bar {
  width: 100%;
  height: 20px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.health-fill {
  height: 100%;
  background: linear-gradient(90deg, #ff0000, #ff6600, #00ff00);
  transition: width 0.3s ease;
  box-shadow: 0 0 10px currentColor;
}

/* Controls Info */
.controls-info {
  position: absolute;
  top: 2rem;
  right: 2rem;
  background: rgba(0, 0, 0, 0.8);
  padding: 1rem;
  border-radius: 10px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
}

/* Desktop/Mobile Visibility */
.desktop-only {
  display: block;
}

.mobile-only {
  display: none;
}

/* Mobile Controls */
.mobile-controls {
  position: absolute;
  bottom: 1rem;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-between;
  padding: 0 1rem;
  pointer-events: none;
  z-index: 10;
}

.movement-controls.left-controls {
  order: 1;
  position: absolute;
  left: 1rem;
  bottom: 1rem;
  pointer-events: auto;
}

.fire-controls.right-controls {
  order: 2;
  position: absolute;
  right: 1rem;
  bottom: 1rem;
  pointer-events: auto;
}

.movement-controls {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  pointer-events: auto;
}

.left-controls {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 0.3rem;
  background: rgba(0, 0, 0, 0.5);
  padding: 0.3rem;
  border-radius: 25px;
  border: 1px solid rgba(0, 150, 255, 0.3);
  backdrop-filter: blur(5px);
}

.right-controls {
  pointer-events: auto;
  background: rgba(0, 0, 0, 0.5);
  padding: 0.3rem;
  border-radius: 35px;
  border: 1px solid rgba(255, 100, 100, 0.3);
  backdrop-filter: blur(5px);
}

.mobile-btn {
  background: rgba(0, 0, 0, 0.7);
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 15px;
  color: rgba(255, 255, 255, 0.9);
  cursor: pointer;
  transition: all 0.2s ease;
  backdrop-filter: blur(10px);
  user-select: none;
  -webkit-user-select: none;
  -webkit-tap-highlight-color: transparent;
  touch-action: manipulation;
}

.mobile-btn:active {
  background: rgba(0, 255, 255, 0.3);
  border-color: rgba(0, 255, 255, 0.6);
  transform: scale(0.95);
}

.move-btn {
  width: 45px;
  height: 45px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  font-weight: bold;
  background: rgba(0, 100, 200, 0.7);
  border-color: rgba(0, 150, 255, 0.5);
  border-radius: 50%;
  box-shadow: 0 4px 15px rgba(0, 150, 255, 0.3);
}

.move-btn:active {
  background: rgba(0, 150, 255, 0.8);
  border-color: rgba(0, 200, 255, 0.8);
  transform: scale(0.9);
}

.fire-btn {
  width: 55px;
  height: 55px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.1rem;
  font-size: 0.9rem;
  font-weight: bold;
  background: rgba(255, 0, 0, 0.7);
  border-color: rgba(255, 100, 100, 0.5);
  border-radius: 50%;
  box-shadow: 0 4px 20px rgba(255, 0, 0, 0.4);
}

.fire-btn:active {
  background: rgba(255, 0, 0, 0.9);
  border-color: rgba(255, 100, 100, 0.8);
}

.btn-icon {
  font-size: 1.8rem;
}

.btn-label {
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.control-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.5rem;
}

.control-item:last-child {
  margin-bottom: 0;
}

.key {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
  border: 1px solid rgba(255, 255, 255, 0.3);
  padding: 0.3rem 0.6rem;
  border-radius: 5px;
  font-family: monospace;
  font-weight: bold;
  color: #00ffff;
  min-width: 3rem;
  text-align: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.control-desc {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
}

/* Game Over Screen */
.game-over-screen,
.victory-screen {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 85%;
  max-width: 320px;
  animation: screenAppear 0.5s ease-out;
}

@keyframes screenAppear {
  from {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.8);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
  }
}

.game-over-content,
.victory-content {
  background: linear-gradient(135deg,
    rgba(10, 10, 30, 0.95) 0%,
    rgba(26, 0, 51, 0.95) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 15px;
  padding: 1.5rem;
  text-align: center;
  backdrop-filter: blur(20px);
  box-shadow:
    0 10px 30px rgba(0, 0, 0, 0.5),
    0 0 50px rgba(255, 255, 255, 0.1);
}

.game-over-title {
  color: #ff6b6b;
  font-size: 1.8rem;
  margin-bottom: 0.8rem;
  text-shadow: 0 0 15px currentColor;
  animation: titleShake 0.5s ease-in-out;
}

@keyframes titleShake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

.victory-title {
  color: #00ff00;
  font-size: 1.8rem;
  margin-bottom: 0.8rem;
  text-shadow: 0 0 15px currentColor;
  animation: titlePulse 1s ease-in-out infinite;
}

@keyframes titlePulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

.game-over-message,
.victory-message {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1rem;
  margin-bottom: 1rem;
}

.game-over-stats,
.victory-stats {
  margin-bottom: 1rem;
}

.stat-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.6rem 0.8rem;
  margin-bottom: 0.4rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-row:last-child {
  margin-bottom: 0;
}

.stat-label {
  color: rgba(255, 255, 255, 0.8);
  font-size: 1rem;
}

.stat-value {
  color: #ffd60a;
  font-weight: bold;
  font-size: 1.1rem;
  text-shadow: 0 0 10px currentColor;
}

.restart-btn {
  margin-top: 1rem;
  padding: 0.8rem 1.5rem;
  background: linear-gradient(45deg, #00ffff, #0099cc);
  border: none;
  border-radius: 20px;
  color: #000000;
  font-weight: bold;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 3px 15px rgba(0, 255, 255, 0.3);
}

.restart-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 255, 255, 0.5);
  background: linear-gradient(45deg, #0099cc, #00ffff);
}

/* Project Modal */
.project-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.9);
  backdrop-filter: blur(20px);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.project-card {
  background: linear-gradient(135deg,
    rgba(10, 10, 30, 0.95) 0%,
    rgba(26, 0, 51, 0.95) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  border: 2px solid rgba(255, 214, 10, 0.4);
  border-radius: 20px;
  padding: 2.5rem;
  max-width: 600px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  box-shadow:
    0 20px 60px rgba(0, 0, 0, 0.5),
    0 0 100px rgba(255, 214, 10, 0.1);
  animation: cardSlideIn 0.3s ease-out;
}

@keyframes cardSlideIn {
  from {
    opacity: 0;
    transform: translateY(-50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.project-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.project-header h2 {
  color: #ffd60a;
  font-size: 2rem;
  margin: 0;
  text-shadow: 0 0 20px currentColor;
}

.close-btn {
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

.close-btn:hover {
  color: #ff00ff;
  background: rgba(255, 0, 255, 0.1);
  transform: rotate(90deg);
}

.project-content {
  margin-bottom: 2rem;
}

.project-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.project-description {
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
  font-size: 1.1rem;
  margin-bottom: 1.5rem;
}

.project-tech {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
  margin-bottom: 1.5rem;
}

.tech-tag {
  background: linear-gradient(135deg,
    rgba(255, 214, 10, 0.1) 0%,
    rgba(255, 0, 255, 0.1) 100%);
  border: 1px solid rgba(255, 214, 10, 0.3);
  color: #ffffff;
  padding: 0.6rem 1.2rem;
  border-radius: 25px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.tech-tag:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(255, 214, 10, 0.3);
  border-color: rgba(255, 214, 10, 0.6);
}

.project-links {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.project-link {
  display: inline-flex;
  align-items: center;
  padding: 0.8rem 1.5rem;
  background: linear-gradient(45deg, #00ffff, #0099cc);
  color: #000000;
  text-decoration: none;
  border-radius: 20px;
  font-weight: 600;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 0.9rem;
}

.project-link:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 255, 255, 0.5);
  background: linear-gradient(45deg, #0099cc, #00ffff);
}

.resume-btn {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(45deg, #ff00ff, #9900cc);
  border: none;
  border-radius: 30px;
  color: #ffffff;
  font-weight: bold;
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  box-shadow: 0 5px 20px rgba(255, 0, 255, 0.3);
}

.resume-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(255, 0, 255, 0.5);
  background: linear-gradient(45deg, #9900cc, #ff00ff);
}

/* Responsive Design */
@media (max-width: 768px) {
  .space-game-section {
    padding: 1rem;
  }

  .game-title {
    font-size: 2rem;
  }

  .game-subtitle {
    font-size: 1rem;
  }

  .game-stats {
    gap: 1rem;
  }

  .stat {
    padding: 0.8rem 1.2rem;
  }

  .stat-value {
    font-size: 1.5rem;
  }

  .health-bar-container {
    position: static;
    margin-bottom: 1rem;
  }

  .game-ui {
    position: static;
    padding: 1rem;
  }

  /* Hide desktop controls on mobile */
  .desktop-only {
    display: none;
  }

  /* Show mobile controls on mobile */
  .mobile-only {
    display: block;
  }

  .controls-info {
    display: none;
  }

  .mobile-controls {
    position: absolute;
    bottom: 1rem;
    left: 0;
    right: 0;
    padding: 0 1rem;
    justify-content: space-between;
  }

  .movement-controls.left-controls {
    left: 1rem;
    bottom: 1rem;
  }

  .fire-controls.right-controls {
    right: 1rem;
    bottom: 1rem;
  }

  .game-over-screen,
  .victory-screen,
  .project-card {
    margin: 0.5rem;
    padding: 1rem;
  }

  .project-header h2,
  .game-over-title,
  .victory-title {
    font-size: 1.4rem;
    margin-bottom: 0.6rem;
  }

  .project-links {
    flex-direction: column;
  }
}

@media (max-width: 480px) {
  .game-title {
    font-size: 1.5rem;
  }

  .stat-value {
    font-size: 1.2rem;
  }

  .mobile-controls {
    bottom: 0.5rem;
    padding: 0 0.5rem;
  }

  .movement-controls {
    gap: 0.5rem;
  }

  .move-btn {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
  }

  .fire-btn {
    width: 45px;
    height: 45px;
    font-size: 0.8rem;
    gap: 0.1rem;
  }

  .btn-icon {
    font-size: 1.1rem;
  }

  .btn-label {
    font-size: 0.6rem;
  }
}
</style>
