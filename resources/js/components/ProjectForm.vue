<template>
  <div class="project-form">
    <form @submit.prevent="handleSubmit">
      <div class="form-grid">
        <!-- Basic Information -->
        <div class="form-section">
          <h3>Basic Information</h3>

          <div class="form-group">
            <label for="name">Project Name *</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              placeholder="Enter project name"
            />
          </div>

          <div class="form-group">
            <label for="description">Description *</label>
            <textarea
              id="description"
              v-model="form.description"
              required
              placeholder="Describe your project"
              rows="4"
            ></textarea>
          </div>

          <div class="form-group">
            <label for="technologies">Technologies *</label>
            <input
              id="technologies"
              v-model="form.technologies"
              type="text"
              required
              placeholder="Vue.js, Laravel, MySQL (comma-separated)"
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="live_url">Live URL</label>
              <input
                id="live_url"
                v-model="form.live_url"
                type="url"
                placeholder="https://example.com"
              />
            </div>

            <div class="form-group">
              <label for="github_url">GitHub URL</label>
              <input
                id="github_url"
                v-model="form.github_url"
                type="url"
                placeholder="https://github.com/user/repo"
              />
            </div>
          </div>
        </div>

        <!-- 3D Settings -->
        <div class="form-section">
          <h3>3D Gallery Settings</h3>

          <div class="form-row">
            <div class="form-group">
              <label for="position_x">Position X</label>
              <input
                id="position_x"
                v-model.number="form.position.x"
                type="number"
                step="0.1"
                placeholder="0"
              />
            </div>

            <div class="form-group">
              <label for="position_y">Position Y</label>
              <input
                id="position_y"
                v-model.number="form.position.y"
                type="number"
                step="0.1"
                placeholder="0"
              />
            </div>

            <div class="form-group">
              <label for="position_z">Position Z</label>
              <input
                id="position_z"
                v-model.number="form.position.z"
                type="number"
                step="0.1"
                placeholder="0"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="color">Project Color</label>
            <div class="color-input-group">
              <input
                id="color"
                v-model="form.color"
                type="color"
              />
              <input
                v-model="form.color"
                type="text"
                placeholder="#00ffff"
                pattern="^#[0-9A-Fa-f]{6}$"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="featured">Featured Project</label>
            <label class="checkbox-label">
              <input
                id="featured"
                v-model="form.featured"
                type="checkbox"
              />
              <span class="checkbox-custom"></span>
              Show in featured projects
            </label>
          </div>
        </div>
      </div>

      <!-- Media Selection -->
      <div class="form-section">
        <h3>Project Media</h3>

        <div class="media-selector">
          <div class="current-media" v-if="form.image_url">
            <img :src="form.image_url" :alt="form.name" class="current-image" />
            <button type="button" @click="removeCurrentImage" class="remove-media-btn">
              Remove Image
            </button>
          </div>

          <div class="media-gallery" v-if="mediaFiles.length > 0">
            <h4>Select from Media Library</h4>
            <div class="media-grid">
              <div
                v-for="file in imageFiles"
                :key="file.id"
                @click="selectMedia(file)"
                :class="['media-item', { selected: isSelected(file) }]"
              >
                <img :src="file.url" :alt="file.filename" />
                <div class="media-overlay">
                  <span class="checkmark">✓</span>
                </div>
              </div>
            </div>
          </div>

          <div class="no-media" v-else>
            <p>No images available in media library.</p>
            <button type="button" @click="$emit('open-media-upload')" class="upload-btn">
              Upload Images
            </button>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <button type="submit" class="save-btn" :disabled="isSubmitting">
          <span v-if="isSubmitting">
            <div class="spinner"></div>
            Saving...
          </span>
          <span v-else>
            {{ isEditing ? 'Update Project' : 'Create Project' }}
          </span>
        </button>

        <button type="button" @click="$emit('cancel')" class="cancel-btn">
          Cancel
        </button>

        <button type="button" @click="resetForm" class="reset-btn">
          Reset Form
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  project: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['submit', 'cancel', 'open-media-upload'])

const isSubmitting = ref(false)
const mediaFiles = ref([])

const isEditing = computed(() => !!props.project)

const form = ref({
  name: '',
  description: '',
  technologies: '',
  live_url: '',
  github_url: '',
  image_url: '',
  position: { x: 0, y: 0, z: 0 },
  color: '#00ffff',
  featured: false
})

// Computed property for image files only
const imageFiles = computed(() => {
  return mediaFiles.value.filter(file => file.type === 'image')
})

// Initialize form when project prop changes
watch(() => props.project, (newProject) => {
  if (newProject) {
    form.value = {
      ...newProject,
      technologies: Array.isArray(newProject.technologies)
        ? newProject.technologies.join(', ')
        : newProject.technologies,
      position: newProject.position || { x: 0, y: 0, z: 0 }
    }
  } else {
    resetForm()
  }
}, { immediate: true })

// Load media files on component mount
onMounted(() => {
  loadMediaFiles()
})

const loadMediaFiles = async () => {
  try {
    const response = await axios.get('/api/v1/media')
    if (response.data.success) {
      mediaFiles.value = response.data.data
    }
  } catch (error) {
    console.error('Failed to load media files:', error)
  }
}

const selectMedia = (file) => {
  form.value.image_url = file.url
}

const removeCurrentImage = () => {
  form.value.image_url = ''
}

const isSelected = (file) => {
  return form.value.image_url === file.url
}

const handleSubmit = async () => {
  if (isSubmitting.value) return

  isSubmitting.value = true

  try {
    // Process form data
    const formData = {
      ...form.value,
      technologies: form.value.technologies
        .split(',')
        .map(tech => tech.trim())
        .filter(tech => tech.length > 0)
    }

    emit('submit', formData)
  } catch (error) {
    console.error('Form submission error:', error)
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  form.value = {
    name: '',
    description: '',
    technologies: '',
    live_url: '',
    github_url: '',
    image_url: '',
    position: { x: 0, y: 0, z: 0 },
    color: '#00ffff',
    featured: false
  }
}
</script>

<style scoped>
.project-form {
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 12px;
  padding: 2rem;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.form-section {
  background: var(--color-bg-secondary);
  border: 1px solid var(--color-border-primary);
  border-radius: 8px;
  padding: 1.5rem;
}

.form-section h3 {
  color: var(--color-primary);
  margin-bottom: 1.5rem;
  font-size: 1.2rem;
  font-weight: 600;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group label {
  display: block;
  color: var(--color-text-primary);
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 6px;
  color: var(--color-text-primary);
  font-size: 1rem;
  transition: var(--transition-normal);
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

.color-input-group {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.color-input-group input[type="color"] {
  width: 50px;
  height: 40px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.color-input-group input[type="text"] {
  flex: 1;
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  color: var(--color-text-primary);
}

.checkbox-label input[type="checkbox"] {
  display: none;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid var(--color-border-primary);
  border-radius: 4px;
  margin-right: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition-normal);
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
  background: var(--color-primary);
  border-color: var(--color-primary);
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
  content: '✓';
  color: var(--color-bg-primary);
  font-weight: bold;
}

.media-selector {
  margin-top: 1rem;
}

.current-media {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 8px;
}

.current-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 6px;
}

.remove-media-btn {
  background: var(--color-error);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  transition: var(--transition-normal);
}

.remove-media-btn:hover {
  background: #c82333;
}

.media-gallery h4 {
  color: var(--color-text-primary);
  margin-bottom: 1rem;
}

.media-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: 1rem;
}

.media-item {
  position: relative;
  aspect-ratio: 1;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  border: 2px solid transparent;
  transition: var(--transition-normal);
}

.media-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.media-item:hover {
  transform: scale(1.05);
  border-color: var(--color-primary);
}

.media-item.selected {
  border-color: var(--color-primary);
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
}

.media-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: var(--transition-normal);
}

.media-item.selected .media-overlay {
  opacity: 1;
}

.checkmark {
  background: var(--color-primary);
  color: var(--color-bg-primary);
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.no-media {
  text-align: center;
  padding: 2rem;
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 8px;
}

.no-media p {
  color: var(--color-text-secondary);
  margin-bottom: 1rem;
}

.upload-btn {
  background: var(--gradient-primary);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  cursor: pointer;
  transition: var(--transition-normal);
}

.upload-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 255, 255, 0.3);
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 2rem;
  border-top: 1px solid var(--color-border-primary);
}

.save-btn,
.cancel-btn,
.reset-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: var(--transition-normal);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.save-btn {
  background: var(--gradient-primary);
  color: white;
}

.save-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 255, 255, 0.3);
}

.save-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.cancel-btn {
  background: var(--color-bg-secondary);
  color: var(--color-text-primary);
  border: 1px solid var(--color-border-primary);
}

.cancel-btn:hover {
  background: var(--color-bg-hover);
}

.reset-btn {
  background: var(--color-warning);
  color: white;
}

.reset-btn:hover {
  background: #e0a800;
}

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .project-form {
    padding: 1rem;
  }

  .form-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }

  .media-grid {
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  }
}
</style>