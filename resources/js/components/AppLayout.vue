<template>
  <div class="app-layout">
    <!-- Navigation -->
    <nav class="navbar">
      <div class="nav-content">
        <div class="logo">
          <span class="logo-text">GK</span>
        </div>
        <div class="nav-links">
          <router-link to="/" class="nav-link">Work</router-link>
          <router-link to="/projects" class="nav-link">Projects</router-link>
          <router-link to="/contact" class="nav-link">Contact</router-link>
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
// No script logic needed for now, just a layout component
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
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(0, 8, 20, 0.8);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(255, 214, 10, 0.2);
  padding: 1rem 0;
}

.nav-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-text {
  font-size: 1.5rem;
  font-weight: bold;
  color: #ffffff;
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
  padding: 0.5rem 0;
  font-size: 0.9rem;
}

.nav-link:hover,
.nav-link.active {
  color: #ffd60a;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: #ffd60a;
  transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
  width: 100%;
}

/* Main Content */
.main-content {
  width: 100%;
  min-height: 100vh;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .nav-content {
    padding: 0 1rem;
  }

  .nav-links {
    gap: 1rem;
    font-size: 0.8rem;
  }

  .logo-text {
    font-size: 1.2rem;
  }
}
</style>
