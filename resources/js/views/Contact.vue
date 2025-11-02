<template>
  <AppLayout>
    <div class="min-h-screen p-8 mx-auto max-w-7xl flex flex-col">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="font-black text-4xl lg:text-6xl leading-tight mb-4">
          <span class="block">Let's Build</span>
          <span class="block bg-gradient-to-r from-purple-500 to-yellow-500 bg-clip-text text-transparent animate-pulse">Something Amazing</span>
        </h1>
        <p class="text-lg text-white/70 leading-relaxed max-w-xl mx-auto">
          I'm always excited to work on innovative projects. Whether you have a specific need or just want to chat about technology, I'm here to help.
        </p>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 flex-1 items-start">
        <!-- Contact Form Section -->
        <div class="lg:col-span-3 bg-black/50 border border-yellow-500/20 rounded-2xl p-6 backdrop-blur-lg h-fit">
          <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-2 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Get In Touch</h2>
          </div>
          <ContactForm />
        </div>

        <!-- Contact Info Section -->
        <div class="lg:col-span-2 flex flex-col gap-6">
          <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-2 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Connect With Me</h2>
          </div>

          <div class="flex flex-col gap-3">
            <div class="flex items-center gap-3 p-3 bg-black/50 border border-yellow-500/20 rounded-lg transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10">
              <div class="text-2xl w-10 h-10 flex items-center justify-center bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full text-black flex-shrink-0">📧</div>
              <div class="flex-1">
                <h3 class="text-cyan-400 mb-1 text-sm font-medium">Email</h3>
                <a href="mailto:ganeshr848@gmail.com" class="text-white/70 hover:text-cyan-400 transition-colors duration-300 text-sm">ganeshr848@gmail.com</a>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-black/50 border border-yellow-500/20 rounded-lg transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10">
              <div class="text-2xl w-10 h-10 flex items-center justify-center bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full text-black flex-shrink-0">💼</div>
              <div class="flex-1">
                <h3 class="text-cyan-400 mb-1 text-sm font-medium">LinkedIn</h3>
                <a href="#" target="_blank" class="text-white/70 hover:text-cyan-400 transition-colors duration-300 text-sm">linkedin.com/in/ganeshkp</a>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-black/50 border border-yellow-500/20 rounded-lg transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10">
              <div class="text-2xl w-10 h-10 flex items-center justify-center bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full text-black flex-shrink-0">🐙</div>
              <div class="flex-1">
                <h3 class="text-cyan-400 mb-1 text-sm font-medium">GitHub</h3>
                <a href="#" target="_blank" class="text-white/70 hover:text-cyan-400 transition-colors duration-300 text-sm">github.com/ganeshkgp</a>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-black/50 border border-yellow-500/20 rounded-lg transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10">
              <div class="text-2xl w-10 h-10 flex items-center justify-center bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full text-black flex-shrink-0">🌍</div>
              <div class="flex-1">
                <h3 class="text-cyan-400 mb-1 text-sm font-medium">Location</h3>
                <p class="text-white/70 text-sm">Remote work worldwide</p>
              </div>
            </div>
          </div>

        <!-- Response Time -->
          <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-cyan-500/10 to-purple-500/10 border border-purple-500/30 rounded-lg">
            <div class="text-2xl">⚡</div>
            <div class="flex-1">
              <h3 class="text-purple-400 mb-1 text-sm font-medium">Response Time</h3>
              <p class="text-white/70 text-sm">Usually within 24 hours</p>
            </div>
          </div>

          <!-- Skills -->
          <div class="skills-section">
            <h3 class="text-yellow-500 mb-4 text-lg font-medium">Skills & Expertise</h3>
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-black/50 border border-yellow-500/20 rounded-lg p-3 text-center transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10 backdrop-blur-lg">
                <div class="text-2xl mb-1">🐘</div>
                <h4 class="text-cyan-400 text-xs font-medium">PHP/Laravel</h4>
              </div>
              <div class="bg-black/50 border border-yellow-500/20 rounded-lg p-3 text-center transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10 backdrop-blur-lg">
                <div class="text-2xl mb-1">💚</div>
                <h4 class="text-cyan-400 text-xs font-medium">Vue.js/React</h4>
              </div>
              <div class="bg-black/50 border border-yellow-500/20 rounded-lg p-3 text-center transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10 backdrop-blur-lg">
                <div class="text-2xl mb-1">📱</div>
                <h4 class="text-cyan-400 text-xs font-medium">Flutter</h4>
              </div>
              <div class="bg-black/50 border border-yellow-500/20 rounded-lg p-3 text-center transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10 backdrop-blur-lg">
                <div class="text-2xl mb-1">🐍</div>
                <h4 class="text-cyan-400 text-xs font-medium">Python</h4>
              </div>
              <div class="bg-black/50 border border-yellow-500/20 rounded-lg p-3 text-center transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10 backdrop-blur-lg">
                <div class="text-2xl mb-1">🎮</div>
                <h4 class="text-cyan-400 text-xs font-medium">Unity</h4>
              </div>
              <div class="bg-black/50 border border-yellow-500/20 rounded-lg p-3 text-center transition-all duration-300 hover:border-purple-500/30 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cyan-500/10 backdrop-blur-lg">
                <div class="text-2xl mb-1">☁️</div>
                <h4 class="text-cyan-400 text-xs font-medium">Cloud & DevOps</h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom CTA -->
      <div class="text-center mt-8 pt-6 border-t border-yellow-500/20">
        <p class="mb-4 text-white/80">Ready to start your next project? Let's create something remarkable together!</p>
        <router-link to="/projects" class="inline-block bg-gradient-to-r from-yellow-500 to-purple-500 text-black px-8 py-3 rounded-lg font-semibold text-base transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-500/30 uppercase tracking-wide">
          View My Work
        </router-link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import ContactForm from '../components/ContactForm.vue'

// Store original overflow settings
let originalAppStyles = {}
let originalBodyStyles = {}
let originalHtmlStyles = {}

onMounted(() => {
  console.log('Contact page mounted - enabling scrolling')

  // Store original App.vue styles
  const app = document.getElementById('app')
  const body = document.body
  const html = document.documentElement

  if (app) {
    originalAppStyles = {
      overflowY: app.style.overflowY,
      overflowX: app.style.overflowX,
      height: app.style.height,
      minHeight: app.style.minHeight
    }
  }

  if (body) {
    originalBodyStyles = {
      overflowY: body.style.overflowY,
      overflowX: body.style.overflowX
    }
  }

  if (html) {
    originalHtmlStyles = {
      overflowY: html.style.overflowY,
      overflowX: html.style.overflowX
    }
  }

  // CRITICAL: Override App.vue overflow settings for Contact page only
  // Use !important and multiple methods to ensure override works
  if (app) {
    app.style.setProperty('overflow-y', 'auto', 'important')
    app.style.setProperty('overflow-x', 'hidden', 'important')
    app.style.setProperty('height', 'auto', 'important')
    app.style.setProperty('min-height', '100vh', 'important')
    // Fallback for older browsers
    app.style.overflowY = 'auto'
    app.style.overflowX = 'hidden'
    app.style.height = 'auto'
    app.style.minHeight = '100vh'
  }

  if (body) {
    body.style.setProperty('overflow-y', 'auto', 'important')
    body.style.setProperty('overflow-x', 'hidden', 'important')
    body.style.overflowY = 'auto'
    body.style.overflowX = 'hidden'
  }

  if (html) {
    html.style.setProperty('overflow-y', 'auto', 'important')
    html.style.setProperty('overflow-x', 'hidden', 'important')
    html.style.overflowY = 'auto'
    html.style.overflowX = 'hidden'
  }

  // Add a class to body for additional CSS targeting
  body.classList.add('contact-page-active')
})

onUnmounted(() => {
  console.log('Contact page unmounted - restoring original overflow settings')

  // Restore original App.vue overflow settings when leaving Contact page
  const app = document.getElementById('app')
  const body = document.body
  const html = document.documentElement

  // Remove the contact page class
  body.classList.remove('contact-page-active')

  if (app) {
    app.style.removeProperty('overflow-y')
    app.style.removeProperty('overflow-x')
    app.style.removeProperty('height')
    app.style.removeProperty('min-height')
    // Restore original styles
    app.style.overflowY = originalAppStyles.overflowY || 'hidden'
    app.style.overflowX = originalAppStyles.overflowX || 'hidden'
    app.style.height = originalAppStyles.height || '100vh'
    app.style.minHeight = originalAppStyles.minHeight || ''
  }

  if (body) {
    body.style.removeProperty('overflow-y')
    body.style.removeProperty('overflow-x')
    body.style.overflowY = originalBodyStyles.overflowY || 'hidden'
    body.style.overflowX = originalBodyStyles.overflowX || 'hidden'
  }

  if (html) {
    html.style.removeProperty('overflow-y')
    html.style.removeProperty('overflow-x')
    html.style.overflowY = originalHtmlStyles.overflowY || 'hidden'
    html.style.overflowX = originalHtmlStyles.overflowX || 'hidden'
  }
})
</script>

<style scoped>
/* IMPORTANT: Override App.vue overflow settings for Contact page only */
/* This ensures Contact page can scroll while Home.vue and Projects.vue remain non-scrollable */

/* Multiple approaches to override App.vue overflow settings for Contact page only */

/* Approach 1: Global CSS targeting */
:global(#app) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
  height: auto !important;
  min-height: 100vh !important;
}

:global(body) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

:global(html) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Approach 2: Class-based targeting (more reliable) */
:global(body.contact-page-active #app) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
  height: auto !important;
  min-height: 100vh !important;
}

:global(body.contact-page-active) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

:global(body.contact-page-active html) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Single Page Contact Container */
.contact-container {
  min-height: 100vh;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  max-width: 1400px;
  margin: 0 auto;
}

/* Compact Header */
.contact-header {
  text-align: center;
  margin-bottom: 2rem;
}

.hero-title {
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 1rem;
}

.title-line {
  display: block;
}

.gradient-text {
  background: linear-gradient(45deg, #ff00ff, #ffff00);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: gradient-shift 3s ease infinite;
}

@keyframes gradient-shift {
  0%, 100% { filter: hue-rotate(0deg); }
  50% { filter: hue-rotate(30deg); }
}

.hero-subtitle {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.7);
  line-height: 1.5;
  max-width: 500px;
  margin: 0 auto;
}

/* Main Grid Layout */
.contact-main-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 2rem;
  flex: 1;
  align-items: start;
}

/* Section Titles */
.section-title {
  margin-bottom: 1.5rem;
}

.section-title h2 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Form Section */
.form-section {
  background: rgba(0, 8, 20, 0.8);
  border: 1px solid rgba(255, 214, 10, 0.2);
  border-radius: 15px;
  padding: 1.5rem;
  backdrop-filter: blur(10px);
  height: fit-content;
}

/* Info Section */
.info-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Compact Contact Methods */
.contact-methods {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.contact-method {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 0.8rem;
  background: rgba(0, 8, 20, 0.8);
  border: 1px solid rgba(255, 214, 10, 0.2);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.contact-method:hover {
  border-color: rgba(255, 0, 255, 0.3);
  transform: translateY(-1px);
  box-shadow: 0 3px 15px rgba(0, 255, 255, 0.1);
}

.method-icon {
  font-size: 1.2rem;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  border-radius: 50%;
  color: #0a0a0a;
  flex-shrink: 0;
}

.method-info h3 {
  color: #00ffff;
  margin-bottom: 0.2rem;
  font-size: 0.9rem;
}

.method-info a,
.method-info p {
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 0.85rem;
}

.method-info a:hover {
  color: #00ffff;
}

/* Compact Response Time */
.response-time {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 1rem;
  background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
  border: 1px solid rgba(255, 0, 255, 0.3);
  border-radius: 8px;
}

.response-icon {
  font-size: 1.5rem;
}

.response-info h3 {
  color: #ff00ff;
  margin-bottom: 0.2rem;
  font-size: 0.9rem;
}

.response-info p {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.85rem;
}

/* Compact Skills Section */
.skills-section h3 {
  color: #ffd60a;
  margin-bottom: 1rem;
  font-size: 1.1rem;
}

.skills-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.8rem;
}

.skill-item {
  background: rgba(0, 8, 20, 0.8);
  border: 1px solid rgba(255, 214, 10, 0.2);
  border-radius: 8px;
  padding: 0.8rem;
  text-align: center;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.skill-item:hover {
  border-color: rgba(255, 0, 255, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(0, 255, 255, 0.1);
}

.skill-icon {
  font-size: 1.5rem;
  margin-bottom: 0.3rem;
}

.skill-item h4 {
  color: #00ffff;
  margin-bottom: 0;
  font-size: 0.75rem;
}

/* Bottom CTA */
.bottom-cta {
  text-align: center;
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(255, 214, 10, 0.2);
}

/* Custom Scrollbar for Contact page only */
:deep(::-webkit-scrollbar) {
  width: 12px;
}

:deep(::-webkit-scrollbar-track) {
  background: rgba(0, 8, 20, 0.8);
  border-left: 1px solid rgba(255, 214, 10, 0.1);
}

:deep(::-webkit-scrollbar-thumb) {
  background: linear-gradient(45deg, rgba(255, 214, 10, 0.6), rgba(255, 0, 255, 0.6));
  border-radius: 6px;
  border: 1px solid rgba(255, 214, 10, 0.3);
  transition: all 0.3s ease;
}

:deep(::-webkit-scrollbar-thumb:hover) {
  background: linear-gradient(45deg, rgba(255, 214, 10, 0.8), rgba(255, 0, 255, 0.8));
  border-color: rgba(255, 214, 10, 0.5);
}

:deep(::-webkit-scrollbar-corner) {
  background: rgba(0, 8, 20, 0.8);
}

/* Firefox scrollbar styling for Contact page only */
:deep(*) {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 214, 10, 0.6) rgba(0, 8, 20, 0.8);
}

/* Button Styles */
.btn-primary {
  background: linear-gradient(45deg, #ffd60a, #ff00ff);
  color: #0a0a0a;
  border: none;
  padding: 0.8rem 2rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  position: relative;
  overflow: hidden;
  text-decoration: none;
  display: inline-block;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(255, 214, 10, 0.3);
}


/* Mobile Responsiveness */
@media (max-width: 768px) {
  .contact-container {
    padding: 1rem;
  }

  .contact-header {
    margin-bottom: 1.5rem;
  }

  .hero-title {
    font-size: clamp(1.5rem, 6vw, 2rem);
  }

  .hero-subtitle {
    font-size: 0.9rem;
  }

  .contact-main-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .form-section {
    padding: 1rem;
  }

  .section-title h2 {
    font-size: 1.3rem;
  }

  .contact-method {
    padding: 0.6rem;
  }

  .method-icon {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }

  .method-info h3 {
    font-size: 0.85rem;
  }

  .method-info a,
  .method-info p {
    font-size: 0.8rem;
  }

  .skills-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.6rem;
  }

  .skill-item {
    padding: 0.6rem;
  }

  .skill-icon {
    font-size: 1.2rem;
  }

  .skill-item h4 {
    font-size: 0.7rem;
  }

  .bottom-cta {
    margin-top: 1.5rem;
  }
}


/* Touch Device Optimization */
@media (hover: none) and (pointer: coarse) {
  .contact-method:hover,
  .skill-item:hover {
    transform: none;
  }
}
</style>