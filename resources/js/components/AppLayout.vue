<template>
  <div class="app-layout">
    <!-- Preloader -->
    <div v-if="isLoading" class="preloader">
      <div class="preloader-content">
        <div class="logo-animation">
          <span class="logo-letter">G</span>
          <span class="logo-letter">K</span>
        </div>
        <div class="loading-text">
          <span class="loading-dot"></span>
          <span class="loading-dot"></span>
          <span class="loading-dot"></span>
        </div>
        <div class="loading-percentage">{{ loadingPercentage }}%</div>
      </div>
    </div>

    <!-- Navigation -->
    <nav
      ref="navbarRef"
      class="navbar"
      :class="{ 'scrolled': isScrolled }"
    >
      <div class="nav-content">
        <!-- Logo -->
        <div class="logo">
          <span class="logo-text">GK</span>
        </div>

        <!-- Mobile Breadcrumb -->
        <div class="md:hidden mobile-breadcrumb">
          <span class="breadcrumb-text">{{ getCurrentPageName() }}</span>
        </div>

        <!-- Mobile menu button -->
        <button
          @click="toggleMobileMenu"
          class="mobile-menu-button md:hidden"
          :class="{ 'active': mobileMenuOpen }"
        >
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
        </button>

        <!-- Desktop Navigation -->
        <div class="nav-links flex-col md:flex-row hidden md:flex">
          <router-link to="/" class="nav-link" :class="{ 'active': isLinkActive('/') }">Work</router-link>
          <router-link to="/projects" class="nav-link" :class="{ 'active': isLinkActive('/projects') }">Projects</router-link>
          <router-link to="/blogs" class="nav-link" :class="{ 'active': isLinkActive('/blogs') }">Blog</router-link>
          <router-link to="/contact" class="nav-link" :class="{ 'active': isLinkActive('/contact') }">Contact</router-link>
        </div>

        <!-- Desktop Authentication -->
        <div class="hidden md:block">
          <UserAuth />
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div
        class="mobile-nav"
        :class="{ 'active': mobileMenuOpen }"
      >
        <div class="mobile-nav-header">
          <h3 class="mobile-nav-title">{{ getCurrentPageName() }}</h3>
        </div>
        <div class="mobile-nav-content">
          <!-- Mobile Links -->
          <div class="mobile-nav-links">
            <router-link
              to="/"
              class="mobile-nav-link"
              :class="{ 'active': isLinkActive('/') }"
              @click="closeMobileMenu"
            >
              Work
            </router-link>
            <router-link
              to="/projects"
              class="mobile-nav-link"
              :class="{ 'active': isLinkActive('/projects') }"
              @click="closeMobileMenu"
            >
              Projects
            </router-link>
            <router-link
              to="/blogs"
              class="mobile-nav-link"
              :class="{ 'active': isLinkActive('/blogs') }"
              @click="closeMobileMenu"
            >
              Blog
            </router-link>
            <router-link
              to="/contact"
              class="mobile-nav-link"
              :class="{ 'active': isLinkActive('/contact') }"
              @click="closeMobileMenu"
            >
              Contact
            </router-link>
          </div>

          <!-- Mobile Authentication -->
          <div class="mobile-auth">
            <UserAuth />
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <slot></slot>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import UserAuth from './UserAuth.vue'

const route = useRoute()

// Mobile menu state
const mobileMenuOpen = ref(false)
const navbarRef = ref(null)
const isScrolled = ref(false)

// Preloader state
const isLoading = ref(true)
const loadingPercentage = ref(0)

// Check if link is active
const isLinkActive = (path) => {
  if (path === '/' && route.path === '/') {
    return true
  }
  return route.path.startsWith(path) && path !== '/'
}

// Get current page name for breadcrumb
const getCurrentPageName = () => {
  const path = route.path

  if (path === '/' || path === '') return 'Home'
  if (path.startsWith('/projects')) return 'Projects'
  if (path.startsWith('/blogs')) return 'Blog'
  if (path.startsWith('/contact')) return 'Contact'
  if (path.startsWith('/login')) return 'Login'
  if (path.startsWith('/register')) return 'Register'

  // Handle dynamic routes like blog details
  if (path.startsWith('/blog/')) return 'Blog Details'

  // Default fallback
  return 'Page'
}

// Handle scroll for navbar effects
const handleScroll = () => {
  const scrollY = window.scrollY

  // Add scrolled class when page is scrolled
  if (scrollY > 50) {
    isScrolled.value = true
  } else {
    isScrolled.value = false
  }
}

// Handle visibility change (tab switching)
const handleVisibilityChange = () => {
  if (document.hidden && mobileMenuOpen.value) {
    closeMobileMenu()
  }
}

// Handle resize (window size changes)
const handleResize = () => {
  // Close mobile menu on desktop resize
  if (window.innerWidth >= 768 && mobileMenuOpen.value) {
    closeMobileMenu()
  }
}

// Handle touch events for better mobile experience
const handleTouchMove = (e) => {
  // Allow scroll if mobile menu is not open
  if (!mobileMenuOpen.value) return

  // Prevent vertical scroll when menu is open
  e.preventDefault()
}

// Toggle mobile menu
const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value

  // Prevent body scroll when menu is open
  if (mobileMenuOpen.value) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}

// Close mobile menu
const closeMobileMenu = () => {
  mobileMenuOpen.value = false
  document.body.style.overflow = ''
}

// Close mobile menu when clicking outside
const handleClickOutside = (event) => {
  const nav = event.target.closest('nav')
  if (!nav && mobileMenuOpen.value) {
    closeMobileMenu()
  }
}

// Close mobile menu on escape key
const handleEscapeKey = (event) => {
  if (event.key === 'Escape' && mobileMenuOpen.value) {
    closeMobileMenu()
  }
}

// Handle route change to close mobile menu
const handleRouteChange = () => {
  closeMobileMenu()
  // Scroll to top on route change
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Preloader loading simulation
const simulateLoading = () => {
  let progress = 0
  const interval = setInterval(() => {
    progress += Math.random() * 15 + 5
    if (progress >= 100) {
      progress = 100
      loadingPercentage.value = 100
      setTimeout(() => {
        isLoading.value = false
      }, 500)
      clearInterval(interval)
    } else {
      loadingPercentage.value = Math.floor(progress)
    }
  }, 100)
}

// Add event listeners
onMounted(() => {
  // Initialize scroll state
  handleScroll()

  // Add scroll listener
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('resize', handleResize)
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleEscapeKey)
  document.addEventListener('visibilitychange', handleVisibilityChange)

  // Add touch listeners for mobile
  document.addEventListener('touchmove', handleTouchMove, { passive: false })

  // Add route change listener
  window.addEventListener('popstate', handleRouteChange)

  // Start loading simulation
  simulateLoading()
})

// Clean up event listeners
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', handleResize)
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleEscapeKey)
  document.removeEventListener('visibilitychange', handleVisibilityChange)
  document.removeEventListener('touchmove', handleTouchMove)
  window.removeEventListener('popstate', handleRouteChange)
  document.body.style.overflow = ''
})
</script>

<style scoped>
.app-layout {
  min-height: 100vh;
  background: #000814;
  color: #ffffff;
  overflow-x: hidden;
  position: relative;
}

/* Animated starfield background */
.app-layout::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image:
    radial-gradient(2px 2px at 20px 30px, white, transparent),
    radial-gradient(2px 2px at 40px 70px, white, transparent),
    radial-gradient(1px 1px at 50px 50px, white, transparent),
    radial-gradient(1px 1px at 80px 10px, white, transparent),
    radial-gradient(2px 2px at 130px 80px, white, transparent),
    radial-gradient(1px 1px at 110px 60px, white, transparent),
    radial-gradient(1px 1px at 150px 20px, white, transparent),
    radial-gradient(2px 2px at 180px 90px, white, transparent),
    radial-gradient(1px 1px at 200px 40px, white, transparent),
    radial-gradient(1px 1px at 240px 100px, white, transparent),
    radial-gradient(2px 2px at 280px 30px, white, transparent),
    radial-gradient(1px 1px at 320px 70px, white, transparent);
  background-repeat: repeat;
  background-size: 350px 120px;
  animation: starScroll 200s linear infinite;
  z-index: -1;
  opacity: 0.3;
}

@keyframes starScroll {
  from { transform: translateX(0); }
  to { transform: translateX(-350px); }
}

/* Nebula effects */
.app-layout::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(ellipse at 20% 30%, rgba(65, 105, 225, 0.1) 0%, transparent 50%),
              radial-gradient(ellipse at 80% 70%, rgba(255, 20, 147, 0.08) 0%, transparent 50%),
              radial-gradient(ellipse at 40% 80%, rgba(0, 191, 255, 0.06) 0%, transparent 50%);
  animation: nebulaFloat 60s ease-in-out infinite;
  z-index: -1;
  pointer-events: none;
}

@keyframes nebulaFloat {
  0%, 100% { opacity: 0.3; transform: scale(1) rotate(0deg); }
  50% { opacity: 0.5; transform: scale(1.1) rotate(5deg); }
}

/* Navigation */
.navbar {
  position: sticky;
  top: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(0, 8, 20, 0.95);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(255, 214, 10, 0.2);
  padding: 0.75rem 0;
  transition: all 0.3s ease;
}

.navbar.scrolled {
  background: rgba(0, 8, 20, 0.98);
  backdrop-filter: blur(25px);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
  border-bottom-color: rgba(255, 214, 10, 0.3);
}

.nav-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-text {
  font-size: 1.5rem;
  font-weight: bold;
  color: #ffffff;
  background: linear-gradient(135deg, #ffd60a, #00ffff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Mobile Breadcrumb */
.mobile-breadcrumb {
  flex: 1;
  text-align: center;
}

.breadcrumb-text {
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.9rem;
  font-weight: 500;
  text-transform: capitalize;
  letter-spacing: 0.5px;
  position: relative;
}

.breadcrumb-text::before {
  content: '';
  position: absolute;
  left: -0.5rem;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 4px;
  background: linear-gradient(45deg, #ffd60a, #00ffff);
  border-radius: 50%;
  opacity: 0.7;
}

/* Desktop Navigation */
.nav-links {
  gap: 2rem;
}

/* Flex direction override for responsive behavior */
.nav-links.flex-col {
  flex-direction: column;
}

.nav-links.md\:flex-row {
  flex-direction: row;
}

.nav-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s ease;
  position: relative;
  padding: 0.5rem 0;
  font-size: 0.9rem;
  font-weight: 500;
}

.nav-link:hover,
.nav-link.active {
  color: #ffd60a;
  transform: translateY(-1px);
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #ffd60a, #00ffff);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-link:hover::after,
.nav-link.active::after {
  width: 100%;
  transform: scaleX(1);
}

.nav-link {
  position: relative;
  will-change: transform;
}

.nav-link::after {
  transform-origin: left;
  transform: scaleX(0);
}

/* Mobile Menu Button */
.mobile-menu-button {
  display: none; /* Hidden by default on desktop */
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 2rem;
  height: 2rem;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
  position: relative;
}

/* Show only on mobile and tablet */
@media (max-width: 767px) {
  .mobile-menu-button {
    display: flex;
  }
}

.hamburger-line {
  width: 1.5rem;
  height: 2px;
  background: linear-gradient(90deg, #ffd60a, #00ffff);
  margin: 2px 0;
  transition: all 0.3s ease;
  transform-origin: center;
}

.mobile-menu-button.active .hamburger-line:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.mobile-menu-button.active .hamburger-line:nth-child(2) {
  opacity: 0;
  transform: scale(0);
}

.mobile-menu-button.active .hamburger-line:nth-child(3) {
  transform: rotate(-45deg) translate(7px, -6px);
}

/* Mobile Navigation */
.mobile-nav {
  position: fixed;
  top: 100%;
  left: 0;
  right: 0;
  background: rgba(0, 8, 20, 0.98);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(255, 214, 10, 0.2);
  transform: translateY(-100%);
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
  z-index: 999;
  max-height: calc(100vh - 80px);
  overflow-y: auto;
}

.mobile-nav.active {
  transform: translateY(0);
  opacity: 1;
  visibility: visible;
}

.mobile-nav-content {
  padding: 2rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

/* Add current page indicator in mobile nav */
.mobile-nav-header {
  padding: 1rem 1.5rem 0.5rem;
  border-bottom: 1px solid rgba(255, 214, 10, 0.1);
  margin-bottom: 1rem;
}

.mobile-nav-title {
  color: #ffd60a;
  font-size: 1.1rem;
  font-weight: 600;
  text-align: center;
  text-transform: capitalize;
}

.mobile-nav-links {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.mobile-nav-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  padding: 1rem 1.5rem;
  border-radius: 0.75rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 214, 10, 0.1);
  transition: all 0.3s ease;
  font-size: 1rem;
  font-weight: 500;
  text-align: center;
}

.mobile-nav-link:hover,
.mobile-nav-link.active {
  color: #ffd60a;
  background: rgba(255, 214, 10, 0.1);
  border-color: rgba(255, 214, 10, 0.3);
  transform: translateX(0.25rem);
}

.mobile-auth {
  padding-top: 1rem;
  border-top: 1px solid rgba(255, 214, 10, 0.1);
  display: flex;
  justify-content: center;
}

/* Main Content */
.main-content {
  width: 100%;
  min-height: 100vh;
  padding-top: 3.5rem; /* Account for sticky navbar */
}

/* Desktop Responsiveness */
@media (min-width: 768px) {
  .main-content {
    padding-top: 4rem; /* Larger padding for desktop */
  }

  .nav-content {
    padding: 0 2rem;
  }

  .navbar {
    padding: 1rem 0;
  }

  /* Hide mobile breadcrumb on desktop */
  .mobile-breadcrumb {
    display: none;
  }
}

/* Mobile Responsive */
@media (max-width: 767px) {
  .nav-content {
    padding: 0 1rem;
    gap: 0.5rem;
  }

  .logo-text {
    font-size: 1.25rem;
  }

  .mobile-breadcrumb {
    min-width: 0;
    overflow: hidden;
  }

  .breadcrumb-text {
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
  }

  .mobile-menu-button {
    flex-shrink: 0;
  }
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Ensure proper spacing for sticky navbar */
@media (min-width: 640px) {
  .navbar {
    backdrop-filter: blur(20px);
  }
}

/* Large screens */
@media (min-width: 1024px) {
  .nav-content {
    max-width: 1280px;
  }
}

/* Animation for smoother transitions */
.mobile-nav-link {
  animation: slideInRight 0.3s ease forwards;
  opacity: 0;
}

.mobile-nav-link:nth-child(1) { animation-delay: 0.1s; }
.mobile-nav-link:nth-child(2) { animation-delay: 0.15s; }
.mobile-nav-link:nth-child(3) { animation-delay: 0.2s; }
.mobile-nav-link:nth-child(4) { animation-delay: 0.25s; }

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.mobile-nav.active .mobile-nav-link {
  opacity: 1;
}

/* Scrollbar styling for mobile nav */
.mobile-nav::-webkit-scrollbar {
  width: 6px;
}

.mobile-nav::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

.mobile-nav::-webkit-scrollbar-thumb {
  background: rgba(255, 214, 10, 0.3);
  border-radius: 3px;
}

.mobile-nav::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 214, 10, 0.5);
}

/* Preloader Styles */
.preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #000814 0%, #001233 50%, #000814 100%);
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
}

.preloader::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image:
    radial-gradient(2px 2px at 20px 30px, white, transparent),
    radial-gradient(2px 2px at 40px 70px, white, transparent),
    radial-gradient(1px 1px at 50px 50px, white, transparent),
    radial-gradient(1px 1px at 80px 10px, white, transparent),
    radial-gradient(2px 2px at 130px 80px, white, transparent),
    radial-gradient(1px 1px at 110px 60px, white, transparent),
    radial-gradient(1px 1px at 150px 20px, white, transparent),
    radial-gradient(2px 2px at 180px 90px, white, transparent),
    radial-gradient(1px 1px at 200px 40px, white, transparent),
    radial-gradient(1px 1px at 240px 100px, white, transparent),
    radial-gradient(2px 2px at 280px 30px, white, transparent),
    radial-gradient(1px 1px at 320px 70px, white, transparent);
  background-repeat: repeat;
  background-size: 350px 120px;
  animation: starScroll 200s linear infinite;
  opacity: 0.3;
}

.preloader-content {
  text-align: center;
  z-index: 1;
  animation: fadeInUp 0.8s ease-out;
}

.logo-animation {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 2rem;
}

.logo-letter {
  font-size: 4rem;
  font-weight: bold;
  color: transparent;
  background: linear-gradient(135deg, #ffd60a, #00ffff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: letterFloat 2s ease-in-out infinite;
  text-shadow: 0 0 30px rgba(255, 214, 10, 0.5);
}

.logo-letter:nth-child(1) {
  animation-delay: 0s;
}

.logo-letter:nth-child(2) {
  animation-delay: 0.2s;
}

@keyframes letterFloat {
  0%, 100% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
  50% {
    transform: translateY(-10px) scale(1.05);
    opacity: 0.8;
  }
}

.loading-text {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.loading-dot {
  width: 12px;
  height: 12px;
  background: linear-gradient(45deg, #ffd60a, #00ffff);
  border-radius: 50%;
  animation: dotPulse 1.5s ease-in-out infinite;
  box-shadow: 0 0 20px rgba(255, 214, 10, 0.5);
}

.loading-dot:nth-child(1) {
  animation-delay: 0s;
}

.loading-dot:nth-child(2) {
  animation-delay: 0.3s;
}

.loading-dot:nth-child(3) {
  animation-delay: 0.6s;
}

@keyframes dotPulse {
  0%, 100% {
    transform: scale(1);
    opacity: 0.3;
  }
  50% {
    transform: scale(1.3);
    opacity: 1;
  }
}

.loading-percentage {
  font-size: 1.2rem;
  font-weight: 600;
  color: #ffffff;
  letter-spacing: 2px;
  text-transform: uppercase;
  background: linear-gradient(90deg, #ffd60a, #00ffff, #ffd60a);
  background-size: 200% 100%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: shimmer 2s linear infinite;
}

@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Exit animation for preloader */
.preloader.hide {
  animation: fadeOut 0.5s ease-out forwards;
}

@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
    pointer-events: none;
  }
}

/* Responsive preloader */
@media (max-width: 768px) {
  .logo-letter {
    font-size: 3rem;
  }

  .loading-percentage {
    font-size: 1rem;
  }

  .loading-dot {
    width: 10px;
    height: 10px;
  }
}

@media (max-width: 480px) {
  .logo-letter {
    font-size: 2.5rem;
  }

  .loading-percentage {
    font-size: 0.9rem;
  }

  .logo-animation {
    margin-bottom: 1.5rem;
  }
}
</style>
