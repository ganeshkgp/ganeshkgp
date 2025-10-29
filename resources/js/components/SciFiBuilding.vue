<template>
  <div ref="container" class="scifi-building-container">
    <div class="loading-overlay" v-if="isLoading">
      <div class="loading-content">
        <div class="loading-spinner"></div>
      </div>
    </div>
    <div class="building-info" v-if="hoveredSection">
      <h3>{{ hoveredSection.title }}</h3>
      <p>{{ hoveredSection.description }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js'
import { FBXLoader } from 'three/examples/jsm/loaders/FBXLoader.js'
import { TextureLoader } from 'three'

const container = ref(null)
const isLoading = ref(true)
const hoveredSection = ref(null)

let scene, camera, renderer, controls
let building, animationId
const mouse = new THREE.Vector2()
const raycaster = new THREE.Raycaster()
let wasHovering = false
let fbxModel = null
const textureLoader = new THREE.TextureLoader()
const fbxLoader = new FBXLoader()

// Building sections data
const buildingSections = [
  {
    id: 'web',
    title: 'Web Development',
    description: 'Vue.js, React, Laravel, Node.js',
    position: { x: 0, y: 8, z: 0 },
    color: '#00ffff'
  },
  {
    id: 'mobile',
    title: 'Mobile Development',
    description: 'Flutter, React Native, Swift, Kotlin',
    position: { x: -8, y: 4, z: 0 },
    color: '#ff00ff'
  },
  {
    id: 'gaming',
    title: 'Game Development',
    description: 'Unity, C#, WebGL, Three.js',
    position: { x: 8, y: 4, z: 0 },
    color: '#ffff00'
  },
  {
    id: 'cloud',
    title: 'Cloud & DevOps',
    description: 'AWS, Docker, Kubernetes, CI/CD',
    position: { x: 0, y: 0, z: -8 },
    color: '#00ff00'
  },
  {
    id: 'ai',
    title: 'AI & Machine Learning',
    description: 'Python, TensorFlow, PyTorch, ML',
    position: { x: 0, y: 0, z: 8 },
    color: '#ff6b6b'
  }
]

onMounted(() => {
  if (container.value) {
    initThreeJS()
    animate()
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
  scene.fog = new THREE.Fog(0x0a0a0a, 15, 100)

  // Camera setup
  const aspect = container.value.clientWidth / container.value.clientHeight
  camera = new THREE.PerspectiveCamera(60, aspect, 0.1, 1000)
  camera.position.set(15, 10, 15)
  camera.lookAt(0, 5, 0)

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
  controls.maxDistance = 50
  controls.minDistance = 10
  controls.autoRotate = true
  controls.autoRotateSpeed = 0.3
  controls.maxPolarAngle = Math.PI / 2

  // Lighting
  setupLighting()

  // Load custom FBX sci-fi building
  loadSciFiBuilding()

  // Create environment
  createEnvironment()

  // Event listeners
  window.addEventListener('resize', onWindowResize)
  renderer.domElement.addEventListener('mousemove', onMouseMove)
}

const setupLighting = () => {
  // Enhanced professional lighting setup for maximum visibility

  // Strong Key Light - Main directional light (simulating sunlight)
  const keyLight = new THREE.DirectionalLight(0xffffff, 2.5)
  keyLight.position.set(20, 25, 15)
  keyLight.castShadow = true
  keyLight.shadow.camera.left = -20
  keyLight.shadow.camera.right = 20
  keyLight.shadow.camera.top = 20
  keyLight.shadow.camera.bottom = -10
  scene.add(keyLight)

  // Strong Fill Light 1 - From opposite side
  const fillLight1 = new THREE.DirectionalLight(0xffffff, 1.8)
  fillLight1.position.set(-20, 20, -15)
  scene.add(fillLight1)

  // Strong Fill Light 2 - From above
  const fillLight2 = new THREE.DirectionalLight(0xffffff, 1.2)
  fillLight2.position.set(0, 30, -20)
  scene.add(fillLight2)

  // Fill Light 3 - From side angle
  const fillLight3 = new THREE.DirectionalLight(0xffffff, 1.0)
  fillLight3.position.set(15, 15, -25)
  scene.add(fillLight3)

  // Rim Light - Enhanced for edge definition
  const rimLight = new THREE.DirectionalLight(0xa0c4ff, 1.2)
  rimLight.position.set(-20, 5, 20)
  scene.add(rimLight)

  // Strong ambient light for base visibility
  const ambientLight = new THREE.AmbientLight(0xffffff, 1.2)
  scene.add(ambientLight)

  // Additional ambient fill from different angles
  const ambientFill1 = new THREE.AmbientLight(0xffffff, 0.4)
  scene.add(ambientFill1)

  // Bottom fill light to eliminate shadows underneath
  const bottomLight = new THREE.DirectionalLight(0xffffff, 0.8)
  bottomLight.position.set(0, -20, 0)
  scene.add(bottomLight)

  // Bright accent lights for sections
  buildingSections.forEach(section => {
    const light = new THREE.PointLight(section.color, 1.5, 20)
    light.position.set(section.position.x, section.position.y + 5, section.position.z)
    scene.add(light)
  })

  // Additional overall scene lights
  const sceneLight1 = new THREE.PointLight(0xffffff, 1.0, 30)
  sceneLight1.position.set(25, 15, 25)
  scene.add(sceneLight1)

  const sceneLight2 = new THREE.PointLight(0xffffff, 1.0, 30)
  sceneLight2.position.set(-25, 15, -25)
  scene.add(sceneLight2)
}

const loadSciFiBuilding = () => {
  console.log('Starting to load FBX model...')

  // Load PBR textures with error handling
  const textures = {
    diffuse: textureLoader.load(
      '/storage/sci-fi-tech-facility-model/texture_diffuse.png',
      (texture) => console.log('Diffuse texture loaded'),
      undefined,
      (error) => console.error('Error loading diffuse texture:', error)
    ),
    metallic: textureLoader.load(
      '/storage/sci-fi-tech-facility-model/texture_metallic.png',
      (texture) => console.log('Metallic texture loaded'),
      undefined,
      (error) => console.error('Error loading metallic texture:', error)
    ),
    roughness: textureLoader.load(
      '/storage/sci-fi-tech-facility-model/texture_roughness.png',
      (texture) => console.log('Roughness texture loaded'),
      undefined,
      (error) => console.error('Error loading roughness texture:', error)
    ),
    normal: textureLoader.load(
      '/storage/sci-fi-tech-facility-model/texture_normal.png',
      (texture) => console.log('Normal texture loaded'),
      undefined,
      (error) => console.error('Error loading normal texture:', error)
    ),
    pbr: textureLoader.load(
      '/storage/sci-fi-tech-facility-model/texture_pbr.png',
      (texture) => console.log('PBR texture loaded'),
      undefined,
      (error) => console.error('Error loading PBR texture:', error)
    )
  }

  // Set texture properties for better quality
  Object.values(textures).forEach(texture => {
    texture.flipY = false
    texture.wrapS = THREE.RepeatWrapping
    texture.wrapT = THREE.RepeatWrapping
  })

  // Try the pre-baked shaded model first for better visibility
  console.log('Loading FBX model from: /storage/sci-fi-tech-facility-model/base_basic_shaded.fbx')

  fbxLoader.load(
    '/storage/sci-fi-tech-facility-model/base_basic_shaded.fbx',
    (model) => {
      console.log('Shaded FBX model loaded successfully, processing...')
      fbxModel = model

      // Enhance existing materials instead of replacing them
      model.traverse((child) => {
        if (child.isMesh) {
          console.log('Processing mesh:', child.name, 'Original material:', child.material)

          // Keep the original material if it exists, otherwise create a basic one
          let material = child.material

          if (!material) {
            console.log('No material found, creating standard material')
            material = new THREE.MeshStandardMaterial({
              roughness: 0.6,
              metalness: 0.2
            })
          }

          // Enhance the existing material
          if (material instanceof THREE.MeshStandardMaterial) {
            // Apply diffuse texture if material doesn't already have one
            if (!material.map && textures.diffuse) {
              material.map = textures.diffuse
              console.log('Applied diffuse texture to:', child.name)
            }

            // Enhance PBR properties if textures are available
            if (!material.metalnessMap && textures.metallic) {
              material.metalnessMap = textures.metallic
            }
            if (!material.roughnessMap && textures.roughness) {
              material.roughnessMap = textures.roughness
            }
            if (!material.normalMap && textures.normal) {
              material.normalMap = textures.normal
            }
            if (!material.aoMap && textures.pbr) {
              material.aoMap = textures.pbr
              material.aoMapIntensity = 0.8
            }

            // Ensure material is visible with proper PBR values
            material.metalness = material.metalness || 0.3
            material.roughness = material.roughness || 0.5
            material.envMapIntensity = material.envMapIntensity || 1.0

            console.log('Enhanced existing material for:', child.name)
          } else {
            // Convert non-standard materials to MeshStandardMaterial
            console.log('Converting material type for:', child.name)
            const newMaterial = new THREE.MeshStandardMaterial({
              color: material.color || 0x888888,
              map: material.map || textures.diffuse,
              metalness: 0.3,
              roughness: 0.5,
              envMapIntensity: 1.0
            })

            // Apply PBR textures if available
            if (textures.metallic) newMaterial.metalnessMap = textures.metallic
            if (textures.roughness) newMaterial.roughnessMap = textures.roughness
            if (textures.normal) newMaterial.normalMap = textures.normal
            if (textures.pbr) newMaterial.aoMap = textures.pbr

            material = newMaterial
          }

          child.material = material
          child.castShadow = true
          child.receiveShadow = true
        }
      })

      // Scale and position the model
      model.scale.setScalar(0.05) // Adjust scale as needed
      model.position.y = 0
      model.rotation.y = Math.PI // Rotate if needed

      // Assign building first
      building = model
      scene.add(model)

      // Store building sections data for interaction
      building.sections = buildingSections

      // Add interactive sections
      addInteractiveSections(model)

      console.log('Shaded FBX model added to scene successfully')

      // Update loading state
      isLoading.value = false
    },
    (xhr) => {
      console.log('Loading progress:', (xhr.loaded / xhr.total * 100) + '% loaded')
    },
    (error) => {
      console.error('Error loading shaded FBX model:', error)
      console.error('Error details:', error.message || error)

      // Fallback to PBR model if shaded fails
      console.log('Attempting to load PBR FBX model...')
      fbxLoader.load(
        '/storage/sci-fi-tech-facility-model/base_basic_pbr.fbx',
        (model) => {
          console.log('PBR FBX model loaded')
          fbxModel = model

          // Apply proper PBR materials
          model.traverse((child) => {
            if (child.isMesh) {
              const material = new THREE.MeshStandardMaterial({
                metalness: 0.8,
                roughness: 0.2,
                envMapIntensity: 1.0
              })

              // Apply PBR textures naturally
              if (textures.diffuse) material.map = textures.diffuse
              if (textures.metallic) material.metalnessMap = textures.metallic
              if (textures.roughness) material.roughnessMap = textures.roughness
              if (textures.normal) material.normalMap = textures.normal
              if (textures.pbr) material.aoMap = textures.pbr

              child.material = material
              child.castShadow = true
              child.receiveShadow = true
            }
          })

          model.scale.setScalar(0.05)
          model.position.y = 0
          model.rotation.y = Math.PI

          building = model
          scene.add(model)

          // Store building sections data for interaction
          building.sections = buildingSections

          // Add interactive sections
          addInteractiveSections(model)

          isLoading.value = false
        },
        undefined,
        (error) => {
          console.error('PBR model also failed:', error)
          // Final fallback to procedural building
          createFallbackBuilding()
        }
      )
    }
  )
}

const addInteractiveSections = (model) => {
  // Create invisible interactive zones around the building
  buildingSections.forEach((section, index) => {
    // Create interactive zone
    const zoneGeometry = new THREE.BoxGeometry(3, 3, 3)
    const zoneMaterial = new THREE.MeshBasicMaterial({
      transparent: true,
      opacity: 0,
      visible: false
    })
    const zone = new THREE.Mesh(zoneGeometry, zoneMaterial)
    zone.position.set(section.position.x, section.position.y, section.position.z)
    zone.userData = section

    model.add(zone)

    // Add visual indicator (glowing sphere)
    const indicatorGeometry = new THREE.SphereGeometry(0.2, 16, 16)
    const indicatorMaterial = new THREE.MeshBasicMaterial({
      color: section.color,
      emissive: section.color,
      transparent: true,
      opacity: 0.8
    })
    const indicator = new THREE.Mesh(indicatorGeometry, indicatorMaterial)
    indicator.position.set(section.position.x, section.position.y + 2, section.position.z)
    model.add(indicator)

    // Add pulsing animation
    indicator.userData = {
      baseScale: 1,
      pulsePhase: Math.random() * Math.PI * 2
    }
  })
}

const createFallbackBuilding = () => {
  console.log('Creating fallback procedural building')
  building = new THREE.Group()

  // Simple fallback building
  const baseGeometry = new THREE.BoxGeometry(8, 20, 8)
  const baseMaterial = new THREE.MeshPhongMaterial({
    color: 0x1a1a2e,
    emissive: 0x1a1a2e,
    emissiveIntensity: 0.1,
    metalness: 0.8,
    roughness: 0.2
  })
  const base = new THREE.Mesh(baseGeometry, baseMaterial)
  base.position.y = 10
  base.castShadow = true
  base.receiveShadow = true
  building.add(base)

  // Add building sections
  createBuildingSections()

  scene.add(building)

  // Update loading state
  isLoading.value = false
  console.log('Fallback building created and loading state updated')
}

const createBuildingSections = () => {
  buildingSections.forEach((section, index) => {
    // Create section platform
    const sectionHeight = 3
    const platformGeometry = new THREE.BoxGeometry(2, sectionHeight, 2)
    const platformMaterial = new THREE.MeshPhongMaterial({
      color: 0x16213e,
      emissive: section.color,
      emissiveIntensity: 0.2,
      metalness: 0.7,
      roughness: 0.3
    })
    const platform = new THREE.Mesh(platformGeometry, platformMaterial)
    platform.position.set(section.position.x, section.position.y, section.position.z)
    platform.userData = section
    building.add(platform)

    // Add section marker lights
    const markerGeometry = new THREE.SphereGeometry(0.3, 16, 16)
    const markerMaterial = new THREE.MeshBasicMaterial({
      color: section.color,
      emissive: section.color
    })
    const marker = new THREE.Mesh(markerGeometry, markerMaterial)
    marker.position.set(section.position.x, section.position.y + 2, section.position.z)
    building.add(marker)

    // Create connection beams
    const beamGeometry = new THREE.CylinderGeometry(0.1, 0.1, 10)
    const beamMaterial = new THREE.MeshBasicMaterial({
      color: section.color,
      transparent: true,
      opacity: 0.3
    })
    const beam = new THREE.Mesh(beamGeometry, beamMaterial)

    const direction = new THREE.Vector3(section.position.x, 0, section.position.z).normalize()
    beam.position.copy(direction).multiplyScalar(5)
    beam.position.y = 5
    beam.lookAt(section.position.x, 5, section.position.z)
    building.add(beam)
  })
}

const createWindows = () => {
  // Create windows on the main building
  const windowRows = 8
  const windowCols = 12
  const windowGeometry = new THREE.BoxGeometry(0.3, 0.5, 0.1)

  for (let row = 0; row < windowRows; row++) {
    for (let col = 0; col < windowCols; col++) {
      const angle = (col / windowCols) * Math.PI * 2
      const radius = 4.8
      const height = (row / windowRows) * 15 + 2

      const windowMaterial = new THREE.MeshBasicMaterial({
        color: Math.random() > 0.3 ? 0x00ffff : 0x1a1a2e,
        emissive: Math.random() > 0.3 ? 0x00ffff : 0x1a1a2e
      })

      const window = new THREE.Mesh(windowGeometry, windowMaterial)
      window.position.x = Math.cos(angle) * radius
      window.position.y = height
      window.position.z = Math.sin(angle) * radius
      window.lookAt(0, height, 0)
      building.add(window)
    }
  }
}

const createTowerSpire = () => {
  // Create a futuristic tower spire
  const spireGroup = new THREE.Group()

  // Main spire
  const spireGeometry = new THREE.ConeGeometry(1, 8, 32)
  const spireMaterial = new THREE.MeshPhongMaterial({
    color: 0x16213e,
    emissive: 0x00ffff,
    emissiveIntensity: 0.3,
    metalness: 0.9,
    roughness: 0.1
  })
  const spire = new THREE.Mesh(spireGeometry, spireMaterial)
  spire.position.y = 20
  spire.castShadow = true
  spireGroup.add(spire)

  // Energy rings around spire
  for (let i = 0; i < 3; i++) {
    const ringGeometry = new THREE.TorusGeometry(1.5 + i * 0.5, 0.1, 32, 32)
    const ringMaterial = new THREE.MeshBasicMaterial({
      color: 0x00ffff,
      emissive: 0x00ffff,
      transparent: true,
      opacity: 0.8
    })
    const ring = new THREE.Mesh(ringGeometry, ringMaterial)
    ring.position.y = 22 + i * 2
    ring.rotation.x = Math.PI / 2
    spireGroup.add(ring)
  }

  // Top beacon
  const beaconGeometry = new THREE.SphereGeometry(0.5, 16, 16)
  const beaconMaterial = new THREE.MeshBasicMaterial({
    color: 0xffffff,
    emissive: 0xffffff
  })
  const beacon = new THREE.Mesh(beaconGeometry, beaconMaterial)
  beacon.position.y = 30
  spireGroup.add(beacon)

  building.add(spireGroup)
}

const createFloatingPlatforms = () => {
  // Create floating platforms around the building
  for (let i = 0; i < 6; i++) {
    const angle = (i / 6) * Math.PI * 2
    const radius = 12
    const height = 5 + Math.random() * 10

    const platformGeometry = new THREE.BoxGeometry(2, 0.2, 2)
    const platformMaterial = new THREE.MeshPhongMaterial({
      color: 0x16213e,
      emissive: 0x00ffff,
      emissiveIntensity: 0.1,
      metalness: 0.6,
      roughness: 0.4
    })
    const platform = new THREE.Mesh(platformGeometry, platformMaterial)
    platform.position.x = Math.cos(angle) * radius
    platform.position.y = height
    platform.position.z = Math.sin(angle) * radius

    // Add support beam
    const beamGeometry = new THREE.CylinderGeometry(0.05, 0.05, height)
    const beamMaterial = new THREE.MeshBasicMaterial({
      color: 0x00ffff,
      transparent: true,
      opacity: 0.3
    })
    const beam = new THREE.Mesh(beamGeometry, beamMaterial)
    beam.position.x = Math.cos(angle) * radius * 0.7
    beam.position.y = height / 2
    beam.position.z = Math.sin(angle) * radius * 0.7

    building.add(platform)
    building.add(beam)
  }
}

const createEnergyCores = () => {
  // Create glowing energy cores on each section
  buildingSections.forEach(section => {
    const coreGeometry = new THREE.OctahedronGeometry(0.5, 0)
    const coreMaterial = new THREE.MeshBasicMaterial({
      color: section.color,
      emissive: section.color
    })
    const core = new THREE.Mesh(coreGeometry, coreMaterial)
    core.position.set(section.position.x, section.position.y + 1, section.position.z)

    // Add pulsing animation data
    core.userData = {
      baseScale: 1,
      pulsePhase: Math.random() * Math.PI * 2
    }

    building.add(core)
  })
}

const createEnvironment = () => {
  // Ground plane
  const groundGeometry = new THREE.PlaneGeometry(100, 100)
  const groundMaterial = new THREE.MeshPhongMaterial({
    color: 0x0a0a0a,
    emissive: 0x00ffff,
    emissiveIntensity: 0.02
  })
  const ground = new THREE.Mesh(groundGeometry, groundMaterial)
  ground.rotation.x = -Math.PI / 2
  ground.receiveShadow = true
  scene.add(ground)

  // Grid on ground
  const gridHelper = new THREE.GridHelper(100, 50, 0x00ffff, 0x004444)
  scene.add(gridHelper)

  // Floating particles
  const particleGeometry = new THREE.BufferGeometry()
  const particleCount = 1000
  const positions = new Float32Array(particleCount * 3)

  for (let i = 0; i < particleCount * 3; i += 3) {
    positions[i] = (Math.random() - 0.5) * 80
    positions[i + 1] = Math.random() * 30
    positions[i + 2] = (Math.random() - 0.5) * 80
  }

  particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))

  const particleMaterial = new THREE.PointsMaterial({
    size: 0.05,
    color: 0x00ffff,
    transparent: true,
    opacity: 0.6,
    blending: THREE.AdditiveBlending
  })

  const particles = new THREE.Points(particleGeometry, particleMaterial)
  scene.add(particles)
}

const animate = () => {
  animationId = requestAnimationFrame(animate)

  // Animate building rotation
  if (building) {
    building.rotation.y += 0.001 // Slower rotation for larger model

    // Animate interactive indicators
    building.traverse((child) => {
      if (child.userData && child.userData.baseScale) {
        const time = Date.now() * 0.003
        const scale = child.userData.baseScale + Math.sin(time + child.userData.pulsePhase) * 0.2
        child.scale.setScalar(scale)
      }
    })
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

  // Check for hover on building sections
  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObjects(building ? building.children : [], true)

  let isHovering = false
  hoveredSection.value = null

  if (intersects.length > 0) {
    const clickedObject = intersects[0].object
    // Check if the object has userData with section info (from our interactive zones)
    if (clickedObject.userData && clickedObject.userData.id) {
      hoveredSection.value = clickedObject.userData
      isHovering = true
      renderer.domElement.style.cursor = 'pointer'
    }
  }

  if (!isHovering) {
    renderer.domElement.style.cursor = 'default'
  }
}
</script>

<style scoped>
.scifi-building-container {
  width: 100%;
  height: 100vh;
  position: relative;
  background: radial-gradient(ellipse at center, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
}

.scifi-building-container canvas {
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
}

.loading-spinner {
  width: 60px;
  height: 60px;
  border: 3px solid rgba(0, 255, 255, 0.3);
  border-top: 3px solid #00ffff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.building-info {
  position: absolute;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(10, 10, 10, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 12px;
  padding: 1rem 2rem;
  text-align: center;
  z-index: 5;
  animation: fadeIn 0.3s ease;
}

.building-info h3 {
  color: #00ffff;
  margin: 0 0 0.5rem 0;
  font-size: 1.2rem;
  font-weight: 600;
}

.building-info p {
  color: rgba(255, 255, 255, 0.8);
  margin: 0;
  font-size: 0.9rem;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateX(-50%) translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
}
</style>