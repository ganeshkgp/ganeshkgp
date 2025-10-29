<template>
  <div class="contact-container">
    <!-- Navigation -->
    <nav class="navbar">
      <div class="nav-content">
        <div class="logo">
          <span class="logo-text">GK</span>
        </div>
        <div class="nav-links">
          <router-link to="/" class="nav-link">Home</router-link>
          <router-link to="/projects" class="nav-link">Projects</router-link>
          <router-link to="/contact" class="nav-link active">Contact</router-link>
          <router-link to="/admin" class="nav-link">Admin</router-link>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero">
      <div class="hero-content">
        <h1 class="hero-title">
          <span class="title-line">Let's Build</span>
          <span class="title-line gradient-text">Something Amazing</span>
        </h1>
        <p class="hero-subtitle">
          I'm always excited to work on innovative projects. Whether you have a specific need or just want to chat about technology, I'm here to help.
        </p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
      <div class="contact-grid">
        <!-- Contact Form -->
        <div class="contact-form-wrapper">
          <ContactForm />
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
          <h2 class="info-title">Let's Connect</h2>
          <p class="info-description">
            Ready to start your next project? Reach out through any of these channels:
          </p>

          <div class="contact-methods">
            <div class="contact-method">
              <div class="method-icon">📧</div>
              <div class="method-info">
                <h3>Email</h3>
                <a href="mailto:ganeshr848@gmail.com">ganeshr848@gmail.com</a>
              </div>
            </div>

            <div class="contact-method">
              <div class="method-icon">💼</div>
              <div class="method-info">
                <h3>LinkedIn</h3>
                <a href="#" target="_blank">linkedin.com/in/ganeshkp</a>
              </div>
            </div>

            <div class="contact-method">
              <div class="method-icon">🐙</div>
              <div class="method-info">
                <h3>GitHub</h3>
                <a href="#" target="_blank">github.com/ganeshkgp</a>
              </div>
            </div>

            <div class="contact-method">
              <div class="method-icon">🌍</div>
              <div class="method-info">
                <h3>Location</h3>
                <p>Available for remote work worldwide</p>
              </div>
            </div>
          </div>

          <!-- Response Time -->
          <div class="response-time">
            <div class="response-icon">⚡</div>
            <div class="response-info">
              <h3>Response Time</h3>
              <p>Usually within 24 hours</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Skills Highlight -->
    <section class="skills-highlight">
      <h2 class="section-title">What I Can Help With</h2>
      <div class="skills-grid">
        <div class="skill-item">
          <div class="skill-icon">🐘</div>
          <h3>PHP/Laravel</h3>
          <p>Custom web applications, APIs, CMS</p>
        </div>
        <div class="skill-item">
          <div class="skill-icon">💚</div>
          <h3>Vue.js/React</h3>
          <p>Modern SPAs, dashboards, UI components</p>
        </div>
        <div class="skill-item">
          <div class="skill-icon">📱</div>
          <h3>Flutter</h3>
          <p>Cross-platform mobile apps</p>
        </div>
        <div class="skill-item">
          <div class="skill-icon">🐍</div>
          <h3>Python</h3>
          <p>Data processing, automation, AI/ML</p>
        </div>
        <div class="skill-item">
          <div class="skill-icon">🎮</div>
          <h3>Unity</h3>
          <p>2D/3D games, AR/VR experiences</p>
        </div>
        <div class="skill-item">
          <div class="skill-icon">☁️</div>
          <h3>Cloud & DevOps</h3>
          <p>AWS, Docker, CI/CD pipelines</p>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
      <div class="cta-content">
        <h2>Ready to Start Your Project?</h2>
        <p>Let's discuss how I can help bring your ideas to life.</p>
        <div class="cta-buttons">
          <router-link to="/projects" class="btn-primary">View My Work</router-link>
          <a href="#contact-form" class="btn-secondary">Get In Touch</a>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import ContactForm from '../components/ContactForm.vue'

onMounted(() => {
  console.log('Contact page mounted')
})
</script>

<style scoped>
.contact-container {
  min-height: 100vh;
  background: var(--gradient-secondary);
}

/* Navigation */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  background: rgba(10, 10, 10, 0.8);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--color-border-primary);
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
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: bold;
  font-size: 1.5rem;
}

.nav-links {
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: var(--color-text-secondary);
  text-decoration: none;
  transition: var(--transition-normal);
  position: relative;
  padding: 0.5rem 0;
}

.nav-link:hover,
.nav-link.active {
  color: var(--color-primary);
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: var(--gradient-primary);
  transition: width var(--transition-normal);
}

.nav-link:hover::after,
.nav-link.active::after {
  width: 100%;
}

/* Hero Section */
.contact-hero {
  padding: 8rem 2rem 4rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

.hero-title {
  font-size: clamp(2.5rem, 8vw, 4rem);
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 2rem;
}

.title-line {
  display: block;
}

.gradient-text {
  background: var(--gradient-accent);
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
  font-size: 1.2rem;
  color: var(--color-text-secondary);
  line-height: 1.6;
  max-width: 600px;
  margin: 0 auto;
}

/* Contact Section */
.contact-section {
  padding: 4rem 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: start;
}

.contact-form-wrapper {
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 15px;
  padding: 2rem;
  backdrop-filter: blur(10px);
}

.contact-info {
  padding: 2rem 0;
}

.info-title {
  font-size: 2rem;
  margin-bottom: 1rem;
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.info-description {
  color: var(--color-text-secondary);
  line-height: 1.6;
  margin-bottom: 2rem;
}

.contact-methods {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.contact-method {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 10px;
  transition: var(--transition-normal);
}

.contact-method:hover {
  border-color: var(--color-border-accent);
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(0, 255, 255, 0.1);
}

.method-icon {
  font-size: 1.5rem;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--gradient-primary);
  border-radius: 50%;
  color: var(--color-bg-primary);
}

.method-info h3 {
  color: var(--color-primary);
  margin-bottom: 0.3rem;
}

.method-info a,
.method-info p {
  color: var(--color-text-secondary);
  text-decoration: none;
  transition: var(--transition-normal);
}

.method-info a:hover {
  color: var(--color-primary);
}

.response-time {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
  border: 1px solid var(--color-border-accent);
  border-radius: 10px;
}

.response-icon {
  font-size: 2rem;
}

.response-info h3 {
  color: var(--color-accent);
  margin-bottom: 0.3rem;
}

.response-info p {
  color: var(--color-text-secondary);
}

/* Skills Highlight */
.skills-highlight {
  padding: 4rem 2rem;
  background: rgba(0, 0, 0, 0.3);
}

.section-title {
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 3rem;
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.skills-grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.skill-item {
  background: var(--color-bg-card);
  border: 1px solid var(--color-border-primary);
  border-radius: 12px;
  padding: 2rem;
  text-align: center;
  transition: var(--transition-normal);
  backdrop-filter: blur(10px);
}

.skill-item:hover {
  border-color: var(--color-border-accent);
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 255, 255, 0.2);
}

.skill-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.skill-item h3 {
  color: var(--color-primary);
  margin-bottom: 0.5rem;
}

.skill-item p {
  color: var(--color-text-secondary);
  line-height: 1.5;
}

/* CTA Section */
.cta-section {
  padding: 4rem 2rem;
  text-align: center;
  background: rgba(0, 0, 0, 0.5);
}

.cta-content h2 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  background: var(--gradient-accent);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.cta-content p {
  font-size: 1.2rem;
  color: var(--color-text-secondary);
  margin-bottom: 2rem;
}

.cta-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .nav-content {
    padding: 1rem;
  }

  .nav-links {
    gap: 1rem;
    font-size: 0.9rem;
  }

  .contact-hero {
    padding: 6rem 1rem 3rem;
  }

  .contact-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .contact-form-wrapper {
    padding: 1.5rem;
  }

  .skills-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }

  .contact-method {
    flex-direction: column;
    text-align: center;
  }

  .method-icon {
    margin: 0 auto 1rem;
  }
}

/* Touch Device Optimization */
@media (hover: none) and (pointer: coarse) {
  .contact-method:hover,
  .skill-item:hover {
    transform: none;
  }

  .nav-link::after {
    display: none;
  }
}
</style>