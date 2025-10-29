<template>
  <div ref="container" class="skills-visualization-container">
    <div class="loading-overlay" v-if="isLoading">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <p>Initializing 3D Environment...</p>
      </div>
    </div>
    <div class="skill-info-panel" v-if="hoveredSkill">
      <h3>{{ hoveredSkill.name }}</h3>
      <p>{{ hoveredSkill.experience }}</p>
      <div class="skill-progress">
        <div class="progress-bar" :style="{ width: (hoveredSkill.proficiency * 100) + '%' }"></div>
      </div>
      <span>{{ Math.round(hoveredSkill.proficiency * 100) }}% Proficiency</span>
    </div>
    <div class="controls-hint">
      <p>🖱️ Click & Drag to rotate • Scroll to zoom • Click objects to explore</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js'
import audioManager from '../utils/AudioManager.js'

const container = ref(null)
const isLoading = ref(true)
const hoveredSkill = ref(null)

let scene, camera, renderer, controls
let skillObjects = []
let animationId
let particles, gridHelper, ambientLights
const mouse = new THREE.Vector2()
const raycaster = new THREE.Raycaster()
let wasHovering = false

const skills = [
  {
    name: 'PHP/Laravel',
    experience: 'Backend Expert',
    icon: '🐘',
    color: '#777BB4',
    position: { x: -4, y: 2, z: 0 },
    proficiency: 0.95,
    type: 'backend',
    model: 'server'
  },
  {
    name: 'Vue.js',
    experience: 'Frontend Specialist',
    icon: '💚',
    color: '#4FC08D',
    position: { x: 4, y: 2, z: 0 },
    proficiency: 0.90,
    type: 'frontend',
    model: 'atom'
  },
  {
    name: 'Python',
    experience: 'Multi-purpose Developer',
    icon: '🐍',
    color: '#3776AB',
    position: { x: 0, y: 4, z: -2 },
    proficiency: 0.85,
    type: 'language',
    model: 'dna'
  },
  {
    name: 'Flutter',
    experience: 'Cross-platform Expert',
    icon: '📱',
    color: '#02569B',
    position: { x: -4, y: -1, z: 2 },
    proficiency: 0.80,
    type: 'mobile',
    model: 'cube'
  },
  {
    name: 'Unity',
    experience: 'Game Developer',
    icon: '🎮',
    color: '#000000',
    position: { x: 4, y: -1, z: 2 },
    proficiency: 0.75,
    type: 'gaming',
    model: 'gamepad'
  },
  {
    name: 'Three.js',
    experience: '3D Graphics Specialist',
    icon: '🎨',
    color: '#000000',
    position: { x: 0, y: -3, z: 3 },
    proficiency: 0.88,
    type: 'graphics',
    model: 'geometry'
  },
  {
    name: 'MySQL/PostgreSQL',
    experience: 'Database Expert',
    icon: '🗄️',
    color: '#4479A1',
    position: { x: -6, y: 0, z: -2 },
    proficiency: 0.92,
    type: 'database',
    model: 'database'
  },
  {
    name: 'AWS/Cloud',
    experience: 'Cloud Architect',
    icon: '☁️',
    color: '#FF9900',
    position: { x: 6, y: 0, z: -2 },
    proficiency: 0.83,
    type: 'cloud',
    model: 'cloud'
  },
  {
    name: 'Docker/DevOps',
    experience: 'DevOps Engineer',
    icon: '🐳',
    color: '#2496ED',
    position: { x: 0, y: 5, z: 0 },
    proficiency: 0.78,
    type: 'devops',
    model: 'container'
  },
  {
    name: 'React/JavaScript',
    experience: 'Frontend Developer',
    icon: '⚛️',
    color: '#61DAFB',
    position: { x: 0, y: -4, z: -3 },
    proficiency: 0.87,
    type: 'frontend',
    model: 'react'
  }
]

onMounted(() => {
  if (container.value) {
    initThreeJS()
    animate()
    setTimeout(() => {
      isLoading.value = false
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
})

const initThreeJS = () => {
  // Scene setup
  scene = new THREE.Scene()
  scene.fog = new THREE.Fog(0x0a0a0a, 8, 30)

  // Camera setup
  const aspect = container.value.clientWidth / container.value.clientHeight
  camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000)
  camera.position.set(0, 2, 12)

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
  renderer.toneMapping = THREE.ACESFilmicToneMapping
  renderer.toneMappingExposure = 1.2
  container.value.appendChild(renderer.domElement)

  // Controls
  controls = new OrbitControls(camera, renderer.domElement)
  controls.enableDamping = true
  controls.dampingFactor = 0.05
  controls.maxDistance = 25
  controls.minDistance = 5
  controls.autoRotate = true
  controls.autoRotateSpeed = 0.3
  controls.enablePan = false

  // Enhanced lighting
  setupLighting()

  // Create skill visualizations
  createSkillVisualizations()

  // Create environment
  createEnvironment()

  // Create particle systems
  createParticleSystems()

  // Event listeners
  window.addEventListener('resize', onWindowResize)
  renderer.domElement.addEventListener('mousemove', onMouseMove)
  renderer.domElement.addEventListener('click', onMouseClick)
}

const setupLighting = () => {
  // Main lighting
  const mainLight = new THREE.DirectionalLight(0x00ffff, 1)
  mainLight.position.set(10, 10, 5)
  mainLight.castShadow = true
  mainLight.shadow.camera.left = -15
  mainLight.shadow.camera.right = 15
  mainLight.shadow.camera.top = 15
  mainLight.shadow.camera.bottom = -15
  scene.add(mainLight)

  // Accent lighting
  const accentLight = new THREE.DirectionalLight(0xff00ff, 0.5)
  accentLight.position.set(-10, 10, -5)
  scene.add(accentLight)

  // Ambient lighting
  const ambientLight = new THREE.AmbientLight(0x404040, 0.3)
  scene.add(ambientLight)

  // Point lights for each skill
  skills.forEach((skill, index) => {
    const light = new THREE.PointLight(skill.color, 0.8, 8)
    light.position.set(skill.position.x, skill.position.y, skill.position.z)
    light.castShadow = true
    scene.add(light)

    // Add light pulsing animation
    skill.light = light
    skill.lightPhase = Math.random() * Math.PI * 2
  })
}

const createSkillVisualizations = () => {
  skills.forEach((skill, index) => {
    let skillObject

    switch (skill.model) {
      case 'server':
        skillObject = createServerVisualization(skill)
        break
      case 'atom':
        skillObject = createAtomVisualization(skill)
        break
      case 'dna':
        skillObject = createDNAVisualization(skill)
        break
      case 'cube':
        skillObject = createCubeVisualization(skill)
        break
      case 'gamepad':
        skillObject = createGamepadVisualization(skill)
        break
      case 'geometry':
        skillObject = createGeometryVisualization(skill)
        break
      case 'database':
        skillObject = createDatabaseVisualization(skill)
        break
      case 'cloud':
        skillObject = createCloudVisualization(skill)
        break
      case 'container':
        skillObject = createContainerVisualization(skill)
        break
      case 'react':
        skillObject = createReactVisualization(skill)
        break
      default:
        skillObject = createDefaultVisualization(skill)
    }

    skillObject.userData = skill
    scene.add(skillObject)
    skillObjects.push(skillObject)
  })
}

const createServerVisualization = (skill) => {
  const group = new THREE.Group()

  // Server rack
  const rackGeometry = new THREE.BoxGeometry(1.5, 3, 0.8)
  const rackMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.1,
    metalness: 0.8,
    roughness: 0.2
  })
  const rack = new THREE.Mesh(rackGeometry, rackMaterial)
  group.add(rack)

  // Server details
  for (let i = 0; i < 4; i++) {
    const serverGeometry = new THREE.BoxGeometry(1.3, 0.6, 0.7)
    const serverMaterial = new THREE.MeshPhongMaterial({
      color: 0x333333,
      emissive: skill.color,
      emissiveIntensity: 0.2
    })
    const server = new THREE.Mesh(serverGeometry, serverMaterial)
    server.position.y = -1 + i * 0.7
    server.position.z = 0.05
    group.add(server)

    // LED lights
    const ledGeometry = new THREE.SphereGeometry(0.02, 8, 8)
    const ledMaterial = new THREE.MeshBasicMaterial({
      color: 0x00ff00,
      emissive: 0x00ff00
    })
    const led = new THREE.Mesh(ledGeometry, ledMaterial)
    led.position.set(0.6, server.position.y, 0.4)
    group.add(led)
  }

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createAtomVisualization = (skill) => {
  const group = new THREE.Group()

  // Nucleus
  const nucleusGeometry = new THREE.IcosahedronGeometry(0.3, 2)
  const nucleusMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.3,
    metalness: 0.7,
    roughness: 0.1
  })
  const nucleus = new THREE.Mesh(nucleusGeometry, nucleusMaterial)
  group.add(nucleus)

  // Electron orbits
  const orbitRadius = 1.2
  for (let i = 0; i < 3; i++) {
    const orbitAngle = (Math.PI * 2 * i) / 3
    const orbitGeometry = new THREE.TorusGeometry(orbitRadius, 0.02, 8, 32)
    const orbitMaterial = new THREE.MeshBasicMaterial({
      color: skill.color,
      opacity: 0.3,
      transparent: true
    })
    const orbit = new THREE.Mesh(orbitGeometry, orbitMaterial)
    orbit.rotation.x = orbitAngle
    group.add(orbit)

    // Electrons
    const electronGeometry = new THREE.SphereGeometry(0.08, 8, 8)
    const electronMaterial = new THREE.MeshBasicMaterial({
      color: 0x00ffff,
      emissive: 0x00ffff
    })
    const electron = new THREE.Mesh(electronGeometry, electronMaterial)

    // Create orbital motion
    const orbitData = {
      electron: electron,
      radius: orbitRadius,
      speed: 0.02 + Math.random() * 0.02,
      angle: Math.random() * Math.PI * 2,
      axis: new THREE.Vector3(
        Math.sin(orbitAngle),
        Math.cos(orbitAngle),
        0
      ).normalize()
    }
    skill.orbitData = orbitData
    group.add(electron)
  }

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createDNAVisualization = (skill) => {
  const group = new THREE.Group()

  // DNA double helix
  const points1 = []
  const points2 = []
  const segments = 20
  const height = 3

  for (let i = 0; i <= segments; i++) {
    const t = i / segments
    const angle = t * Math.PI * 4

    points1.push(new THREE.Vector3(
      Math.cos(angle) * 0.5,
      (t - 0.5) * height,
      Math.sin(angle) * 0.5
    ))

    points2.push(new THREE.Vector3(
      Math.cos(angle + Math.PI) * 0.5,
      (t - 0.5) * height,
      Math.sin(angle + Math.PI) * 0.5
    ))
  }

  // Create helix curves
  const curve1 = new THREE.CatmullRomCurve3(points1)
  const curve2 = new THREE.CatmullRomCurve3(points2)

  // Create tube geometry for helix
  const tubeGeometry1 = new THREE.TubeGeometry(curve1, 64, 0.08, 8, false)
  const tubeGeometry2 = new THREE.TubeGeometry(curve2, 64, 0.08, 8, false)

  const tubeMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.2
  })

  const tube1 = new THREE.Mesh(tubeGeometry1, tubeMaterial)
  const tube2 = new THREE.Mesh(tubeGeometry2, tubeMaterial)
  group.add(tube1)
  group.add(tube2)

  // Add base pairs
  for (let i = 0; i < segments; i += 2) {
    const t = i / segments
    const angle = t * Math.PI * 4

    const baseGeometry = new THREE.CylinderGeometry(0.05, 0.05, 0.8, 8)
    const baseMaterial = new THREE.MeshPhongMaterial({
      color: 0xff00ff,
      emissive: 0xff00ff,
      emissiveIntensity: 0.3
    })

    const base = new THREE.Mesh(baseGeometry, baseMaterial)
    base.position.set(
      Math.cos(angle) * 0.25,
      (t - 0.5) * height,
      Math.sin(angle) * 0.25
    )
    base.lookAt(
      Math.cos(angle + Math.PI) * 0.25,
      (t - 0.5) * height,
      Math.sin(angle + Math.PI) * 0.25
    )
    group.add(base)
  }

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createCubeVisualization = (skill) => {
  const group = new THREE.Group()

  // Main cube with gradient effect
  const cubeGeometry = new THREE.BoxGeometry(1.5, 1.5, 1.5)
  const cubeMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.1,
    metalness: 0.6,
    roughness: 0.4
  })
  const cube = new THREE.Mesh(cubeGeometry, cubeMaterial)
  group.add(cube)

  // Floating elements around cube
  for (let i = 0; i < 6; i++) {
    const elementGeometry = new THREE.OctahedronGeometry(0.2, 0)
    const elementMaterial = new THREE.MeshPhongMaterial({
      color: skill.color,
      emissive: skill.color,
      emissiveIntensity: 0.3
    })
    const element = new THREE.Mesh(elementGeometry, elementMaterial)

    const angle = (Math.PI * 2 * i) / 6
    element.position.set(
      Math.cos(angle) * 2,
      Math.sin(i * 0.5) * 0.5,
      Math.sin(angle) * 2
    )

    group.add(element)
    skill.floatingElements = skill.floatingElements || []
    skill.floatingElements.push(element)
  }

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createGamepadVisualization = (skill) => {
  const group = new THREE.Group()

  // Gamepad base
  const gamepadGeometry = new THREE.BoxGeometry(2.5, 0.3, 1.2)
  const gamepadMaterial = new THREE.MeshPhongMaterial({
    color: 0x1a1a1a,
    emissive: skill.color,
    emissiveIntensity: 0.1
  })
  const gamepad = new THREE.Mesh(gamepadGeometry, gamepadMaterial)
  group.add(gamepad)

  // Analog sticks
  const stickGeometry = new THREE.CylinderGeometry(0.15, 0.15, 0.2, 16)
  const stickMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.2
  })

  const leftStick = new THREE.Mesh(stickGeometry, stickMaterial)
  leftStick.position.set(-0.6, 0.2, 0.3)
  group.add(leftStick)

  const rightStick = new THREE.Mesh(stickGeometry, stickMaterial)
  rightStick.position.set(0.6, 0.2, 0.3)
  group.add(rightStick)

  // Buttons
  const buttonGeometry = new THREE.SphereGeometry(0.12, 16, 16)
  const buttonMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.4
  })

  // D-Pad
  const dpadPositions = [[-0.8, 0.15, 0], [-1, 0.15, -0.3], [-0.8, 0.15, -0.6], [-0.6, 0.15, -0.3]]
  dpadPositions.forEach(pos => {
    const button = new THREE.Mesh(buttonGeometry, buttonMaterial)
    button.position.set(...pos)
    group.add(button)
  })

  // Action buttons
  const actionPositions = [[0.8, 0.15, 0], [1, 0.15, -0.3], [0.8, 0.15, -0.6], [0.6, 0.15, -0.3]]
  actionPositions.forEach(pos => {
    const button = new THREE.Mesh(buttonGeometry, buttonMaterial)
    button.position.set(...pos)
    group.add(button)
  })

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createGeometryVisualization = (skill) => {
  const group = new THREE.Group()

  // Complex geometric shape combination
  const geometries = [
    new THREE.IcosahedronGeometry(0.8, 1),
    new THREE.OctahedronGeometry(0.6, 0),
    new THREE.TetrahedronGeometry(0.5, 0)
  ]

  geometries.forEach((geometry, index) => {
    const material = new THREE.MeshPhongMaterial({
      color: skill.color,
      emissive: skill.color,
      emissiveIntensity: 0.15,
      wireframe: index === 1,
      transparent: true,
      opacity: index === 1 ? 0.3 : 0.8
    })

    const mesh = new THREE.Mesh(geometry, material)
    mesh.scale.setScalar(1 - index * 0.2)
    mesh.rotation.set(
      index * Math.PI / 3,
      index * Math.PI / 4,
      index * Math.PI / 6
    )
    group.add(mesh)

    skill.geometryElements = skill.geometryElements || []
    skill.geometryElements.push(mesh)
  })

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createDatabaseVisualization = (skill) => {
  const group = new THREE.Group()

  // Database cylinders
  for (let i = 0; i < 3; i++) {
    const cylinderGeometry = new THREE.CylinderGeometry(0.8, 0.8, 0.4, 32)
    const cylinderMaterial = new THREE.MeshPhongMaterial({
      color: skill.color,
      emissive: skill.color,
      emissiveIntensity: 0.1,
      metalness: 0.7,
      roughness: 0.3
    })
    const cylinder = new THREE.Mesh(cylinderGeometry, cylinderMaterial)
    cylinder.position.y = i * 0.5
    cylinder.rotation.z = Math.PI / 2
    group.add(cylinder)
  }

  // Data flowing effect
  const particleCount = 50
  const particleGeometry = new THREE.BufferGeometry()
  const positions = new Float32Array(particleCount * 3)

  for (let i = 0; i < particleCount * 3; i += 3) {
    positions[i] = (Math.random() - 0.5) * 1.5
    positions[i + 1] = Math.random() * 2 - 1
    positions[i + 2] = (Math.random() - 0.5) * 1.5
  }

  particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))

  const particleMaterial = new THREE.PointsMaterial({
    color: 0x00ffff,
    size: 0.03,
    transparent: true,
    opacity: 0.6,
    blending: THREE.AdditiveBlending
  })

  const particles = new THREE.Points(particleGeometry, particleMaterial)
  group.add(particles)
  skill.dataParticles = particles

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createCloudVisualization = (skill) => {
  const group = new THREE.Group()

  // Cloud shape using multiple spheres
  const cloudPositions = [
    [0, 0, 0], [0.6, 0.2, 0], [0.6, -0.2, 0],
    [-0.6, 0.2, 0], [-0.6, -0.2, 0], [0, 0.4, 0.3],
    [0, -0.4, 0.3], [0, 0, 0.5]
  ]

  cloudPositions.forEach((pos, index) => {
    const sphereGeometry = new THREE.SphereGeometry(0.4 + Math.random() * 0.2, 16, 16)
    const sphereMaterial = new THREE.MeshPhongMaterial({
      color: skill.color,
      emissive: skill.color,
      emissiveIntensity: 0.05,
      transparent: true,
      opacity: 0.8
    })
    const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial)
    sphere.position.set(...pos)
    group.add(sphere)
  })

  // Server nodes inside cloud
  for (let i = 0; i < 4; i++) {
    const nodeGeometry = new THREE.BoxGeometry(0.2, 0.2, 0.2)
    const nodeMaterial = new THREE.MeshPhongMaterial({
      color: 0x00ffff,
      emissive: 0x00ffff,
      emissiveIntensity: 0.3
    })
    const node = new THREE.Mesh(nodeGeometry, nodeMaterial)

    const angle = (Math.PI * 2 * i) / 4
    node.position.set(
      Math.cos(angle) * 0.3,
      (Math.random() - 0.5) * 0.4,
      Math.sin(angle) * 0.3
    )

    group.add(node)
    skill.cloudNodes = skill.cloudNodes || []
    skill.cloudNodes.push(node)
  }

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createContainerVisualization = (skill) => {
  const group = new THREE.Group()

  // Container (ship)
  const containerGeometry = new THREE.BoxGeometry(2, 1.5, 1)
  const containerMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.1,
    metalness: 0.8,
    roughness: 0.2
  })
  const container = new THREE.Mesh(containerGeometry, containerMaterial)
  group.add(container)

  // Container stacks inside
  for (let i = 0; i < 3; i++) {
    for (let j = 0; j < 2; j++) {
      const stackGeometry = new THREE.BoxGeometry(0.5, 0.4, 0.4)
      const stackMaterial = new THREE.MeshPhongMaterial({
        color: 0x333333,
        emissive: skill.color,
        emissiveIntensity: 0.2
      })
      const stack = new THREE.Mesh(stackGeometry, stackMaterial)
      stack.position.set(
        (j - 0.5) * 0.8,
        (i - 1) * 0.5,
        0
      )
      group.add(stack)
    }
  }

  // Docker whale logo (simplified)
  const whaleGeometry = new THREE.SphereGeometry(0.3, 8, 8)
  const whaleMaterial = new THREE.MeshPhongMaterial({
    color: 0x2496ED,
    emissive: 0x2496ED,
    emissiveIntensity: 0.3
  })
  const whale = new THREE.Mesh(whaleGeometry, whaleMaterial)
  whale.position.set(0, 0, 0.6)
  group.add(whale)

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createReactVisualization = (skill) => {
  const group = new THREE.Group()

  // React atom symbol
  const centerGeometry = new THREE.SphereGeometry(0.3, 16, 16)
  const centerMaterial = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.3
  })
  const center = new THREE.Mesh(centerGeometry, centerMaterial)
  group.add(center)

  // Electron orbits (elliptical)
  const orbitConfigs = [
    { radiusX: 1.2, radiusY: 0.8, rotation: 0 },
    { radiusX: 1.2, radiusY: 0.8, rotation: Math.PI * 0.66 },
    { radiusX: 1.2, radiusY: 0.8, rotation: Math.PI * 1.33 }
  ]

  orbitConfigs.forEach((config, index) => {
    // Create elliptical orbit
    const curve = new THREE.EllipseCurve(
      0, 0,
      config.radiusX, config.radiusY,
      0, 2 * Math.PI,
      false,
      0
    )

    const points = curve.getPoints(64)
    const geometry = new THREE.BufferGeometry().setFromPoints(points)
    geometry.rotateZ(config.rotation)

    const material = new THREE.LineBasicMaterial({
      color: skill.color,
      opacity: 0.4,
      transparent: true
    })

    const orbit = new THREE.Line(geometry, material)
    group.add(orbit)

    // Add electron
    const electronGeometry = new THREE.SphereGeometry(0.06, 8, 8)
    const electronMaterial = new THREE.MeshBasicMaterial({
      color: 0x61DAFB,
      emissive: 0x61DAFB
    })
    const electron = new THREE.Mesh(electronGeometry, electronMaterial)

    skill.reactElectrons = skill.reactElectrons || []
    skill.reactElectrons.push({
      electron: electron,
      config: config,
      angle: Math.random() * Math.PI * 2
    })
    group.add(electron)
  })

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createDefaultVisualization = (skill) => {
  const group = new THREE.Group()

  // Fallback to more interesting shape than sphere
  const geometry = new THREE.TorusKnotGeometry(0.8, 0.3, 100, 16)
  const material = new THREE.MeshPhongMaterial({
    color: skill.color,
    emissive: skill.color,
    emissiveIntensity: 0.2
  })
  const mesh = new THREE.Mesh(geometry, material)
  group.add(mesh)

  group.position.set(skill.position.x, skill.position.y, skill.position.z)
  return group
}

const createEnvironment = () => {
  // Grid floor
  const gridHelper = new THREE.GridHelper(30, 30, 0x00ffff, 0x004444)
  gridHelper.position.y = -5
  scene.add(gridHelper)

  // Floating particles in background
  const particleGeometry = new THREE.BufferGeometry()
  const particleCount = 2000
  const positions = new Float32Array(particleCount * 3)
  const colors = new Float32Array(particleCount * 3)

  for (let i = 0; i < particleCount * 3; i += 3) {
    positions[i] = (Math.random() - 0.5) * 50
    positions[i + 1] = (Math.random() - 0.5) * 30
    positions[i + 2] = (Math.random() - 0.5) * 50

    const color = new THREE.Color()
    color.setHSL(Math.random() * 0.1 + 0.5, 1, Math.random() * 0.5 + 0.5)
    colors[i] = color.r
    colors[i + 1] = color.g
    colors[i + 2] = color.b
  }

  particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))
  particleGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3))

  const particleMaterial = new THREE.PointsMaterial({
    size: 0.05,
    vertexColors: true,
    transparent: true,
    opacity: 0.6,
    blending: THREE.AdditiveBlending
  })

  particles = new THREE.Points(particleGeometry, particleMaterial)
  scene.add(particles)
}

const createParticleSystems = () => {
  // Create connection lines between related skills
  skillObjects.forEach((obj1, i) => {
    skillObjects.forEach((obj2, j) => {
      if (i < j) {
        const skill1 = obj1.userData
        const skill2 = obj2.userData

        // Connect skills of same type or related technologies
        if (skill1.type === skill2.type ||
            (skill1.type === 'frontend' && skill2.type === 'frontend') ||
            (skill1.type === 'backend' && skill2.type === 'database')) {

          const material = new THREE.LineBasicMaterial({
            color: skill1.color,
            transparent: true,
            opacity: 0.1,
            linewidth: 1
          })

          const points = [obj1.position, obj2.position]
          const geometry = new THREE.BufferGeometry().setFromPoints(points)
          const line = new THREE.Line(geometry, material)
          scene.add(line)
        }
      }
    })
  })
}

const animate = () => {
  animationId = requestAnimationFrame(animate)

  // Animate skill objects
  skillObjects.forEach((obj, index) => {
    const skill = obj.userData

    // Gentle floating motion
    obj.position.y += Math.sin(Date.now() * 0.001 + index) * 0.002
    obj.rotation.y += 0.003

    // Animate specific visualizations
    if (skill.orbitData) {
      // Atom orbital motion
      skill.orbitData.angle += skill.orbitData.speed
      skill.orbitData.electron.position.x = Math.cos(skill.orbitData.angle) * skill.orbitData.radius
      skill.orbitData.electron.position.z = Math.sin(skill.orbitData.angle) * skill.orbitData.radius
    }

    if (skill.geometryElements) {
      // Geometry rotation
      skill.geometryElements.forEach((element, i) => {
        element.rotation.x += 0.01 * (i + 1)
        element.rotation.y += 0.01 * (i + 1)
      })
    }

    if (skill.floatingElements) {
      // Floating elements animation
      skill.floatingElements.forEach((element, i) => {
        const time = Date.now() * 0.001
        element.position.x += Math.sin(time + i) * 0.01
        element.position.y += Math.cos(time + i) * 0.01
        element.rotation.x += 0.02
        element.rotation.y += 0.02
      })
    }

    if (skill.dataParticles) {
      // Database particle animation
      skill.dataParticles.rotation.y += 0.005
      const positions = skill.dataParticles.geometry.attributes.position.array
      for (let i = 0; i < positions.length; i += 3) {
        positions[i + 1] += 0.01
        if (positions[i + 1] > 1) positions[i + 1] = -1
      }
      skill.dataParticles.geometry.attributes.position.needsUpdate = true
    }

    if (skill.cloudNodes) {
      // Cloud nodes pulsing
      skill.cloudNodes.forEach(node => {
        const scale = 1 + Math.sin(Date.now() * 0.003) * 0.1
        node.scale.setScalar(scale)
      })
    }

    if (skill.reactElectrons) {
      // React electron motion
      skill.reactElectrons.forEach((data, i) => {
        data.angle += 0.02
        data.electron.position.x = Math.cos(data.angle) * data.config.radiusX
        data.electron.position.y = Math.sin(data.angle) * data.config.radiusY
        const rotatedX = data.electron.position.x * Math.cos(data.config.rotation) -
                        data.electron.position.y * Math.sin(data.config.rotation)
        const rotatedY = data.electron.position.x * Math.sin(data.config.rotation) +
                        data.electron.position.y * Math.cos(data.config.rotation)
        data.electron.position.x = rotatedX
        data.electron.position.y = rotatedY
      })
    }

    // Animate lights
    if (skill.light) {
      skill.lightPhase += 0.02
      skill.light.intensity = 0.8 + Math.sin(skill.lightPhase) * 0.2
    }
  })

  // Animate background particles
  if (particles) {
    particles.rotation.y += 0.0005
    particles.rotation.x += 0.0002
  }

  // Update controls
  if (controls) {
    controls.update()
  }

  renderer.render(scene, camera)
}

const onWindowResize = () => {
  if (!container.value) return

  const width = container.value.clientWidth
  const height = container.value.clientHeight

  camera.aspect = width / height
  camera.updateProjectionMatrix()
  renderer.setSize(width, height)
}

const onMouseMove = (event) => {
  const rect = renderer.domElement.getBoundingClientRect()
  mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
  mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1

  // Check for hover
  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObjects(skillObjects, true)

  let isHovering = false
  skillObjects.forEach(obj => {
    const material = obj.children[0]?.material || obj.material
    if (material) {
      if (intersects.length > 0 && intersects[0].object.parent === obj || intersects[0].object === obj) {
        if (material.emissiveIntensity !== undefined) {
          material.emissiveIntensity = 0.4
        }
        renderer.domElement.style.cursor = 'pointer'
        isHovering = true
        hoveredSkill.value = obj.userData
      } else {
        if (material.emissiveIntensity !== undefined) {
          material.emissiveIntensity = 0.1
        }
      }
    }
  })

  // Play hover sound when entering an object
  if (isHovering && !wasHovering) {
    audioManager.playSound('hover')
    wasHovering = true
  } else if (!isHovering) {
    wasHovering = false
    hoveredSkill.value = null
  }

  if (intersects.length === 0) {
    renderer.domElement.style.cursor = 'default'
  }
}

const onMouseClick = (event) => {
  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObjects(skillObjects, true)

  if (intersects.length > 0) {
    const clickedObject = intersects[0].object.parent || intersects[0].object
    const skill = clickedObject.userData

    if (skill) {
      console.log('Clicked skill:', skill)
      audioManager.playSound('success')

      // Add pulse effect to clicked object
      const originalScale = clickedObject.scale.x
      const pulseAnimation = () => {
        const time = Date.now() * 0.003
        const scale = originalScale + Math.sin(time) * 0.2
        clickedObject.scale.setScalar(scale)

        if (Math.sin(time) > 0.9) {
          clickedObject.scale.setScalar(originalScale)
        } else {
          requestAnimationFrame(pulseAnimation)
        }
      }
      pulseAnimation()
    }
  }
}
</script>

<style scoped>
.skills-visualization-container {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1;
  background: radial-gradient(ellipse at center, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
}

.skills-visualization-container canvas {
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
  z-index: 10;
  backdrop-filter: blur(10px);
}

.loading-content {
  text-align: center;
  color: #ffffff;
}

.loading-spinner {
  width: 60px;
  height: 60px;
  border: 3px solid rgba(0, 255, 255, 0.3);
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
  font-size: 1.2rem;
  color: var(--color-primary);
  margin: 0;
}

.skill-info-panel {
  position: absolute;
  top: 20px;
  left: 20px;
  background: rgba(10, 10, 10, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid var(--color-border-primary);
  border-radius: 12px;
  padding: 1.5rem;
  color: #ffffff;
  z-index: 5;
  min-width: 250px;
  animation: slideIn 0.3s ease;
}

.skill-info-panel h3 {
  color: var(--color-primary);
  margin: 0 0 0.5rem 0;
  font-size: 1.3rem;
}

.skill-info-panel p {
  color: var(--color-text-secondary);
  margin: 0 0 1rem 0;
  font-size: 1rem;
}

.skill-progress {
  width: 100%;
  height: 6px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.progress-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
  border-radius: 3px;
  transition: width 0.3s ease;
}

.skill-info-panel span {
  color: var(--color-text-muted);
  font-size: 0.9rem;
}

@keyframes slideIn {
  from {
    transform: translateX(-100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.controls-hint {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(10, 10, 10, 0.8);
  backdrop-filter: blur(10px);
  border: 1px solid var(--color-border-primary);
  border-radius: 25px;
  padding: 0.75rem 1.5rem;
  color: var(--color-text-secondary);
  font-size: 0.9rem;
  z-index: 5;
  animation: fadeInUp 1s ease 1s both;
}

@keyframes fadeInUp {
  from {
    transform: translateX(-50%) translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
  }
}

/* Mobile Optimization */
@media (max-width: 768px) {
  .skill-info-panel {
    top: 10px;
    left: 10px;
    right: 10px;
    min-width: auto;
    padding: 1rem;
  }

  .skill-info-panel h3 {
    font-size: 1.1rem;
  }

  .controls-hint {
    bottom: 10px;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
  }
}
</style>