<template>
  <div class="user-auth">
    <!-- User is authenticated -->
    <div v-if="authStore.isAuthenticated" class="flex items-center gap-3">
      <!-- User Avatar -->
      <div class="relative group">
        <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-purple-500 rounded-full flex items-center justify-center text-black text-sm font-bold cursor-pointer hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300">
          {{ authStore.userInitials }}
        </div>

        <!-- User Dropdown -->
        <div class="absolute right-0 mt-2 w-48 bg-black/90 border border-cyan-500/30 rounded-lg shadow-xl backdrop-blur-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
          <div class="p-4 border-b border-cyan-500/20">
            <p class="text-cyan-400 font-medium text-sm">{{ authStore.displayName }}</p>
            <p class="text-white/50 text-xs">{{ authStore.user?.email }}</p>
          </div>
          <div class="p-2">
            <button
              @click="handleLogout"
              :disabled="loading"
              class="w-full text-left px-3 py-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm transition-all duration-300 disabled:opacity-50"
            >
              <span v-if="loading" class="flex items-center gap-2">
                <div class="w-3 h-3 border border-white/20 border-t-white rounded-full animate-spin"></div>
                Signing out...
              </span>
              <span v-else class="flex items-center gap-2">
                <span>🚪</span>
                Sign Out
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- User is not authenticated -->
    <div v-else class="flex items-center gap-3">
      <router-link
        to="/login"
        class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white/70 hover:bg-white/20 hover:text-white transition-all duration-300 text-sm"
      >
        Sign In
      </router-link>
      <router-link
        to="/register"
        class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-purple-500 text-black rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300 text-sm"
      >
        Join
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)

const handleLogout = async () => {
  try {
    loading.value = true
    await authStore.logout()
    router.push('/')
  } catch (err) {
    console.error('Logout error:', err)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.user-auth {
  position: relative;
  z-index: 40;
}

/* Ensure dropdown is above other content */
.group:hover .group-hover\:visible {
  visibility: visible;
}
</style>