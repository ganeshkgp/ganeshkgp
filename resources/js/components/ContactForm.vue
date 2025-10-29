<template>
  <div class="contact-form-container">
    <div class="contact-header">
      <h2 class="text-neon-primary">Let's Connect</h2>
      <p>Have a project in mind? I'd love to hear from you!</p>
    </div>

    <form @submit.prevent="handleSubmit" class="contact-form">
      <div class="form-row">
        <div class="form-group">
          <label for="name" class="form-label">Name *</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="form-input"
            :class="{ 'error': errors.name }"
            placeholder="John Doe"
            required
          />
          <span v-if="errors.name" class="form-error">{{ errors.name }}</span>
        </div>

        <div class="form-group">
          <label for="email" class="form-label">Email *</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="form-input"
            :class="{ 'error': errors.email }"
            placeholder="john@example.com"
            required
          />
          <span v-if="errors.email" class="form-error">{{ errors.email }}</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="phone" class="form-label">Phone</label>
          <input
            id="phone"
            v-model="form.phone"
            type="tel"
            class="form-input"
            placeholder="+1 (555) 123-4567"
          />
        </div>

        <div class="form-group">
          <label for="company" class="form-label">Company</label>
          <input
            id="company"
            v-model="form.company"
            type="text"
            class="form-input"
            placeholder="Acme Corp"
          />
        </div>
      </div>

      <div class="form-group">
        <label for="subject" class="form-label">Subject</label>
        <input
          id="subject"
          v-model="form.subject"
          type="text"
          class="form-input"
          placeholder="Project Inquiry"
        />
      </div>

      <div class="form-group">
        <label for="message" class="form-label">Message *</label>
        <textarea
          id="message"
          v-model="form.message"
          class="form-textarea"
          :class="{ 'error': errors.message }"
          placeholder="Tell me about your project..."
          rows="5"
          required
        ></textarea>
        <span v-if="errors.message" class="form-error">{{ errors.message }}</span>
        <div class="char-count">
          {{ form.message.length }}/2000 characters
        </div>
      </div>

      <div class="form-actions">
        <button
          type="submit"
          class="btn-primary"
          :disabled="isSubmitting"
        >
          <span v-if="isSubmitting">
            <span class="loading-spinner small"></span>
            Sending...
          </span>
          <span v-else>Send Message</span>
        </button>
      </div>
    </form>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content success-modal" @click.stop>
        <div class="success-icon">✨</div>
        <h3>Message Sent Successfully!</h3>
        <p>Thank you for reaching out. I'll get back to you within 24 hours.</p>
        <button @click="closeModal" class="btn-primary">Awesome!</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'

const form = reactive({
  name: '',
  email: '',
  phone: '',
  company: '',
  subject: '',
  message: ''
})

const errors = reactive({})
const isSubmitting = ref(false)
const showSuccessModal = ref(false)

const validateForm = () => {
  // Clear previous errors
  Object.keys(errors).forEach(key => delete errors[key])

  let isValid = true

  // Name validation
  if (!form.name.trim()) {
    errors.name = 'Name is required'
    isValid = false
  } else if (form.name.length < 2) {
    errors.name = 'Name must be at least 2 characters'
    isValid = false
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!form.email.trim()) {
    errors.email = 'Email is required'
    isValid = false
  } else if (!emailRegex.test(form.email)) {
    errors.email = 'Please enter a valid email address'
    isValid = false
  }

  // Message validation
  if (!form.message.trim()) {
    errors.message = 'Message is required'
    isValid = false
  } else if (form.message.length < 10) {
    errors.message = 'Message must be at least 10 characters'
    isValid = false
  } else if (form.message.length > 2000) {
    errors.message = 'Message must be less than 2000 characters'
    isValid = false
  }

  return isValid
}

const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  isSubmitting.value = true

  try {
    const response = await axios.post('/api/v1/contact', form)

    if (response.data.success) {
      showSuccessModal.value = true
      resetForm()
    }
  } catch (error) {
    console.error('Error sending message:', error)

    if (error.response?.data?.errors) {
      // Handle validation errors from server
      Object.assign(errors, error.response.data.errors)
    } else {
      // Handle other errors
      alert('Oops! Something went wrong. Please try again.')
    }
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  Object.assign(form, {
    name: '',
    email: '',
    phone: '',
    company: '',
    subject: '',
    message: ''
  })
  Object.keys(errors).forEach(key => delete errors[key])
}

const closeModal = () => {
  showSuccessModal.value = false
}
</script>

<style scoped>
.contact-form-container {
  max-width: 100%;
  margin: 0;
  padding: 0;
}

.contact-header {
  text-align: center;
  margin-bottom: 1.5rem;
}

.contact-header h2 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.contact-header p {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  position: relative;
}

.form-label {
  color: #ffd60a;
  font-weight: 600;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.form-input,
.form-textarea {
  background: rgba(0, 8, 20, 0.8);
  border: 1px solid rgba(255, 214, 10, 0.2);
  border-radius: 8px;
  padding: 0.6rem;
  color: #ffffff;
  font-family: inherit;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #ffd60a;
  box-shadow: 0 0 10px rgba(255, 214, 10, 0.2);
  background: rgba(0, 8, 20, 0.9);
}

.form-input.error,
.form-textarea.error {
  border-color: #ff4444;
  box-shadow: 0 0 10px rgba(255, 68, 68, 0.2);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.form-error {
  color: #ff4444;
  font-size: 0.8rem;
  margin-top: 0.3rem;
  display: block;
}

.char-count {
  text-align: right;
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.5);
  margin-top: 0.3rem;
}

.form-actions {
  text-align: center;
  margin-top: 1rem;
}

.btn-primary {
  min-width: 200px;
  position: relative;
  overflow: hidden;
}

.loading-spinner.small {
  width: 16px;
  height: 16px;
  border-width: 2px;
  display: inline-block;
  margin-right: 0.5rem;
  vertical-align: middle;
}

/* Modal Styles */
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
  z-index: 1000;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: rgba(0, 8, 20, 0.9);
  border: 1px solid rgba(255, 214, 10, 0.3);
  border-radius: 15px;
  padding: 2rem;
  max-width: 400px;
  width: 90%;
  text-align: center;
  box-shadow: 0 0 30px rgba(255, 214, 10, 0.2);
  animation: modalSlideIn 0.3s ease;
  backdrop-filter: blur(10px);
}

@keyframes modalSlideIn {
  from {
    transform: translateY(-50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.success-modal .success-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  animation: sparkle 1s ease-in-out;
}

@keyframes sparkle {
  0%, 100% {
    transform: scale(1) rotate(0deg);
  }
  50% {
    transform: scale(1.2) rotate(180deg);
  }
}

.success-modal h3 {
  color: #00ff88;
  margin-bottom: 1rem;
  font-size: 1.5rem;
}

.success-modal p {
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 2rem;
  line-height: 1.6;
}

/* Mobile Optimization */
@media (max-width: 768px) {
  .contact-form-container {
    padding: 1rem;
  }

  .contact-header h2 {
    font-size: 2rem;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .form-input,
  .form-textarea {
    padding: 0.6rem;
    font-size: 0.9rem;
  }

  .modal-content {
    padding: 1.5rem;
    margin: 1rem;
  }
}

/* Touch Device Optimization */
@media (hover: none) and (pointer: coarse) {
  .form-input,
  .form-textarea {
    font-size: 16px; /* Prevent zoom on iOS */
  }

  .btn-primary {
    min-height: 44px; /* Minimum touch target size */
  }
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
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(255, 214, 10, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.btn-primary:disabled:hover {
  transform: none;
  box-shadow: none;
}

/* Loading Spinner */
.loading-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid transparent;
  border-top: 2px solid #0a0a0a;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  display: inline-block;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>