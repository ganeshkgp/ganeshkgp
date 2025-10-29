<template>
  <AppLayout>
    <div class="contact-container">
      <!-- Header -->
      <div class="contact-header">
        <h1 class="hero-title">
          <span class="title-line">Let's Build</span>
          <span class="title-line gradient-text">Something Amazing</span>
        </h1>
        <p class="hero-subtitle">
          I'm always excited to work on innovative projects. Whether you have a specific need or just want to chat about technology, I'm here to help.
        </p>
      </div>

      <!-- Main Content Grid -->
      <div class="contact-main-grid">
        <!-- Contact Form Section -->
        <div class="form-section">
          <div class="section-title">
            <h2>Get In Touch</h2>
          </div>
          <ContactForm />
        </div>

        <!-- Contact Info Section -->
        <div class="info-section">
          <div class="section-title">
            <h2>Connect With Me</h2>
          </div>

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
                <p>Remote work worldwide</p>
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

          <!-- Skills -->
          <div class="skills-section">
            <h3>Skills & Expertise</h3>
            <div class="skills-grid">
              <div class="skill-item">
                <div class="skill-icon">🐘</div>
                <h4>PHP/Laravel</h4>
              </div>
              <div class="skill-item">
                <div class="skill-icon">💚</div>
                <h4>Vue.js/React</h4>
              </div>
              <div class="skill-item">
                <div class="skill-icon">📱</div>
                <h4>Flutter</h4>
              </div>
              <div class="skill-item">
                <div class="skill-icon">🐍</div>
                <h4>Python</h4>
              </div>
              <div class="skill-item">
                <div class="skill-icon">🎮</div>
                <h4>Unity</h4>
              </div>
              <div class="skill-item">
                <div class="skill-icon">☁️</div>
                <h4>Cloud & DevOps</h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom CTA -->
      <div class="bottom-cta">
        <router-link to="/projects" class="btn-primary">View My Work</router-link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import ContactForm from '../components/ContactForm.vue'

onMounted(() => {
  console.log('Contact page mounted')
})
</script>

<style scoped>
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