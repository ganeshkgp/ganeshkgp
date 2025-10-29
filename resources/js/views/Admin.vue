<template>
  <div class="admin-container">
    <!-- Navigation -->
    <nav class="navbar">
      <div class="nav-content">
        <div class="logo">
          <span class="logo-text">GK Admin</span>
        </div>
        <div class="nav-links">
          <router-link to="/" class="nav-link">Home</router-link>
          <router-link to="/projects" class="nav-link">Projects</router-link>
          <router-link to="/admin" class="nav-link active">Admin</router-link>
        </div>
      </div>
    </nav>

    <!-- Admin Dashboard -->
    <div class="admin-dashboard">
      <div class="admin-header">
        <h1>Portfolio Admin Panel</h1>
        <p>Manage your 3D portfolio content</p>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📊</div>
          <div class="stat-info">
            <h3>{{ stats.totalProjects }}</h3>
            <p>Total Projects</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">👁️</div>
          <div class="stat-info">
            <h3>{{ stats.totalViews }}</h3>
            <p>Total Views</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📧</div>
          <div class="stat-info">
            <h3>{{ stats.messages }}</h3>
            <p>Messages</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🎨</div>
          <div class="stat-info">
            <h3>{{ stats.skills }}</h3>
            <p>Skills</p>
          </div>
        </div>
      </div>

      <!-- Tab Navigation -->
      <div class="tab-navigation">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="['tab-button', { active: activeTab === tab.id }]"
        >
          {{ tab.name }}
        </button>
      </div>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Projects Management -->
        <div v-if="activeTab === 'projects'" class="projects-management">
          <div class="section-header">
            <h2>Projects Management</h2>
            <button @click="showProjectForm = true" class="add-button">
              + Add New Project
            </button>
          </div>

          <div class="projects-list">
            <div v-for="project in projects" :key="project.id" class="project-item">
              <div class="project-info">
                <h3>{{ project.name }}</h3>
                <p>{{ project.description }}</p>
                <div class="project-tech">
                  <span v-for="tech in project.technologies" :key="tech" class="tech-tag">
                    {{ tech }}
                  </span>
                </div>
              </div>
              <div class="project-actions">
                <button @click="editProject(project)" class="edit-btn">Edit</button>
                <button @click="deleteProject(project.id)" class="delete-btn">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Skills Management -->
        <div v-if="activeTab === 'skills'" class="skills-management">
          <div class="section-header">
            <h2>Skills Management</h2>
            <button @click="showSkillForm = true" class="add-button">
              + Add New Skill
            </button>
          </div>

          <div class="skills-list">
            <div v-for="skill in skills" :key="skill.id" class="skill-item">
              <div class="skill-info">
                <div class="skill-icon">{{ skill.icon }}</div>
                <div>
                  <h3>{{ skill.name }}</h3>
                  <p>{{ skill.experience }}</p>
                </div>
              </div>
              <div class="skill-actions">
                <button @click="editSkill(skill)" class="edit-btn">Edit</button>
                <button @click="deleteSkill(skill.id)" class="delete-btn">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Media Management -->
        <div v-if="activeTab === 'media'" class="media-management">
          <h2>Media Library</h2>
          <p>Upload and manage your project screenshots, 3D models, and documents</p>

          <MediaUpload
            @file-uploaded="handleFileUploaded"
            @file-deleted="handleFileDeleted"
          />
        </div>

        <!-- Analytics -->
        <div v-if="activeTab === 'analytics'" class="analytics-section">
          <h2>Analytics Dashboard</h2>
          <div class="analytics-grid">
            <div class="analytics-card">
              <h3>Project Views</h3>
              <div class="chart-placeholder">
                📈 Chart showing project views over time
              </div>
            </div>
            <div class="analytics-card">
              <h3>Visitor Stats</h3>
              <div class="chart-placeholder">
                📊 Visitor demographics and traffic sources
              </div>
            </div>
            <div class="analytics-card">
              <h3>Popular Technologies</h3>
              <div class="tech-popularity">
                <div v-for="tech in popularTechs" :key="tech.name" class="tech-stat">
                  <span>{{ tech.name }}</span>
                  <div class="tech-bar">
                    <div class="tech-fill" :style="{ width: tech.percentage + '%' }"></div>
                  </div>
                  <span>{{ tech.count }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <div v-if="activeTab === 'messages'" class="messages-section">
          <h2>Contact Messages</h2>
          <div class="messages-list">
            <div v-for="message in messages" :key="message.id" class="message-item">
              <div class="message-header">
                <div class="sender-info">
                  <h4>{{ message.name }}</h4>
                  <p>{{ message.email }}</p>
                </div>
                <span class="message-date">{{ message.date }}</span>
              </div>
              <div class="message-content">
                <p>{{ message.content }}</p>
              </div>
              <div class="message-actions">
                <button @click="replyToMessage(message)" class="reply-btn">Reply</button>
                <button @click="deleteMessage(message.id)" class="delete-btn">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Forms -->
    <div v-if="showProjectForm || showSkillForm" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <!-- Project Form -->
        <div v-if="showProjectForm" class="form-container">
          <h2>{{ editingProject ? 'Edit Project' : 'Add New Project' }}</h2>
          <form @submit.prevent="saveProject">
            <div class="form-group">
              <label>Project Name</label>
              <input v-model="projectForm.name" type="text" required>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea v-model="projectForm.description" required></textarea>
            </div>
            <div class="form-group">
              <label>Technologies (comma-separated)</label>
              <input v-model="projectForm.technologies" type="text" required>
            </div>
            <div class="form-group">
              <label>Live URL</label>
              <input v-model="projectForm.liveUrl" type="url">
            </div>
            <div class="form-group">
              <label>GitHub URL</label>
              <input v-model="projectForm.githubUrl" type="url">
            </div>
            <div class="form-group">
              <label>Project Image</label>
              <input type="file" @change="handleImageUpload">
            </div>
            <div class="form-actions">
              <button type="submit" class="save-btn">Save</button>
              <button type="button" @click="closeModal" class="cancel-btn">Cancel</button>
            </div>
          </form>
        </div>

        <!-- Skill Form -->
        <div v-if="showSkillForm" class="form-container">
          <h2>{{ editingSkill ? 'Edit Skill' : 'Add New Skill' }}</h2>
          <form @submit.prevent="saveSkill">
            <div class="form-group">
              <label>Skill Name</label>
              <input v-model="skillForm.name" type="text" required>
            </div>
            <div class="form-group">
              <label>Experience Level</label>
              <input v-model="skillForm.experience" type="text" required>
            </div>
            <div class="form-group">
              <label>Icon (Emoji)</label>
              <input v-model="skillForm.icon" type="text" required>
            </div>
            <div class="form-group">
              <label>Color (Hex)</label>
              <input v-model="skillForm.color" type="color">
            </div>
            <div class="form-actions">
              <button type="submit" class="save-btn">Save</button>
              <button type="button" @click="closeModal" class="cancel-btn">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import MediaUpload from '../components/MediaUpload.vue'

const activeTab = ref('projects')
const showProjectForm = ref(false)
const showSkillForm = ref(false)
const editingProject = ref(null)
const editingSkill = ref(null)

// Stats
const stats = ref({
  totalProjects: 6,
  totalViews: 1247,
  messages: 8,
  skills: 12
})

// Tabs
const tabs = [
  { id: 'projects', name: 'Projects' },
  { id: 'skills', name: 'Skills' },
  { id: 'media', name: 'Media' },
  { id: 'analytics', name: 'Analytics' },
  { id: 'messages', name: 'Messages' }
]

// Sample data
const projects = ref([
  {
    id: 1,
    name: 'E-Commerce Platform',
    description: 'Full-stack e-commerce solution with real-time inventory management',
    technologies: ['Laravel', 'Vue.js', 'MySQL'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example'
  },
  {
    id: 2,
    name: 'Mobile Banking App',
    description: 'Cross-platform mobile banking application',
    technologies: ['Flutter', 'Node.js'],
    liveUrl: 'https://example.com',
    githubUrl: 'https://github.com/example'
  }
])

const skills = ref([
  {
    id: 1,
    name: 'PHP/Laravel',
    experience: 'Backend Expert',
    icon: '🐘',
    color: '#777BB4'
  },
  {
    id: 2,
    name: 'Vue.js',
    experience: 'Frontend Specialist',
    icon: '💚',
    color: '#4FC08D'
  }
])

const messages = ref([
  {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    content: 'Great portfolio! Would love to discuss a project.',
    date: '2024-01-15'
  }
])

const popularTechs = ref([
  { name: 'Vue.js', count: 45, percentage: 90 },
  { name: 'Laravel', count: 38, percentage: 76 },
  { name: 'Flutter', count: 32, percentage: 64 },
  { name: 'Python', count: 28, percentage: 56 }
])

// Forms
const projectForm = ref({
  name: '',
  description: '',
  technologies: '',
  liveUrl: '',
  githubUrl: ''
})

const skillForm = ref({
  name: '',
  experience: '',
  icon: '',
  color: '#000000'
})

onMounted(() => {
  // Load data from API
  console.log('Admin panel initialized')
})

const editProject = (project) => {
  editingProject.value = project
  projectForm.value = { ...project, technologies: project.technologies.join(', ') }
  showProjectForm.value = true
}

const saveProject = () => {
  // Save project logic
  console.log('Saving project:', projectForm.value)
  closeModal()
}

const deleteProject = (id) => {
  // Delete project logic
  console.log('Deleting project:', id)
}

const editSkill = (skill) => {
  editingSkill.value = skill
  skillForm.value = { ...skill }
  showSkillForm.value = true
}

const saveSkill = () => {
  // Save skill logic
  console.log('Saving skill:', skillForm.value)
  closeModal()
}

const deleteSkill = (id) => {
  // Delete skill logic
  console.log('Deleting skill:', id)
}

const replyToMessage = (message) => {
  // Reply to message logic
  console.log('Replying to message:', message)
}

const deleteMessage = (id) => {
  // Delete message logic
  console.log('Deleting message:', id)
}

const closeModal = () => {
  showProjectForm.value = false
  showSkillForm.value = false
  editingProject.value = null
  editingSkill.value = null
  // Reset forms
  projectForm.value = {
    name: '',
    description: '',
    technologies: '',
    liveUrl: '',
    githubUrl: ''
  }
  skillForm.value = {
    name: '',
    experience: '',
    icon: '',
    color: '#000000'
  }
}

const handleImageUpload = (event) => {
  // Handle image upload
  console.log('Image uploaded:', event.target.files[0])
}

const handleFileUploaded = (fileData) => {
  console.log('File uploaded:', fileData)
  // You could update stats or trigger other actions
}

const handleFileDeleted = (filename) => {
  console.log('File deleted:', filename)
  // You could update stats or trigger other actions
}
</script>

<style scoped>
.admin-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
  color: #ffffff;
}

/* Navigation */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(10, 10, 10, 0.9);
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

.logo-text {
  background: linear-gradient(45deg, #ff00ff, #00ffff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: bold;
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

/* Admin Dashboard */
.admin-dashboard {
  max-width: 1200px;
  margin: 0 auto;
  padding: 6rem 2rem 2rem;
}

.admin-header {
  text-align: center;
  margin-bottom: 3rem;
}

.admin-header h1 {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.admin-header p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 1.1rem;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.stat-card {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  border-color: rgba(0, 255, 255, 0.3);
}

.stat-icon {
  font-size: 2rem;
}

.stat-info h3 {
  font-size: 1.8rem;
  color: #00ffff;
  margin-bottom: 0.3rem;
}

.stat-info p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
}

/* Tab Navigation */
.tab-navigation {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding-bottom: 1rem;
}

.tab-button {
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.7);
  padding: 0.8rem 1.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  border-radius: 25px;
  font-size: 1rem;
}

.tab-button:hover {
  background: rgba(255, 255, 255, 0.1);
}

.tab-button.active {
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  color: #000000;
  font-weight: bold;
}

/* Tab Content */
.tab-content {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 2rem;
  backdrop-filter: blur(10px);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.section-header h2 {
  color: #ffffff;
  font-size: 1.5rem;
}

.add-button {
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  color: #000000;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
}

.add-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 255, 255, 0.4);
}

/* Projects List */
.projects-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.project-item {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.project-info h3 {
  color: #00ffff;
  margin-bottom: 0.5rem;
}

.project-info p {
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 1rem;
}

.project-tech {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.tech-tag {
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  color: #00ffff;
  padding: 0.2rem 0.6rem;
  border-radius: 15px;
  font-size: 0.8rem;
}

.project-actions {
  display: flex;
  gap: 1rem;
}

.edit-btn, .reply-btn {
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  color: #00ffff;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.edit-btn:hover, .reply-btn:hover {
  background: rgba(0, 255, 255, 0.2);
}

.delete-btn {
  background: rgba(255, 0, 0, 0.1);
  border: 1px solid rgba(255, 0, 0, 0.3);
  color: #ff6b6b;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.delete-btn:hover {
  background: rgba(255, 0, 0, 0.2);
}

/* Media Management */
.media-management {
  max-width: 100%;
}

.media-management h2 {
  color: #00ffff;
  margin-bottom: 0.5rem;
  font-size: 1.5rem;
}

.media-management p {
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 2rem;
  font-size: 1.1rem;
}

/* Analytics */
.analytics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.analytics-card {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  padding: 1.5rem;
}

.analytics-card h3 {
  color: #00ffff;
  margin-bottom: 1rem;
}

.chart-placeholder {
  height: 200px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255, 255, 255, 0.5);
}

.tech-popularity {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.tech-stat {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.tech-bar {
  flex: 1;
  height: 10px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 5px;
  overflow: hidden;
}

.tech-fill {
  height: 100%;
  background: linear-gradient(90deg, #00ffff, #ff00ff);
  transition: width 0.3s ease;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.modal-content {
  background: linear-gradient(135deg, #1a1a2e, #16213e);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 2rem;
  width: 90%;
  max-width: 500px;
  backdrop-filter: blur(20px);
}

.form-container h2 {
  color: #00ffff;
  margin-bottom: 1.5rem;
  text-align: center;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: rgba(255, 255, 255, 0.9);
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.8rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 5px;
  color: #ffffff;
  font-size: 1rem;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #00ffff;
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

.save-btn {
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  color: #000000;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
}

.cancel-btn {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #ffffff;
  padding: 0.8rem 1.5rem;
  border-radius: 5px;
  cursor: pointer;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .admin-dashboard {
    padding: 5rem 1rem 1rem;
  }

  .tab-navigation {
    flex-wrap: wrap;
  }

  .tab-button {
    font-size: 0.9rem;
    padding: 0.6rem 1rem;
  }

  .section-header {
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
  }

  .project-item {
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
  }

  .project-actions {
    justify-content: flex-start;
  }

  .modal-content {
    width: 95%;
    margin: 1rem;
  }
}
</style>