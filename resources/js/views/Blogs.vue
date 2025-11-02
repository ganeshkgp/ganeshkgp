<template>
  <AppLayout>
    <!-- Header Section -->
    <section class="relative bg-linear-to-br from-purple-900/20 via-black/50 to-cyan-900/20 backdrop-blur-lg">
      <div class="absolute inset-0 bg-black/40"></div>

      <!-- Animated Background Elements -->
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-20 right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute bottom-10 left-1/3 w-72 h-72 bg-pink-500/10 rounded-full blur-3xl animate-pulse delay-2000"></div>
      </div>

      <!-- Header Content -->
      <div class="relative z-10 px-6 pt-24 pb-16 lg:pt-32 lg:pb-24 flex items-center justify-center">
        <div class="w-full max-w-4xl text-center">
          <!-- Category Badge -->
          <div class="mb-8 flex justify-center">
            <div class="px-6 py-3 bg-linear-to-r from-cyan-500/20 to-purple-500/20 border border-cyan-500/30 rounded-full backdrop-blur-md inline-flex">
              <span class="text-cyan-400 font-semibold text-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                🚀 Tech Blog Hub
              </span>
            </div>
          </div>

          <!-- Main Title -->
          <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight text-center">
            <span class="bg-linear-to-r from-cyan-400 via-purple-400 to-pink-400 bg-clip-text text-transparent block">
              Explore Our
            </span>
            <span class="bg-linear-to-r from-pink-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent block">
              Tech Blog
            </span>
          </h1>


        </div>
      </div>
    </section>

    <!-- Filter Section -->
    <section class="sticky top-0 z-40 bg-black/60 backdrop-blur-xl border-b border-white/5">
      <div class="filter-container">
        <div class="bg-white/5 backdrop-blur-lg rounded-2xl border border-white/10 p-6">

            <!-- Filter Controls -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
              <!-- Search Bar -->
              <div class="md:col-span-7">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-white/40">🔍</span>
                  </div>
                  <input
                    v-model="searchQuery"
                    @input="debounceSearch"
                    type="text"
                    placeholder="Search articles, topics, or keywords..."
                    class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none transition-all duration-300"
                  />
                </div>
              </div>

              <!-- Category Filter -->
              <div class="md:col-span-3">
                <div class="relative">
                  <select
                    v-model="selectedCategory"
                    @change="resetPagination"
                    class="w-full appearance-none bg-white/5 border border-white/10 rounded-xl text-white px-4 py-3 pr-8 cursor-pointer focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none transition-all duration-300"
                  >
                    <option value="">All Categories</option>
                    <option v-for="category in getUniqueCategories()" :key="category" :value="category">
                      {{ formatCategory(category) }}
                    </option>
                  </select>
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-white/40 text-xs">▼</span>
                  </div>
                </div>
              </div>

              <!-- Per Page Filter -->
              <div class="md:col-span-2">
                <div class="relative">
                  <select
                    v-model="perPage"
                    @change="resetPagination"
                    class="w-full appearance-none bg-white/5 border border-white/10 rounded-xl text-white px-4 py-3 pr-8 cursor-pointer focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none transition-all duration-300"
                  >
                    <option value="6">6</option>
                    <option value="12">12</option>
                    <option value="24">24</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-white/40 text-xs">▼</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Active Filters -->
            <div v-if="searchQuery || selectedCategory" class="mt-4 flex items-center gap-2">
              <span class="text-white/60 text-sm">Active filters:</span>
              <div class="flex flex-wrap gap-2">
                <span
                  v-if="searchQuery"
                  @click="searchQuery = ''; debounceSearch()"
                  class="px-3 py-1 bg-cyan-500/20 border border-cyan-500/30 rounded-full text-cyan-400 text-xs cursor-pointer hover:bg-cyan-500/30 transition-colors flex items-center gap-1"
                >
                  {{ searchQuery }}
                  <span class="text-cyan-400">×</span>
                </span>
                <span
                  v-if="selectedCategory"
                  @click="selectedCategory = ''; resetPagination()"
                  class="px-3 py-1 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-400 text-xs cursor-pointer hover:bg-purple-500/30 transition-colors flex items-center gap-1"
                >
                  {{ formatCategory(selectedCategory) }}
                  <span class="text-purple-400">×</span>
                </span>
              </div>
            </div>
          </div>
        </div>

    </section>

    <!-- Results Info -->
    <section class="py-4" v-if="!loading && blogs.length > 0">
      <div class="filter-container">
        <div class="flex items-center justify-between">
          <p class="text-white/60">
            Showing <span class="text-cyan-400 font-medium">{{ pagination.from }}-{{ pagination.to }}</span> of <span class="text-purple-400 font-medium">{{ pagination.total }}</span> articles
          </p>
          <div class="flex items-center gap-2">
            <span class="text-white/40 text-sm">Sorted by:</span>
            <span class="text-cyan-400 text-sm font-medium">Latest First</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Blogs Grid -->
    <section class="py-12">
      <div class="blog-grid-section">
        <!-- Loading State -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-20">
          <div class="relative">
            <div class="w-20 h-20 border-4 border-cyan-500/20 rounded-full animate-spin border-t-cyan-500"></div>
            <div class="absolute inset-0 w-20 h-20 border-4 border-purple-500/20 rounded-full animate-spin border-b-purple-500" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
          </div>
          <p class="text-white/70 mt-6 text-lg">Loading amazing content...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="blogs.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
          <div class="relative mb-8">
            <div class="text-8xl opacity-20">🔍</div>
            <div class="absolute inset-0 text-8xl animate-ping opacity-10">🔍</div>
          </div>
          <h3 class="text-3xl font-bold text-white mb-4">No articles found</h3>
          <p class="text-white/60 text-lg mb-6 max-w-md">Try adjusting your filters or search terms to discover more content</p>
          <button @click="clearFilters" class="px-6 py-3 bg-linear-to-r from-cyan-500 to-purple-500 rounded-xl text-black font-medium hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300">
            Clear Filters
          </button>
        </div>

        <!-- Blog Grid - Responsive: 4 on PC, 3 on laptop, 1 on mobile -->
        <div v-else class="blog-grid-container">
          <article
            v-for="(blog, index) in blogs"
            :key="blog.id"
            :style="{ animationDelay: `${index * 100}ms` }"
            class="blog-card group cursor-pointer opacity-0 animate-fade-in-up"
            @click="navigateToBlog(blog.slug)"
          >
            <!-- Card Image -->
            <div class="relative h-64 overflow-hidden rounded-t-3xl bg-linear-to-br from-cyan-500/20 to-purple-500/20">
              <img
                v-if="blog.featured_image"
                :src="blog.featured_image"
                :alt="blog.title"
                loading="lazy"
                class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110 group-hover:brightness-110"
              />
              
              <div v-else class="w-full h-full flex items-center justify-center">
                <div class="text-6xl opacity-20">📝</div>
              </div>

              <!-- Overlay Gradient -->
              <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

              <!-- Floating Badges -->
              <div class="absolute top-4 left-4 right-4 flex justify-between items-start">
                <span class="px-3 py-1.5 p-3 bg-linear-to-r from-cyan-500/90 to-purple-500/90 backdrop-blur-sm rounded-full text-white text-xs font-bold shadow-lg">
                  {{ formatCategory(blog.category) }}
                </span>
                <span v-if="blog.is_featured" class="px-3 py-1.5 bg-linear-to-r from-yellow-500/90 to-orange-500/90 backdrop-blur-sm rounded-full text-black text-xs font-bold shadow-lg animate-pulse">
                  ⭐ FEATURED
                </span>
              </div>

              <!-- Reading Time -->
              <div class="absolute bottom-4 left-4">
                <span class="px-3 py-1.5 bg-black/60 backdrop-blur-sm rounded-full text-white/80 text-xs font-medium">
                  ⏱️ {{ blog.reading_time }} min read
                </span>
              </div>
            </div>

            <!-- Card Content -->
            <div class="p-6 bg-linear-to-br from-white/5 to-white/2 backdrop-blur-sm border border-white/10 rounded-b-3xl border-t-0 group-hover:border-cyan-500/30 transition-all duration-300">
              <!-- Date -->
              <div class="flex items-center text-white/50 text-xs mb-3">
                <span class="mr-2">📅</span>
                <span>{{ blog.published_at }}</span>
              </div>

              <!-- Title -->
              <h3 class="text-xl font-bold text-white mb-3 leading-tight group-hover:text-cyan-400 transition-colors duration-300 line-clamp-2">
                {{ blog.title }}
              </h3>

              <!-- Excerpt -->
              <p class="text-white/60 text-sm mb-4 leading-relaxed line-clamp-3">
                {{ blog.excerpt }}
              </p>

              <!-- Tags -->
              <div class="flex flex-wrap gap-2 mb-4" v-if="blog.tags && blog.tags.length > 0">
                <span v-for="tag in blog.tags.slice(0, 3)" :key="tag" class="px-2.5 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded-lg text-cyan-400 text-xs font-medium">
                  #{{ tag }}
                </span>
                <span v-if="blog.tags.length > 3" class="px-2.5 py-1 bg-white/10 rounded-lg text-white/40 text-xs">
                  +{{ blog.tags.length - 3 }}
                </span>
              </div>

              <!-- Footer -->
              <div class="flex items-center justify-between pt-4 border-t border-white/10">
                <div class="flex items-center gap-4 text-white/50 text-xs">
                  <span class="flex items-center gap-1">
                    <span>👀</span>
                    <span>{{ formatNumber(blog.stats.views) }}</span>
                  </span>
                  <span class="flex items-center gap-1">
                    <span>❤️</span>
                    <span>{{ formatNumber(blog.stats.likes) }}</span>
                  </span>
                </div>
                <div class="text-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <span class="text-xs font-medium">Read More →</span>
                </div>
              </div>
            </div>
          </article>
        </div>

        <!-- Enhanced Pagination -->
        <div v-if="pagination.last_page > 1" class="flex flex-col items-center gap-6 mt-12">
          <div class="flex items-center gap-2">
            <button
              :disabled="pagination.current_page === 1"
              @click="changePage(pagination.current_page - 1)"
              class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white/60 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/10 hover:border-cyan-500/30 hover:text-cyan-400 transition-all duration-300"
            >
              ← Previous
            </button>

            <div class="flex items-center gap-1">
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="changePage(page)"
                :class="[
                  'w-10 h-10 rounded-xl text-sm font-medium transition-all duration-300',
                  page === pagination.current_page
                    ? 'bg-linear-to-r from-cyan-500 to-purple-500 text-black shadow-lg shadow-cyan-500/25'
                    : 'bg-white/5 border border-white/10 text-white/60 hover:bg-white/10 hover:border-cyan-500/30 hover:text-cyan-400'
                ]"
              >
                {{ page }}
              </button>
            </div>

            <button
              :disabled="!pagination.has_more_pages"
              @click="changePage(pagination.current_page + 1)"
              class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white/60 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/10 hover:border-cyan-500/30 hover:text-cyan-400 transition-all duration-300"
            >
              Next →
            </button>
          </div>

          <div class="text-white/40 text-sm">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'

const router = useRouter()

// Store original overflow settings
let originalAppStyles = {}
let originalBodyStyles = {}
let originalHtmlStyles = {}

// Reactive data
const blogs = ref([])
const allCategories = ref([])
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0,
  from: 0,
  to: 0,
  has_more_pages: false,
})
const loading = ref(true)
const searchQuery = ref('')
const selectedCategory = ref('')
const perPage = ref(12)
const currentPage = ref(1)

// Debounce for search
let searchTimeout = null
const debounceSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    resetPagination()
  }, 500)
}

// Fetch all categories
const fetchCategories = async () => {
  try {
    // Get all blogs to extract categories
    const response = await fetch('/api/v1/home/blogs?per_page=1000')
    if (!response.ok) return []

    const data = await response.json()
    const categories = data.data.map(blog => blog.category).filter(Boolean)
    return [...new Set(categories)]
  } catch (error) {
    console.error('Error fetching categories:', error)
    return []
  }
}

// Fetch blogs from API with pagination
const fetchBlogs = async (page = 1) => {
  try {
    loading.value = true

    const params = new URLSearchParams({
      page: page.toString(),
      per_page: perPage.value.toString(),
    })

    if (searchQuery.value) {
      params.append('search', searchQuery.value)
    }

    if (selectedCategory.value) {
      params.append('category', selectedCategory.value)
    }

    const response = await fetch(`/api/v1/home/blogs?${params}`)
    if (!response.ok) {
      throw new Error('Failed to fetch blogs')
    }

    const data = await response.json()
    blogs.value = data.data
    pagination.value = data.pagination
    currentPage.value = page

  } catch (error) {
    console.error('Error fetching blogs:', error)
    blogs.value = []
  } finally {
    loading.value = false
  }
}

// Pagination helpers
const visiblePages = computed(() => {
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const delta = 2

  const range = []
  const rangeWithDots = []

  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }

  if (current - delta > 2) {
    rangeWithDots.push(1, '...')
  } else {
    rangeWithDots.push(1)
  }

  rangeWithDots.push(...range)

  if (current + delta < last - 1) {
    rangeWithDots.push('...', last)
  } else {
    rangeWithDots.push(last)
  }

  return rangeWithDots.filter((item, index, arr) => item !== arr[index - 1])
})

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page && page !== pagination.value.current_page) {
    fetchBlogs(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const resetPagination = () => {
  fetchBlogs(1)
}

// Helper methods
const getUniqueCategories = () => {
  return allCategories.value
}

const getUniqueCategoriesCount = () => {
  return allCategories.value.length
}

const formatCategory = (category) => {
  if (!category) return 'Uncategorized'
  return category.split('-').map(word =>
    word.charAt(0).toUpperCase() + word.slice(1)
  ).join(' ')
}

const formatNumber = (num) => {
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'k'
  }
  return num.toString()
}

const navigateToBlog = (slug) => {
  router.push(`/blog/${slug}`)
}

const clearFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  resetPagination()
}

// Watch for per page changes
watch(perPage, () => {
  resetPagination()
})

onMounted(async () => {
  console.log('Blogs page mounted - enabling scrolling')

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

  // Override App.vue overflow settings for Blogs page only
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
  body.classList.add('blogs-page-active')

  // Set smooth scrolling
  document.documentElement.style.scrollBehavior = 'smooth'

  // Fetch categories first
  allCategories.value = await fetchCategories()

  // Then fetch blogs
  await fetchBlogs(1)
})

onUnmounted(() => {
  console.log('Blogs page unmounted - restoring original overflow settings')

  // Restore original App.vue overflow settings when leaving Blogs page
  const app = document.getElementById('app')
  const body = document.body
  const html = document.documentElement

  // Remove the blogs page class
  body.classList.remove('blogs-page-active')

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
/* Override App.vue overflow settings for Blogs page only */
/* This ensures Blogs page can scroll while Home.vue remains non-scrollable */

/* Multiple approaches to override App.vue overflow settings for Blogs page only */

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
:global(body.blogs-page-active #app) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
  height: auto !important;
  min-height: 100vh !important;
}

:global(body.blogs-page-active) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

:global(body.blogs-page-active html) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Compact Header */
.blogs-header {
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  padding: 2rem 0;
  position: relative;
  overflow: hidden;
}

.blogs-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background:
    radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 0, 255, 0.1) 0%, transparent 50%);
  pointer-events: none;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  z-index: 2;
  flex-wrap: wrap;
  gap: 1rem;
}

.header-text {
  flex: 1;
}

.header-title {
  font-size: 2rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  background: linear-gradient(45deg, #00ffff, #ff00ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.header-subtitle {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.8);
  margin: 0;
}

.header-stats {
  display: flex;
  gap: 1rem;
}

.stat-badge {
  padding: 0.5rem 1rem;
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 20px;
  font-size: 0.9rem;
  color: #00ffff;
  font-weight: 600;
}

/* Filter Bar */
.filter-bar {
  background: rgba(0, 8, 20, 0.9);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.filter-controls {
  display: flex;
  gap: 1rem;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 2.5rem 0.75rem 1rem;
  border: 2px solid rgba(0, 255, 255, 0.3);
  border-radius: 25px;
  background: rgba(255, 255, 255, 0.05);
  color: white;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #00ffff;
  box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
}

.search-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #00ffff;
  font-size: 0.9rem;
}

.filter-group {
  display: flex;
  gap: 0.5rem;
}

.category-select,
.per-page-select {
  padding: 0.75rem 1rem;
  border: 2px solid rgba(0, 255, 255, 0.3);
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.05);
  color: white;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.category-select:focus,
.per-page-select:focus {
  outline: none;
  border-color: #00ffff;
  box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
}

.category-select option,
.per-page-select option {
  background: #0a0814;
  color: white;
}

/* Results Info */
.results-info {
  padding: 1rem 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.results-text {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
}

/* Blogs Section */
.blogs-section {
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  padding: 2rem 0;
  min-height: 60vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.loading {
  text-align: center;
  padding: 4rem 2rem;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(0, 255, 255, 0.3);
  border-top: 3px solid #00ffff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.no-results {
  text-align: center;
  padding: 4rem 2rem;
}

.no-results-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.no-results h3 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
  color: #00ffff;
}

.no-results p {
  color: rgba(255, 255, 255, 0.7);
}

/* Compact Blog Grid */
.blogs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.blog-card {
  background: linear-gradient(135deg,
    rgba(10, 10, 30, 0.9) 0%,
    rgba(26, 0, 51, 0.8) 50%,
    rgba(0, 8, 20, 0.9) 100%);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  display: flex;
  flex-direction: column;
}

.blog-card:hover {
  transform: translateY(-5px);
  border-color: rgba(0, 255, 255, 0.3);
  box-shadow:
    0 10px 30px rgba(0, 0, 0, 0.3),
    0 0 30px rgba(0, 255, 255, 0.1);
}

.blog-image {
  position: relative;
  height: 160px;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
}

.blog-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.blog-card:hover .blog-image img {
  transform: scale(1.05);
}

.blog-overlay {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.read-time {
  background: rgba(0, 0, 0, 0.8);
  padding: 0.3rem 0.6rem;
  border-radius: 15px;
  font-size: 0.75rem;
  color: white;
  backdrop-filter: blur(10px);
}

.featured-indicator {
  background: linear-gradient(45deg, #ffd60a, #ff6b00);
  padding: 0.3rem 0.6rem;
  border-radius: 15px;
  font-size: 0.75rem;
  color: #000;
  font-weight: 600;
}

.blog-content {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.blog-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
  gap: 0.5rem;
}

.category-badge {
  padding: 0.2rem 0.8rem;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  flex-shrink: 0;
}

.category-badge.web-development {
  background: linear-gradient(45deg, #00ffff, #0099cc);
  color: #000;
}

.category-badge.programming {
  background: linear-gradient(45deg, #ff00ff, #cc00cc);
  color: #fff;
}

.category-badge.ai-ml {
  background: linear-gradient(45deg, #ffd60a, #ff9900);
  color: #000;
}

.category-badge.devops {
  background: linear-gradient(45deg, #00ff88, #00cc66);
  color: #000;
}

.category-badge.cybersecurity {
  background: linear-gradient(45deg, #ff4444, #cc0000);
  color: #fff;
}

.blog-date {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.6);
  flex-shrink: 0;
}

.blog-title {
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0 0 0.75rem 0;
  color: #00ffff;
  text-shadow: 0 0 8px rgba(0, 255, 255, 0.3);
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.blog-excerpt {
  font-size: 0.9rem;
  line-height: 1.5;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 1rem;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.blog-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 0.75rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  gap: 1rem;
}

.blog-tags {
  display: flex;
  gap: 0.3rem;
  flex-wrap: wrap;
  flex: 1;
}

.tag {
  padding: 0.2rem 0.6rem;
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.2);
  border-radius: 12px;
  font-size: 0.7rem;
  color: #00ffff;
}

.blog-stats {
  display: flex;
  gap: 0.75rem;
  flex-shrink: 0;
}

.stat {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.6);
  display: flex;
  align-items: center;
  gap: 0.2rem;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin-top: 3rem;
  flex-wrap: wrap;
}

.pagination-btn {
  padding: 0.75rem 1.5rem;
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 20px;
  color: #00ffff;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  font-weight: 600;
}

.pagination-btn:hover:not(:disabled) {
  background: rgba(0, 255, 255, 0.2);
  border-color: #00ffff;
  transform: scale(1.05);
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-numbers {
  display: flex;
  gap: 0.5rem;
}

.pagination-number {
  min-width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  color: rgba(255, 255, 255, 0.8);
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pagination-number:hover {
  background: rgba(0, 255, 255, 0.1);
  border-color: rgba(0, 255, 255, 0.3);
  color: #00ffff;
  transform: scale(1.1);
}

.pagination-number.active {
  background: linear-gradient(45deg, #00ffff, #0099cc);
  border-color: #00ffff;
  color: #000;
  transform: scale(1.1);
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }

  .header-title {
    font-size: 1.5rem;
  }

  .filter-controls {
    flex-direction: column;
    gap: 1rem;
  }

  .search-box {
    max-width: 100%;
  }

  .filter-group {
    width: 100%;
    justify-content: center;
  }

  .blogs-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
  }

  .blog-content {
    padding: 1rem;
  }

  .blog-title {
    font-size: 1rem;
  }

  .blog-excerpt {
    font-size: 0.85rem;
  }

  .pagination {
    gap: 0.25rem;
  }

  .pagination-btn {
    padding: 0.6rem 1rem;
    font-size: 0.8rem;
  }
}

@media (max-width: 480px) {
  .blogs-grid {
    grid-template-columns: 1fr;
  }

  .blog-image {
    height: 140px;
  }

  .blog-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
  }

  .pagination-numbers {
    order: -1;
    width: 100%;
    justify-content: center;
    margin-bottom: 0.5rem;
  }
}

/* Custom Scrollbar for Blogs page only */
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

/* Firefox scrollbar styling for Blogs page only */
:deep(*) {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 214, 10, 0.6) rgba(0, 8, 20, 0.8);
}

/* Custom Animations */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes gradient-x {
  0%, 100% {
    background-size: 200% 200%;
    background-position: left center;
  }
  50% {
    background-size: 200% 200%;
    background-position: right center;
  }
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out forwards;
}

.animate-gradient-x {
  animation: gradient-x 3s ease infinite;
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
   line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
   line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Container Classes for Consistent Layout */
.filter-container {
  padding: 1.5rem 1rem;
}

.blog-grid-section {
  padding: 0 1rem;
}

/* Custom Blog Grid Layout - Full Width Spread */
.blog-grid-container {
  display: grid;
  gap: 2rem;
  padding-top: 20px;
}


/* Tablet: 2 columns */
@media (min-width: 768px) {
  .filter-container {
    padding: 1.5rem 2rem;
  }

  .blog-grid-section {
    padding: 0 2rem;
  }

  .blog-grid-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Laptop: 3 columns */
@media (min-width: 1024px) {
  .filter-container {
    padding: 1.5rem 2rem;
  }

  .blog-grid-section {
    padding: 0 2rem;
  }

  .blog-grid-container {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Desktop PC: 4 columns spread evenly */
@media (min-width: 1280px) {
  .filter-container {
    padding: 1.5rem 2rem;
  }

  .blog-grid-section {
    padding: 0 2rem;
  }

  .blog-grid-container {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* Large screens: 4 columns with proper spacing */
@media (min-width: 1536px) {
  .filter-container {
    padding: 1.5rem 4rem;
  }

  .blog-grid-section {
    padding: 0 4rem;
  }

  .blog-grid-container {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* Blog card adjustments - fill available space */
.blog-card {
  width: 100%;
  height: auto;
  min-height: 420px;
}

/* Mobile card adjustments */
@media (max-width: 767px) {
  .blog-card {
    min-height: 380px;
    max-width: 100%;
    margin: 0 auto;
  }
}

/* Tablet card adjustments */
@media (min-width: 768px) and (max-width: 1023px) {
  .blog-card {
    min-height: 400px;
  }
}

/* Custom scrollbar styling */
:deep(::-webkit-scrollbar) {
  width: 8px;
}

:deep(::-webkit-scrollbar-track) {
  background: rgba(0, 0, 0, 0.3);
}

:deep(::-webkit-scrollbar-thumb) {
  background: linear-gradient(45deg, rgba(0, 255, 255, 0.5), rgba(255, 0, 255, 0.5));
  border-radius: 4px;
}

:deep(::-webkit-scrollbar-thumb:hover) {
  background: linear-gradient(45deg, rgba(0, 255, 255, 0.7), rgba(255, 0, 255, 0.7));
}
</style>
