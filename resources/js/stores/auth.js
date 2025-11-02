import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

// API base URL
const API_BASE = '/api'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token'))
  const loading = ref(false)
  const error = ref(null)

  // Computed
  const isAuthenticated = computed(() => !!token.value && !!user.value)

  // Get auth headers for API requests
  const getAuthHeaders = () => {
    return token.value ? {
      'Authorization': `Bearer ${token.value}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    } : {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  }

  // Save token to localStorage
  const saveToken = (newToken) => {
    token.value = newToken
    localStorage.setItem('auth_token', newToken)
  }

  // Remove token from localStorage
  const removeToken = () => {
    token.value = null
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
  }

  // Save user to localStorage
  const saveUser = (userData) => {
    user.value = userData
    localStorage.setItem('user', JSON.stringify(userData))
  }

  // Load user from localStorage
  const loadUser = () => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
      try {
        user.value = JSON.parse(savedUser)
      } catch (e) {
        console.error('Failed to parse saved user data:', e)
        localStorage.removeItem('user')
      }
    }
  }

  // Initialize auth state from localStorage
  const initialize = () => {
    if (token.value) {
      loadUser()
      // Verify token is still valid by fetching current user
      fetchCurrentUser().catch(() => {
        // Token is invalid, clear it
        logout()
      })
    }
  }

  // API helper function
  const apiRequest = async (url, options = {}) => {
    const config = {
      headers: getAuthHeaders(),
      ...options
    }

    try {
      const response = await fetch(`${API_BASE}${url}`, config)
      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'Request failed')
      }

      return { data, response }
    } catch (err) {
      // Handle network errors or 401 unauthorized
      if (err.message.includes('fetch') || err.message.includes('Unauthenticated')) {
        await logout()
        throw new Error('Session expired. Please login again.')
      }
      throw err
    }
  }

  // Login user
  const login = async (credentials) => {
    try {
      loading.value = true
      error.value = null

      const { data } = await apiRequest('/auth/login', {
        method: 'POST',
        body: JSON.stringify(credentials)
      })

      // Save token and user data
      saveToken(data.token)
      saveUser(data.user)

      return data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  // Register new user
  const register = async (userData) => {
    try {
      loading.value = true
      error.value = null

      const { data } = await apiRequest('/auth/register', {
        method: 'POST',
        body: JSON.stringify(userData)
      })

      // Save token and user data
      saveToken(data.token)
      saveUser(data.user)

      return data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  // Fetch current user
  const fetchCurrentUser = async () => {
    try {
      const { data } = await apiRequest('/auth/user')
      saveUser(data.user)
      return data.user
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  // Update user profile
  const updateProfile = async (userData) => {
    try {
      loading.value = true
      error.value = null

      const { data } = await apiRequest('/auth/profile', {
        method: 'PUT',
        body: JSON.stringify(userData)
      })

      // Update user in store and localStorage
      saveUser(data.user)

      return data.user
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  // Change password
  const changePassword = async (passwordData) => {
    try {
      loading.value = true
      error.value = null

      const { data } = await apiRequest('/auth/change-password', {
        method: 'POST',
        body: JSON.stringify(passwordData)
      })

      // Password change successful, logout user
      await logout()

      return data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  // Logout current device
  const logout = async () => {
    try {
      if (token.value) {
        await apiRequest('/auth/logout', { method: 'POST' })
      }
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear auth state regardless of API call success
      removeToken()
      user.value = null
      error.value = null
    }
  }

  // Logout from all devices
  const logoutAll = async () => {
    try {
      if (token.value) {
        await apiRequest('/auth/logout-all', { method: 'POST' })
      }
    } catch (err) {
      console.error('Logout all error:', err)
    } finally {
      // Clear auth state regardless of API call success
      removeToken()
      user.value = null
      error.value = null
    }
  }

  // Refresh token
  const refreshToken = async () => {
    try {
      loading.value = true
      error.value = null

      const { data } = await apiRequest('/auth/refresh', {
        method: 'POST'
      })

      // Update token
      saveToken(data.token)

      return data.token
    } catch (err) {
      error.value = err.message
      // If refresh fails, logout the user
      await logout()
      throw err
    } finally {
      loading.value = false
    }
  }

  // Check if user has specific permission (for future use)
  const hasPermission = (permission) => {
    return user.value?.permissions?.includes(permission) || false
  }

  // Check if user has specific role (for future use)
  const hasRole = (role) => {
    return user.value?.role === role
  }

  // Get user display name
  const displayName = computed(() => {
    return user.value?.name || 'User'
  })

  // Get user initials for avatar
  const userInitials = computed(() => {
    if (!user.value?.name) return 'U'
    return user.value.name
      .split(' ')
      .map(word => word.charAt(0).toUpperCase())
      .join('')
      .slice(0, 2)
  })

  // Get user avatar URL
  const userAvatar = computed(() => {
    if (user.value?.avatar) {
      return user.value.avatar
    }
    // Generate avatar from initials
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(userInitials.value)}&color=FFFFFF&background=00ffff&length=2&bold=true`
  })

  // Initialize auth on store creation
  initialize()

  return {
    // State
    user,
    token,
    loading,
    error,

    // Computed
    isAuthenticated,
    displayName,
    userInitials,
    userAvatar,

    // Actions
    login,
    register,
    logout,
    logoutAll,
    refreshToken,
    fetchCurrentUser,
    updateProfile,
    changePassword,
    hasPermission,
    hasRole,
    getAuthHeaders,
    initialize
  }
})