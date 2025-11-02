<template>
  <AppLayout>
    <div class="min-h-screen flex items-center justify-center px-4">
      <!-- Background Elements -->
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-20 right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-10 left-1/3 w-72 h-72 bg-pink-500/10 rounded-full blur-3xl animate-pulse delay-2000"></div>
      </div>

      <!-- Login Card -->
      <div class="relative z-10 w-full max-w-md">
        <div class="bg-black/50 border border-cyan-500/20 rounded-2xl p-8 backdrop-blur-lg shadow-2xl">
          <!-- Header -->
          <div class="text-center mb-8">
            <div class="mb-4">
              <div class="w-16 h-16 bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full flex items-center justify-center mx-auto text-2xl">
                🚀
              </div>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
            <p class="text-white/70">Sign in to your Space Portfolio account</p>
          </div>

          <!-- Login Form -->
          <form @submit.prevent="handleLogin" class="space-y-6">
            <!-- Email Field -->
            <div>
              <label class="block text-cyan-400 text-sm font-medium mb-2">Email</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-white/40">📧</span>
                </div>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  :disabled="loading"
                  class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/40 focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none transition-all duration-300"
                  placeholder="your@email.com"
                />
              </div>
              <div v-if="errors.email" class="mt-2 text-red-400 text-sm">
                {{ errors.email[0] }}
              </div>
            </div>

            <!-- Password Field -->
            <div>
              <label class="block text-cyan-400 text-sm font-medium mb-2">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-white/40">🔒</span>
                </div>
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  :disabled="loading"
                  class="w-full pl-10 pr-12 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/40 focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none transition-all duration-300"
                  placeholder="Enter your password"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                  <span class="text-white/60 hover:text-cyan-400 transition-colors">
                    {{ showPassword ? '👁️' : '👁️‍🗨️' }}
                  </span>
                </button>
              </div>
              <div v-if="errors.password" class="mt-2 text-red-400 text-sm">
                {{ errors.password[0] }}
              </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
              <label class="flex items-center">
                <input
                  v-model="form.remember"
                  type="checkbox"
                  class="w-4 h-4 bg-white/10 border-white/20 rounded text-cyan-500 focus:ring-cyan-500 focus:ring-2"
                />
                <span class="ml-2 text-white/70 text-sm">Remember me</span>
              </label>
              <router-link to="/forgot-password" class="text-cyan-400 hover:text-cyan-300 text-sm transition-colors">
                Forgot password?
              </router-link>
            </div>

            <!-- Login Button -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full py-3 bg-gradient-to-r from-cyan-500 to-purple-500 text-black rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <div class="w-4 h-4 border-2 border-black/20 border-t-black rounded-full animate-spin mr-2"></div>
                Signing in...
              </span>
              <span v-else>Sign In</span>
            </button>

            <!-- Error Message -->
            <div v-if="error" class="bg-red-500/20 border border-red-500/30 rounded-lg p-3 text-red-400 text-sm">
              {{ error }}
            </div>
          </form>

          <!-- Register Link -->
          <div class="mt-6 text-center">
            <p class="text-white/70">
              Don't have an account?
              <router-link to="/register" class="text-cyan-400 hover:text-cyan-300 transition-colors font-medium">
                Sign up
              </router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

// Form data
const form = reactive({
  email: '',
  password: '',
  remember: false
})

// State
const loading = ref(false)
const error = ref('')
const errors = ref({})
const showPassword = ref(false)

// Store original overflow settings
let originalAppStyles = {}
let originalBodyStyles = {}
let originalHtmlStyles = {}

// Handle login
const handleLogin = async () => {
  try {
    loading.value = true
    error.value = ''
    errors.value = {}

    await authStore.login(form)

    // Redirect to intended page or home
    const intended = router.currentRoute.value.query.redirect
    router.push(intended || '/')

  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors
    } else {
      error.value = err.response?.data?.message || 'Login failed. Please try again.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  console.log('Login page mounted - enabling scrolling')

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

  // Override App.vue overflow settings for Login page only
  if (app) {
    app.style.setProperty('overflow-y', 'auto', 'important')
    app.style.setProperty('overflow-x', 'hidden', 'important')
    app.style.setProperty('height', 'auto', 'important')
    app.style.setProperty('min-height', '100vh', 'important')
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
  body.classList.add('login-page-active')

  // Set smooth scrolling
  document.documentElement.style.scrollBehavior = 'smooth'
})

// Cleanup on unmount
onUnmounted(() => {
  console.log('Login page unmounted - restoring original overflow settings')

  const app = document.getElementById('app')
  const body = document.body
  const html = document.documentElement

  // Remove the login page class
  body.classList.remove('login-page-active')

  if (app) {
    app.style.removeProperty('overflow-y')
    app.style.removeProperty('overflow-x')
    app.style.removeProperty('height')
    app.style.removeProperty('min-height')
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
/* Override App.vue overflow settings for Login page only */
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

:global(body.login-page-active #app) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
  height: auto !important;
  min-height: 100vh !important;
}

:global(body.login-page-active) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

:global(body.login-page-active html) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Custom scrollbar for Login page */
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

/* Firefox scrollbar styling */
:deep(*) {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 214, 10, 0.6) rgba(0, 8, 20, 0.8);
}

/* Animation delays */
.delay-1000 {
  animation-delay: 1s;
}

.delay-2000 {
  animation-delay: 2s;
}

/* Checkbox styling */
input[type="checkbox"]:checked {
  background-color: rgb(6 182 212);
  border-color: rgb(6 182 212);
}
</style>