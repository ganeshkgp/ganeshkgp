<template>
  <div ref="container" class="projects-gallery-3d-container">
    <!-- Loading Screen -->
    <div v-if="loading" class="loading-overlay">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <p>Loading 3D Gallery...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import * as THREE from 'three'
import { PointerLockControls } from 'three/examples/jsm/controls/PointerLockControls.js'

const container = ref(null)
const loading = ref(true)
const emit = defineEmits(['projectSelected'])

let scene, camera, renderer, controls
let projectObjects = []
let moveForward = false
let moveBackward = false
let moveLeft = false
let moveRight = false
let canJump = false
let prevTime = performance.now()
const velocity = new THREE.Vector3()
const direction = new THREE.Vector3()
let animationId

// Sample projects data - this would come from props or API
const projects = [
  {
    id: 1,
    name: 'E-Commerce Platform',
    description: 'Full-stack e-commerce solution with real-time inventory management',
    image: '/images/projects/ecommerce.jpg',
    technologies: ['Laravel', 'Vue.js', 'MySQL'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 0, y: 1, z: -5 },
    color: '#00ffff'
  },
  {
    id: 2,
    name: 'Mobile Banking App',
    description: 'Cross-platform mobile banking application',
    image: '/images/projects/banking.jpg',
    technologies: ['Flutter', 'Node.js'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 5, y: 1, z: 0 },
    color: '#ff00ff'
  },
  {
    id: 3,
    name: '3D Game Engine',
    description: 'Custom Unity game engine with physics simulation',
    image: '/images/projects/game.jpg',
    technologies: ['Unity', 'C#'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: -5, y: 1, z: 0 },
    color: '#ffff00'
  },
  {
    id: 4,
    name: 'AI Dashboard',
    description: 'Real-time analytics dashboard with ML predictions',
    image: '/images/projects/analytics.jpg',
    technologies: ['Python', 'Vue.js'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 0, y: 1, z: 5 },
    color: '#00ff00'
  },
  {
    id: 5,
    name: 'Social Media Platform',
    description: 'Scalable social networking with real-time messaging',
    image: '/images/projects/social.jpg',
    technologies: ['Laravel', 'Vue.js'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: 7, y: 1, z: 7 },
    color: '#ff6b6b'
  },
  {
    id: 6,
    name: 'IoT Control System',
    description: 'Smart home automation with voice control',
    image: '/images/projects/iot.jpg',
    technologies: ['Flutter', 'Python'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example',
    position: { x: -7, y: 1, z: 7 },
    color: '#4ecdc4'
  }
]

onMounted(() => {
  if (container.value) {
    initThreeJS()
    animate()
    setTimeout(() => {
      loading.value = false
    }, 2000)
  }
})

onBeforeUnmount(() => {
  if (animationId) {
    cancelAnimationFrame(animationId)
  }
  if (renderer) {
    renderer.dispose()
  }
  document.removeEventListener('keydown', onKeyDown)
  document.removeEventListener('keyup', onKeyUp)
})

const initThreeJS = () => {
  // Scene setup
  scene = new THREE.Scene()
  scene.fog = new THREE.Fog(0x0a0a0a, 10, 50)
  scene.background = new THREE.Color(0x0a0a0a)

  // Camera setup
  const aspect = container.value.clientWidth / container.value.clientHeight
  camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000)
  camera.position.set(0, 2, 10)

  // Renderer setup
  renderer = new THREE.WebGLRenderer({
    antialias: true,
    alpha: true,
    powerPreference: "high-performance"
  })
  renderer.setSize(container.value.clientWidth, container.value.clientHeight)
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
  renderer.shadowMap.enabled = true
  renderer.shadowMap.type = THREE.PCFSoftShadowMap
  container.value.appendChild(renderer.domElement)

  // Pointer Lock Controls
  controls = new PointerLockControls(camera, renderer.domElement)

  // Create environment
  createEnvironment()

  // Create project displays
  createProjectDisplays()

  // Lighting
  createLighting()

  // Event listeners
  setupEventListeners()

  // Instructions overlay
  showInstructions()
}

const createEnvironment = () => {
  // Floor
  const floorGeometry = new THREE.PlaneGeometry(100, 100)
  const floorMaterial = new THREE.MeshLambertMaterial({
    color: 0x111111,
    transparent: true,
    opacity: 0.8
  })
  const floor = new THREE.Mesh(floorGeometry, floorMaterial)
  floor.rotation.x = -Math.PI / 2
  floor.receiveShadow = true
  scene.add(floor)

  // Grid helper
  const gridHelper = new THREE.GridHelper(100, 50, 0x444444, 0x222222)
  scene.add(gridHelper)

  // Walls (optional - for gallery feel)
  const wallMaterial = new THREE.MeshLambertMaterial({
    color: 0x1a1a1a,
    transparent: true,
    opacity: 0.3
  })

  // Back wall
  const backWallGeometry = new THREE.PlaneGeometry(100, 20)
  const backWall = new THREE.Mesh(backWallGeometry, wallMaterial)
  backWall.position.set(0, 10, -20)
  scene.add(backWall)

  // Side walls
  const sideWallGeometry = new THREE.PlaneGeometry(40, 20)
  const leftWall = new THREE.Mesh(sideWallGeometry, wallMaterial)
  leftWall.position.set(-20, 10, 0)
  leftWall.rotation.y = Math.PI / 2
  scene.add(leftWall)

  const rightWall = new THREE.Mesh(sideWallGeometry, wallMaterial)
  rightWall.position.set(20, 10, 0)
  rightWall.rotation.y = -Math.PI / 2
  scene.add(rightWall)
}

const createProjectDisplays = () => {
  projects.forEach((project, index) => {
    // Create project pedestal
    const pedestalGeometry = new THREE.BoxGeometry(2, 0.2, 2)
    const pedestalMaterial = new THREE.MeshPhongMaterial({
      color: 0x333333,
      emissive: project.color,
      emissiveIntensity: 0.1
    })
    const pedestal = new THREE.Mesh(pedestalGeometry, pedestalMaterial)
    pedestal.position.set(project.position.x, 0.1, project.position.z)
    pedestal.castShadow = true
    pedestal.receiveShadow = true
    scene.add(pedestal)

    // Create project display (floating cube/frame)
    const displayGeometry = new THREE.BoxGeometry(2.5, 2, 0.1)
    const displayMaterial = new THREE.MeshPhongMaterial({
      color: project.color,
      emissive: project.color,
      emissiveIntensity: 0.2,
      transparent: true,
      opacity: 0.8
    })
    const display = new THREE.Mesh(displayGeometry, displayMaterial)
    display.position.set(project.position.x, 2, project.position.z)
    display.castShadow = true
    display.userData = project
    scene.add(display)

    // Add project info sprite
    createProjectInfoSprite(project, display)

    // Add glow effect
    const glowGeometry = new THREE.SphereGeometry(1.5, 16, 16)
    const glowMaterial = new THREE.MeshBasicMaterial({
      color: project.color,
      transparent: true,
      opacity: 0.1,
      side: THREE.BackSide
    })
    const glow = new THREE.Mesh(glowGeometry, glowMaterial)
    glow.position.copy(display.position)
    scene.add(glow)

    // Add to interactive objects
    projectObjects.push(display)

    // Add floating animation
    display.userData.baseY = display.position.y
    display.userData.floatSpeed = 0.5 + Math.random() * 0.5
    display.userData.floatAmount = 0.1 + Math.random() * 0.1
  })
}

const createProjectInfoSprite = (project, parent) => {
  const canvas = document.createElement('canvas')
  const context = canvas.getContext('2d')
  canvas.width = 512
  canvas.height = 256

  // Background
  context.fillStyle = 'rgba(0, 0, 0, 0.8)'
  context.fillRect(0, 0, canvas.width, canvas.height)

  // Project name
  context.fillStyle = '#ffffff'
  context.font = 'bold 32px Inter'
  context.textAlign = 'center'
  context.fillText(project.name, 256, 60)

  // Technologies
  context.font = '20px Inter'
  context.fillStyle = project.color
  const techText = project.technologies.join(' • ')
  context.fillText(techText, 256, 100)

  // Description
  context.fillStyle = '#cccccc'
  context.font = '18px Inter'
  const words = project.description.split(' ')
  let line = ''
  let y = 140
  const lineHeight = 25
  const maxWidth = 450

  for (let n = 0; n < words.length; n++) {
    const testLine = line + words[n] + ' '
    const metrics = context.measureText(testLine)
    const testWidth = metrics.width
    if (testWidth > maxWidth && n > 0) {
      context.fillText(line, 256, y)
      line = words[n] + ' '
      y += lineHeight
    } else {
      line = testLine
    }
  }
  context.fillText(line, 256, y)

  // "Click to view" text
  context.fillStyle = project.color
  context.font = 'italic 16px Inter'
  context.fillText('Click to view →', 256, 230)

  const texture = new THREE.CanvasTexture(canvas)
  const spriteMaterial = new THREE.SpriteMaterial({
    map: texture,
    transparent: true,
    alphaTest: 0.01
  })

  const sprite = new THREE.Sprite(spriteMaterial)
  sprite.scale.set(4, 2, 1)
  sprite.position.y = 1.5
  sprite.position.z = 0.06
  parent.add(sprite)
}

const createLighting = () => {
  // Ambient light
  const ambientLight = new THREE.AmbientLight(0x404040, 0.3)
  scene.add(ambientLight)

  // Main directional light (sun)
  const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5)
  directionalLight.position.set(10, 20, 10)
  directionalLight.castShadow = true
  directionalLight.shadow.camera.near = 0.1
  directionalLight.shadow.camera.far = 50
  directionalLight.shadow.camera.left = -20
  directionalLight.shadow.camera.right = 20
  directionalLight.shadow.camera.top = 20
  directionalLight.shadow.camera.bottom = -20
  scene.add(directionalLight)

  // Colored point lights for each project
  projects.forEach((project, index) => {
    const pointLight = new THREE.PointLight(project.color, 0.5, 10)
    pointLight.position.set(project.position.x, 3, project.position.z)
    scene.add(pointLight)
  })

  // Spotlight on each project
  projectObjects.forEach((obj, index) => {
    const spotlight = new THREE.SpotLight(0xffffff, 0.3)
    spotlight.position.set(obj.position.x, 8, obj.position.z)
    spotlight.target = obj
    spotlight.angle = Math.PI / 6
    spotlight.penumbra = 0.2
    spotlight.castShadow = true
    scene.add(spotlight)
  })
}

const setupEventListeners = () => {
  // Keyboard controls
  document.addEventListener('keydown', onKeyDown)
  document.addEventListener('keyup', onKeyUp)

  // Window resize
  window.addEventListener('resize', onWindowResize)

  // Mouse click to lock controls
  renderer.domElement.addEventListener('click', () => {
    controls.lock()
  })

  // Controls events
  controls.addEventListener('lock', () => {
    console.log('Controls locked')
  })

  controls.addEventListener('unlock', () => {
    console.log('Controls unlocked')
  })

  // Raycasting for project selection
  renderer.domElement.addEventListener('click', onMouseClick)
}

const onKeyDown = (event) => {
  switch (event.code) {
    case 'ArrowUp':
    case 'KeyW':
      moveForward = true
      break
    case 'ArrowDown':
    case 'KeyS':
      moveBackward = true
      break
    case 'ArrowLeft':
    case 'KeyA':
      moveLeft = true
      break
    case 'ArrowRight':
    case 'KeyD':
      moveRight = true
      break
    case 'Space':
      if (canJump === true) velocity.y += 10
      canJump = false
      break
  }
}

const onKeyUp = (event) => {
  switch (event.code) {
    case 'ArrowUp':
    case 'KeyW':
      moveForward = false
      break
    case 'ArrowDown':
    case 'KeyS':
      moveBackward = false
      break
    case 'ArrowLeft':
    case 'KeyA':
      moveLeft = false
      break
    case 'ArrowRight':
    case 'KeyD':
      moveRight = false
      break
  }
}

const onMouseClick = (event) => {
  // Raycasting to detect project selection
  const raycaster = new THREE.Raycaster()
  const mouse = new THREE.Vector2(0, 0) // Center of screen for pointer lock

  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObjects(projectObjects)

  if (intersects.length > 0) {
    const selectedProject = intersects[0].object.userData
    emit('projectSelected', selectedProject)
  }
}

const onWindowResize = () => {
  if (!container.value) return

  const width = container.value.clientWidth
  const height = container.value.clientHeight

  camera.aspect = width / height
  camera.updateProjectionMatrix()
  renderer.setSize(width, height)
}

const showInstructions = () => {
  // You can add an instructions overlay here
  console.log('Click to enter 3D gallery. Use W/A/S/D to move, mouse to look around.')
}

const animate = () => {
  animationId = requestAnimationFrame(animate)

  const time = performance.now()
  const delta = (time - prevTime) / 1000

  // Movement
  velocity.x -= velocity.x * 10.0 * delta
  velocity.z -= velocity.z * 10.0 * delta

  direction.z = Number(moveForward) - Number(moveBackward)
  direction.x = Number(moveRight) - Number(moveLeft)
  direction.normalize()

  if (moveForward || moveBackward) velocity.z -= direction.z * 20.0 * delta
  if (moveLeft || moveRight) velocity.x -= direction.x * 20.0 * delta

  // Apply movement
  if (controls.isLocked === true) {
    controls.moveRight(-velocity.x * delta)
    controls.moveForward(-velocity.z * delta)
  }

  // Animate project displays
  projectObjects.forEach((obj, index) => {
    obj.position.y = obj.userData.baseY + Math.sin(time * 0.001 * obj.userData.floatSpeed) * obj.userData.floatAmount
    obj.rotation.y += 0.005
  })

  renderer.render(scene, camera)
  prevTime = time
}
</script>

<style scoped>
.projects-gallery-3d-container {
  width: 100%;
  height: 100%;
  position: relative;
  cursor: crosshair;
}

.projects-gallery-3d-container canvas {
  display: block;
  width: 100%;
  height: 100%;
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 10, 10, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  transition: opacity 0.5s ease;
}

.loading-content {
  text-align: center;
  color: #ffffff;
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
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.7);
}
</style>