<template>
  <div class="media-upload">
    <div class="upload-area"
         :class="{
           'is-dragging': isDragging,
           'is-uploading': isUploading,
           'has-files': uploadedFiles.length > 0
         }"
         @drop="handleDrop"
         @dragover="handleDragOver"
         @dragenter="handleDragEnter"
         @dragleave="handleDragLeave">

      <!-- Upload Input -->
      <div class="upload-input">
        <input
          ref="fileInput"
          type="file"
          :accept="acceptedTypes"
          multiple
          @change="handleFileSelect"
          style="display: none"
        />

        <button
          @click="$refs.fileInput.click()"
          class="upload-button"
          :disabled="isUploading"
        >
          <span v-if="!isUploading">📁 Choose Files</span>
          <span v-else class="uploading">
            <div class="spinner"></div>
            Uploading...
          </span>
        </button>

        <div class="upload-info">
          <p>Drag & drop files here or click to browse</p>
          <p class="file-types">Supported: Images (JPG, PNG, WEBP), 3D Models (GLB, GLTF), Documents (PDF)</p>
          <p class="file-size">Max file size: 10MB</p>
        </div>
      </div>

      <!-- File Type Selection -->
      <div class="file-type-selector" v-if="selectedFiles.length > 0">
        <label>File Type:</label>
        <select v-model="selectedFileType">
          <option value="image">Image</option>
          <option value="model">3D Model</option>
          <option value="document">Document</option>
        </select>
      </div>

      <!-- Selected Files Preview -->
      <div class="selected-files" v-if="selectedFiles.length > 0">
        <h4>Selected Files:</h4>
        <div class="file-list">
          <div v-for="(file, index) in selectedFiles" :key="index" class="file-item">
            <div class="file-preview">
              <img v-if="file.type.startsWith('image/')" :src="file.preview" :alt="file.name" />
              <div v-else class="file-icon">
                {{ getFileIcon(file.name) }}
              </div>
            </div>
            <div class="file-info">
              <span class="file-name">{{ file.name }}</span>
              <span class="file-size">{{ formatFileSize(file.size) }}</span>
            </div>
            <button @click="removeFile(index)" class="remove-file">✕</button>
          </div>
        </div>

        <div class="upload-actions">
          <button @click="uploadFiles" class="upload-btn primary" :disabled="isUploading">
            Upload {{ selectedFiles.length }} file(s)
          </button>
          <button @click="clearFiles" class="upload-btn secondary">Clear All</button>
        </div>
      </div>
    </div>

    <!-- Uploaded Files Gallery -->
    <div class="uploaded-files" v-if="uploadedFiles.length > 0">
      <h3>Uploaded Files</h3>

      <!-- File Type Filter -->
      <div class="filter-bar">
        <button
          v-for="type in ['all', 'image', 'model', 'document']"
          :key="type"
          @click="filterType = type"
          class="filter-btn"
          :class="{ active: filterType === type }"
        >
          {{ type.charAt(0).toUpperCase() + type.slice(1) }}
        </button>
      </div>

      <div class="media-gallery">
        <div
          v-for="file in filteredFiles"
          :key="file.id"
          class="media-item"
          :class="{ 'selected': selectedMedia.includes(file.id) }"
          @click="toggleSelection(file.id)"
        >
          <div class="media-preview">
            <img v-if="file.type === 'image'" :src="file.url" :alt="file.filename" />
            <div v-else class="media-icon">
              {{ getFileIcon(file.filename) }}
            </div>
          </div>
          <div class="media-info">
            <span class="media-name">{{ file.filename }}</span>
            <span class="media-meta">{{ formatFileSize(file.size) }}</span>
            <div class="media-actions">
              <button @click.stop="copyUrl(file.url)" class="action-btn" title="Copy URL">
                📋
              </button>
              <button @click.stop="deleteFile(file.filename)" class="action-btn delete" title="Delete">
                🗑️
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Bulk Actions -->
      <div class="bulk-actions" v-if="selectedMedia.length > 0">
        <span>Selected: {{ selectedMedia.length }}</span>
        <button @click="deleteSelected" class="delete-btn">Delete Selected</button>
      </div>
    </div>

    <!-- Toast Notifications -->
    <div class="toast-container">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="toast"
        :class="{ success: toast.type === 'success', error: toast.type === 'error' }"
      >
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const emit = defineEmits(['file-uploaded', 'file-deleted'])

const isDragging = ref(false)
const isUploading = ref(false)
const selectedFiles = ref([])
const uploadedFiles = ref([])
const selectedFileType = ref('image')
const filterType = ref('all')
const selectedMedia = ref([])
const toasts = ref([])

// File types for input accept attribute
const acceptedTypes = computed(() => {
  const types = {
    image: 'image/jpeg,image/png,image/gif,image/webp',
    model: 'model/gltf+json,model/gltf-binary,.obj,.fbx',
    document: 'application/pdf,text/plain,.doc,.docx'
  }
  return Object.values(types).join(',')
})

// Filter uploaded files by type
const filteredFiles = computed(() => {
  if (filterType.value === 'all') return uploadedFiles.value
  return uploadedFiles.value.filter(file => file.type === filterType.value)
})

// Drag and drop handlers
const handleDragEnter = (e) => {
  e.preventDefault()
  isDragging.value = true
}

const handleDragLeave = (e) => {
  e.preventDefault()
  if (!e.currentTarget.contains(e.relatedTarget)) {
    isDragging.value = false
  }
}

const handleDragOver = (e) => {
  e.preventDefault()
}

const handleDrop = (e) => {
  e.preventDefault()
  isDragging.value = false

  const files = Array.from(e.dataTransfer.files)
  addFiles(files)
}

// File selection
const handleFileSelect = (e) => {
  const files = Array.from(e.target.files)
  addFiles(files)
}

const addFiles = (files) => {
  files.forEach(file => {
    // Check file size (10MB limit)
    if (file.size > 10 * 1024 * 1024) {
      showToast(`File "${file.name}" is too large (max 10MB)`, 'error')
      return
    }

    // Check file type
    const preview = file.type.startsWith('image/') ? URL.createObjectURL(file) : null

    selectedFiles.value.push({
      file,
      name: file.name,
      size: file.size,
      type: file.type,
      preview
    })
  })
}

const removeFile = (index) => {
  const file = selectedFiles.value[index]
  if (file.preview) {
    URL.revokeObjectURL(file.preview)
  }
  selectedFiles.value.splice(index, 1)
}

const clearFiles = () => {
  selectedFiles.value.forEach(file => {
    if (file.preview) {
      URL.revokeObjectURL(file.preview)
    }
  })
  selectedFiles.value = []
}

// Upload files
const uploadFiles = async () => {
  if (selectedFiles.value.length === 0) return

  isUploading.value = true

  try {
    const uploadPromises = selectedFiles.value.map(fileData => {
      const formData = new FormData()
      formData.append('file', fileData.file)
      formData.append('type', selectedFileType.value)

      return axios.post('/api/v1/media', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    })

    const results = await Promise.all(uploadPromises)

    results.forEach((response, index) => {
      if (response.data.success) {
        uploadedFiles.value.push(response.data.data)
        emit('file-uploaded', response.data.data)
        showToast(`"${selectedFiles.value[index].name}" uploaded successfully`, 'success')
      }
    })

    clearFiles()

  } catch (error) {
    console.error('Upload error:', error)
    showToast('Upload failed. Please try again.', 'error')
  } finally {
    isUploading.value = false
  }
}

// File management
const deleteFile = async (filename) => {
  if (!confirm('Are you sure you want to delete this file?')) return

  try {
    const response = await axios.delete(`/api/v1/media/${filename}`)

    if (response.data.success) {
      uploadedFiles.value = uploadedFiles.value.filter(file => file.filename !== filename)
      emit('file-deleted', filename)
      showToast('File deleted successfully', 'success')
    }
  } catch (error) {
    console.error('Delete error:', error)
    showToast('Delete failed', 'error')
  }
}

const deleteSelected = async () => {
  if (!confirm(`Delete ${selectedMedia.value.length} selected files?`)) return

  try {
    const deletePromises = selectedMedia.value.map(id => {
      const file = uploadedFiles.value.find(f => f.id === id)
      return file ? axios.delete(`/api/v1/media/${file.filename}`) : Promise.resolve()
    })

    await Promise.all(deletePromises)

    uploadedFiles.value = uploadedFiles.value.filter(file => !selectedMedia.value.includes(file.id))
    selectedMedia.value = []
    showToast('Selected files deleted', 'success')

  } catch (error) {
    console.error('Bulk delete error:', error)
    showToast('Delete failed', 'error')
  }
}

// Media selection
const toggleSelection = (fileId) => {
  const index = selectedMedia.value.indexOf(fileId)
  if (index > -1) {
    selectedMedia.value.splice(index, 1)
  } else {
    selectedMedia.value.push(fileId)
  }
}

// Utility functions
const getFileIcon = (filename) => {
  const ext = filename.split('.').pop().toLowerCase()
  const icons = {
    jpg: '🖼️', jpeg: '🖼️', png: '🖼️', gif: '🖼️', webp: '🖼️',
    glb: '🎮', gltf: '🎮', obj: '🎮', fbx: '🎮',
    pdf: '📄', doc: '📄', docx: '📄', txt: '📄'
  }
  return icons[ext] || '📁'
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const copyUrl = async (url) => {
  try {
    await navigator.clipboard.writeText(url)
    showToast('URL copied to clipboard', 'success')
  } catch (error) {
    showToast('Failed to copy URL', 'error')
  }
}

const showToast = (message, type = 'info') => {
  const toast = {
    id: Date.now(),
    message,
    type
  }
  toasts.value.push(toast)

  setTimeout(() => {
    const index = toasts.value.findIndex(t => t.id === toast.id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }, 3000)
}

// Load existing files
const loadExistingFiles = async () => {
  try {
    const response = await axios.get('/api/v1/media')
    if (response.data.success) {
      uploadedFiles.value = response.data.data
    }
  } catch (error) {
    console.error('Failed to load existing files:', error)
  }
}

onMounted(() => {
  loadExistingFiles()
})
</script>

<style scoped>
.media-upload {
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.upload-area {
  border: 2px dashed var(--color-border-primary);
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  transition: var(--transition-normal);
  background: var(--color-bg-secondary);
}

.upload-area.is-dragging {
  border-color: var(--color-primary);
  background: var(--color-bg-hover);
}

.upload-area.is-uploading {
  border-color: var(--color-warning);
  background: rgba(255, 193, 7, 0.1);
}

.upload-button {
  background: var(--gradient-primary);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: var(--transition-normal);
  margin-bottom: 1rem;
}

.upload-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 255, 255, 0.3);
}

.upload-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.uploading {
  display: flex;
  align-items: center;
  gap: 0.5rem;
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

.upload-info p {
  color: var(--color-text-secondary);
  margin: 0.5rem 0;
  font-size: 0.9rem;
}

.file-type-selector {
  margin-top: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.file-type-selector select {
  padding: 0.5rem;
  border: 1px solid var(--color-border-primary);
  border-radius: 6px;
  background: var(--color-bg-card);
  color: var(--color-text-primary);
}

.selected-files {
  margin-top: 1.5rem;
  text-align: left;
}

.file-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin: 1rem 0;
}

.file-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 8px;
}

.file-preview {
  width: 60px;
  height: 60px;
  border-radius: 6px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-bg-secondary);
}

.file-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.file-icon {
  font-size: 2rem;
}

.file-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.file-name {
  font-weight: 600;
  color: var(--color-text-primary);
}

.file-size {
  font-size: 0.8rem;
  color: var(--color-text-secondary);
}

.remove-file {
  background: var(--color-error);
  color: white;
  border: none;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition-normal);
}

.remove-file:hover {
  background: #c82333;
}

.upload-actions {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
}

.upload-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: var(--transition-normal);
}

.upload-btn.primary {
  background: var(--gradient-primary);
  color: white;
}

.upload-btn.secondary {
  background: var(--color-bg-secondary);
  color: var(--color-text-primary);
  border: 1px solid var(--color-border-primary);
}

.upload-btn:hover:not(:disabled) {
  transform: translateY(-1px);
}

.uploaded-files {
  margin-top: 2rem;
}

.filter-bar {
  display: flex;
  gap: 0.5rem;
  margin: 1rem 0;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 0.5rem 1rem;
  border: 1px solid var(--color-border-primary);
  background: var(--color-bg-card);
  color: var(--color-text-secondary);
  border-radius: 6px;
  cursor: pointer;
  transition: var(--transition-normal);
}

.filter-btn.active {
  background: var(--gradient-primary);
  color: white;
  border-color: var(--color-primary);
}

.media-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  margin: 1rem 0;
}

.media-item {
  border: 1px solid var(--color-border-primary);
  border-radius: 8px;
  overflow: hidden;
  background: var(--color-bg-card);
  cursor: pointer;
  transition: var(--transition-normal);
}

.media-item:hover {
  border-color: var(--color-primary);
  transform: translateY(-2px);
}

.media-item.selected {
  border-color: var(--color-primary);
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
}

.media-preview {
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-bg-secondary);
}

.media-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.media-info {
  padding: 0.75rem;
}

.media-name {
  font-weight: 600;
  color: var(--color-text-primary);
  display: block;
  font-size: 0.9rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.media-meta {
  font-size: 0.8rem;
  color: var(--color-text-secondary);
  display: block;
}

.media-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.action-btn {
  background: var(--color-bg-secondary);
  border: 1px solid var(--color-border-primary);
  color: var(--color-text-secondary);
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.8rem;
  transition: var(--transition-normal);
}

.action-btn:hover {
  background: var(--color-bg-hover);
  color: var(--color-text-primary);
}

.action-btn.delete:hover {
  background: var(--color-error);
  color: white;
}

.bulk-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  background: var(--color-bg-secondary);
  border-radius: 8px;
  margin-top: 1rem;
}

.delete-btn {
  background: var(--color-error);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  transition: var(--transition-normal);
}

.delete-btn:hover {
  background: #c82333;
}

.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 1000;
}

.toast {
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 0.5rem;
  min-width: 250px;
  animation: slideIn 0.3s ease;
}

.toast.success {
  border-color: var(--color-success);
  background: rgba(40, 167, 69, 0.1);
}

.toast.error {
  border-color: var(--color-error);
  background: rgba(220, 53, 69, 0.1);
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Mobile Optimization */
@media (max-width: 768px) {
  .media-upload {
    padding: 1rem;
  }

  .upload-area {
    padding: 1.5rem;
  }

  .media-gallery {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 0.75rem;
  }

  .file-item {
    padding: 0.75rem;
  }

  .file-preview {
    width: 50px;
    height: 50px;
  }
}
</style>