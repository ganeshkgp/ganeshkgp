<template>
  <AppLayout>
    <div v-if="loading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Loading amazing content...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <div class="error-icon">🚫</div>
      <h2>{{ error }}</h2>
      <p>{{ errorMessage }}</p>
      <button @click="$router.push('/blogs')" class="back-button">
        ← Back to Blog
      </button>
    </div>

    <article v-else-if="blog" class="blog-details">
      <!-- Hero Section -->
      <section class="blog-hero" :style="heroStyle">
        <div class="hero-overlay">
          <div class="container">
            <div class="blog-breadcrumb">
              <router-link to="/blogs" class="breadcrumb-link">Blog</router-link>
              <span class="breadcrumb-separator">›</span>
              <span class="breadcrumb-current">{{ blog.title }}</span>
            </div>

            <div class="blog-meta-header">
              <span class="category-badge" :class="blog.category">
                {{ formatCategory(blog.category) }}
              </span>
              <span class="featured-badge" v-if="blog.is_featured">⭐ Featured</span>
            </div>

            <h1 class="blog-title">{{ blog.title }}</h1>
            <p class="blog-excerpt">{{ blog.excerpt }}</p>

            <div class="blog-author-info">
              <div class="author-avatar">
                <img :src="blog.author.avatar" :alt="blog.author.name" />
              </div>
              <div class="author-details">
                <h3>{{ blog.author.name }}</h3>
                <p>{{ blog.author.bio }}</p>
                <div class="author-social">
                  <a :href="blog.author.social.github" target="_blank" class="social-link">GitHub</a>
                  <a :href="blog.author.social.linkedin" target="_blank" class="social-link">LinkedIn</a>
                  <a :href="blog.author.social.twitter" target="_blank" class="social-link">Twitter</a>
                </div>
              </div>
            </div>

            <div class="blog-meta-details">
              <div class="meta-item">
                <span class="meta-icon">📅</span>
                <span>{{ blog.published_at }}</span>
              </div>
              <div class="meta-item">
                <span class="meta-icon">⏱️</span>
                <span>{{ blog.reading_time }} min read</span>
              </div>
              <div class="meta-item">
                <span class="meta-icon">👀</span>
                <span>{{ blog.stats.views }} views</span>
              </div>
              <div class="meta-item">
                <span class="meta-icon">❤️</span>
                <span>{{ blog.stats.likes }} likes</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Content Section -->
      <section class="blog-content-section">
        <div class="container">
          <div class="content-layout">
            <!-- Table of Contents -->
            <aside class="table-of-contents" v-if="blog.table_of_contents && blog.table_of_contents.length > 0">
              <h3>📋 Table of Contents</h3>
              <nav>
                <ul>
                  <li v-for="item in blog.table_of_contents" :key="item.slug" :class="'level-' + item.level">
                    <a :href="'#' + item.slug" @click="scrollToSection(item.slug)">
                      {{ item.title }}
                    </a>
                  </li>
                </ul>
              </nav>
            </aside>

            <!-- Main Content -->
            <main class="blog-content">
              <div class="blog-tags" v-if="blog.tags && blog.tags.length > 0">
                <span v-for="tag in blog.tags" :key="tag" class="tag">
                  #{{ tag }}
                </span>
              </div>

              <div class="blog-body" v-html="blog.content"></div>

              <!-- Blog Stats Bar -->
              <div class="blog-stats-bar">
                <div class="stats-left">
                  <button class="stat-button like-button" @click="likeBlog">
                    <span class="icon">❤️</span>
                    <span>{{ blog.stats.likes }}</span>
                  </button>
                  <button class="stat-button comment-button" @click="scrollToComments">
                    <span class="icon">💬</span>
                    <span>{{ blog.stats.comments }}</span>
                  </button>
                </div>
                <div class="stats-right">
                  <button class="stat-button share-button" @click="shareBlog">
                    <span class="icon">🔗</span>
                    <span>Share</span>
                  </button>
                </div>
              </div>
            </main>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
              <!-- Newsletter Signup -->
              <div class="sidebar-card">
                <h3>📧 Newsletter</h3>
                <p>Get the latest tech articles delivered to your inbox.</p>
                <form @submit.prevent="subscribeNewsletter" class="newsletter-form">
                  <input
                    v-model="newsletterEmail"
                    type="email"
                    placeholder="Your email"
                    required
                  />
                  <button type="submit">Subscribe</button>
                </form>
              </div>

              <!-- Related Posts -->
              <div class="sidebar-card" v-if="blog.related_posts && blog.related_posts.length > 0">
                <h3>📚 Related Articles</h3>
                <div class="related-posts">
                  <router-link
                    v-for="post in blog.related_posts"
                    :key="post.id"
                    :to="'/blog/' + post.slug"
                    class="related-post"
                  >
                    <div class="related-post-image" v-if="post.featured_image">
                      <img :src="post.featured_image" :alt="post.title" />
                    </div>
                    <div class="related-post-content">
                      <h4>{{ post.title }}</h4>
                      <p>{{ post.excerpt }}</p>
                      <span class="related-post-meta">{{ post.reading_time }} min read</span>
                    </div>
                  </router-link>
                </div>
              </div>
            </aside>
          </div>
        </div>
      </section>

      <!-- Comments Section -->
      <section id="comments" class="comments-section">
        <div class="container">
          <h2>💬 Comments ({{ blog.stats.comments }})</h2>

          <div class="comment-form">
            <h3>Leave a Comment</h3>
            <form @submit.prevent="submitComment">
              <div class="form-group">
                <input v-model="commentForm.name" type="text" placeholder="Your name" required />
              </div>
              <div class="form-group">
                <input v-model="commentForm.email" type="email" placeholder="Your email" required />
              </div>
              <div class="form-group">
                <textarea
                  v-model="commentForm.message"
                  placeholder="Your comment"
                  rows="4"
                  required
                ></textarea>
              </div>
              <button type="submit" class="submit-comment-btn">Post Comment</button>
            </form>
          </div>
        </div>
      </section>
    </article>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'

const route = useRoute()
const router = useRouter()

// Store original overflow settings
let originalAppStyles = {}
let originalBodyStyles = {}
let originalHtmlStyles = {}

const blog = ref(null)
const loading = ref(true)
const error = ref('')
const errorMessage = ref('')

// Form states
const newsletterEmail = ref('')
const commentForm = ref({
  name: '',
  email: '',
  message: ''
})

// Computed properties
const heroStyle = computed(() => {
  if (!blog.value?.featured_image) return {}
  return {
    backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(${blog.value.featured_image})`
  }
})

// Fetch blog details
const fetchBlogDetails = async () => {
  try {
    const slug = route.params.slug
    const response = await fetch(`/api/v1/home/blogs/${slug}`)

    if (!response.ok) {
      if (response.status === 404) {
        error.value = 'Blog Not Found'
        errorMessage.value = 'The requested blog post could not be found.'
      } else {
        throw new Error('Failed to fetch blog details')
      }
      return
    }

    const blogData = await response.json()
    blog.value = blogData

    // Update page title
    document.title = `${blogData.title} - Tech Blog`

  } catch (err) {
    console.error('Error fetching blog details:', err)
    error.value = 'Error Loading Blog'
    errorMessage.value = 'Failed to load the blog post. Please try again later.'
  } finally {
    loading.value = false
  }
}

// Helper methods
const formatCategory = (category) => {
  if (!category) return 'Uncategorized'
  return category.split('-').map(word =>
    word.charAt(0).toUpperCase() + word.slice(1)
  ).join(' ')
}

const scrollToSection = (slug) => {
  const element = document.getElementById(slug)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const scrollToComments = () => {
  const commentsSection = document.getElementById('comments')
  if (commentsSection) {
    commentsSection.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const likeBlog = () => {
  // Implement like functionality
  if (blog.value) {
    blog.value.stats.likes++
  }
}

const shareBlog = () => {
  if (navigator.share) {
    navigator.share({
      title: blog.value.title,
      text: blog.value.excerpt,
      url: window.location.href
    })
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(window.location.href)
    // You could show a toast notification here
  }
}

const subscribeNewsletter = () => {
  // Implement newsletter subscription
  console.log('Newsletter subscription:', newsletterEmail.value)
  newsletterEmail.value = ''
  // Show success message
}

const submitComment = () => {
  // Implement comment submission
  console.log('New comment:', commentForm.value)
  // Reset form
  commentForm.value = { name: '', email: '', message: '' }
  // Update comment count
  if (blog.value) {
    blog.value.stats.comments++
  }
}

onMounted(() => {
  console.log('BlogDetails page mounted - enabling scrolling')

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

  // Override App.vue overflow settings for BlogDetails page only
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
  body.classList.add('blog-details-page-active')

  // Set smooth scrolling
  document.documentElement.style.scrollBehavior = 'smooth'

  fetchBlogDetails()
})

onUnmounted(() => {
  console.log('BlogDetails page unmounted - restoring original overflow settings')

  // Restore original App.vue overflow settings when leaving BlogDetails page
  const app = document.getElementById('app')
  const body = document.body
  const html = document.documentElement

  // Remove the blog details page class
  body.classList.remove('blog-details-page-active')

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
/* Override App.vue overflow settings for BlogDetails page only */
/* This ensures BlogDetails page can scroll while Home.vue remains non-scrollable */

/* Multiple approaches to override App.vue overflow settings for BlogDetails page only */

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
:global(body.blog-details-page-active #app) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
  height: auto !important;
  min-height: 100vh !important;
}

:global(body.blog-details-page-active) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Override AppLayout styles for blog page responsive behavior */
:global(.app-layout) {
  overflow: hidden !important;
  min-height: 100vh !important;
}

:global(.main-content) {
  width: 100% !important;
  min-height: 100vh !important;
  overflow: hidden !important;
  position: relative !important;
  padding: 0 !important;
}

/* Blog details page specific layout overrides */
.blog-details {
  width: 100% !important;
  min-height: 100vh !important;
  overflow-y: auto !important;
  overflow-x: hidden !important;
  position: relative !important;
  margin: 0 !important;
  padding: 0 !important;
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%) !important;
}

/* Ensure blog hero and content sections take full width */
.blog-hero {
  width: 100% !important;
  max-width: none !important;
  margin: 0 !important;
  padding: 4rem 2rem !important;
}

.blog-content-section {
  width: 100% !important;
  max-width: none !important;
  margin: 0 !important;
  padding: 2rem 0 !important;
}

/* Override container styles for responsive behavior */
.container {
  max-width: 100% !important;
  width: 100% !important;
  padding: 0 1rem !important;
  margin: 0 !important;
}

:global(body.blog-details-page-active html) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Loading & Error States */
.loading-container,
.error-container {
  min-height: 70vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 2rem;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 3px solid rgba(0, 255, 255, 0.3);
  border-top: 3px solid #00ffff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 2rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-icon {
  font-size: 5rem;
  margin-bottom: 2rem;
}

.error-container h2 {
  font-size: 2rem;
  color: #ff6b6b;
  margin-bottom: 1rem;
}

.back-button {
  padding: 1rem 2rem;
  background: linear-gradient(45deg, #00ffff, #0099cc);
  border: none;
  border-radius: 25px;
  color: #000;
  font-weight: 600;
  cursor: pointer;
  margin-top: 2rem;
  transition: all 0.3s ease;
}

.back-button:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 20px rgba(0, 255, 255, 0.4);
}

/* Blog Hero Section */
.blog-hero {
  min-height: 70vh;
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  background-size: cover;
  background-position: center;
  position: relative;
  display: flex;
  align-items: center;
  overflow: hidden;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.8) 0%,
    rgba(26, 0, 51, 0.7) 50%,
    rgba(0, 8, 20, 0.8) 100%);
  z-index: 1;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  position: relative;
  z-index: 2;
}

.blog-breadcrumb {
  margin-bottom: 2rem;
}

.breadcrumb-link {
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: color 0.3s ease;
}

.breadcrumb-link:hover {
  color: #00ffff;
}

.breadcrumb-separator {
  margin: 0 0.5rem;
  color: rgba(255, 255, 255, 0.5);
}

.breadcrumb-current {
  color: #00ffff;
}

.blog-meta-header {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.category-badge {
  padding: 0.5rem 1.5rem;
  border-radius: 25px;
  font-size: 0.9rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
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

.featured-badge {
  padding: 0.5rem 1.5rem;
  background: linear-gradient(45deg, #ffd60a, #ff6b00);
  border-radius: 25px;
  font-size: 0.9rem;
  font-weight: 600;
  color: #000;
}

.blog-title {
  font-size: clamp(2.5rem, 5vw, 4rem);
  font-weight: 900;
  margin: 0 0 1.5rem 0;
  color: white;
  text-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
  line-height: 1.2;
}

.blog-excerpt {
  font-size: 1.3rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 3rem;
  line-height: 1.6;
}

.blog-author-info {
  display: flex;
  gap: 2rem;
  margin-bottom: 2rem;
  align-items: center;
}

.author-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #00ffff;
}

.author-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.author-details h3 {
  font-size: 1.3rem;
  color: #00ffff;
  margin: 0 0 0.5rem 0;
}

.author-details p {
  color: rgba(255, 255, 255, 0.8);
  margin: 0 0 1rem 0;
}

.author-social {
  display: flex;
  gap: 1rem;
}

.social-link {
  color: #00ffff;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s ease;
}

.social-link:hover {
  color: #ff00ff;
}

.blog-meta-details {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: rgba(255, 255, 255, 0.8);
}

.meta-icon {
  color: #00ffff;
}

/* Content Section */
.blog-content-section {
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  padding: 4rem 0;
}

.content-layout {
  display: grid;
  grid-template-columns: 250px 1fr 300px;
  gap: 3rem;
}

/* Table of Contents */
.table-of-contents {
  position: sticky;
  top: 2rem;
  height: fit-content;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 1.5rem;
  backdrop-filter: blur(10px);
}

.table-of-contents h3 {
  margin: 0 0 1rem 0;
  color: #00ffff;
  font-size: 1.1rem;
}

.table-of-contents ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.table-of-contents li {
  margin-bottom: 0.5rem;
}

.table-of-contents a {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s ease;
  display: block;
  padding: 0.3rem 0;
}

.table-of-contents a:hover {
  color: #00ffff;
}

.level-1 {
  padding-left: 0;
}

.level-2 {
  padding-left: 1rem;
}

.level-3 {
  padding-left: 2rem;
}

/* Main Content */
.blog-content {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 2rem;
  backdrop-filter: blur(10px);
}

.blog-tags {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.tag {
  padding: 0.3rem 1rem;
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 20px;
  font-size: 0.9rem;
  color: #00ffff;
}

.blog-body {
  font-size: 1.1rem;
  line-height: 1.8;
  color: rgba(255, 255, 255, 0.9);
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.blog-body h1,
.blog-body h2,
.blog-body h3,
.blog-body h4,
.blog-body h5,
.blog-body h6 {
  color: #00ffff;
  margin: 2rem 0 1rem 0;
  scroll-margin-top: 2rem;
}

.blog-body h1 { font-size: 2rem; }
.blog-body h2 { font-size: 1.8rem; }
.blog-body h3 { font-size: 1.6rem; }
.blog-body h4 { font-size: 1.4rem; }

.blog-body p {
  margin-bottom: 1.5rem;
}

.blog-body code {
  background: rgba(0, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 5px;
  padding: 0.2rem 0.5rem;
  font-family: 'Courier New', monospace;
  color: #00ffff;
}

.blog-body pre {
  background: rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 10px;
  padding: 1.5rem;
  overflow-x: auto;
  margin: 1.5rem 0;
}

.blog-body blockquote {
  border-left: 4px solid #00ffff;
  background: rgba(0, 255, 255, 0.05);
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  font-style: italic;
  color: rgba(255, 255, 255, 0.8);
}

/* Additional blog content styles for full responsiveness */
.blog-body * {
  max-width: 100%;
  box-sizing: border-box;
}

.blog-body table {
  border-collapse: collapse;
  width: 100%;
  margin: 1.5rem 0;
  background: rgba(0, 0, 0, 0.3);
  border-radius: 10px;
  overflow: hidden;
}

.blog-body th,
.blog-body td {
  padding: 0.8rem 1rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
  text-align: left;
}

.blog-body th {
  background: rgba(0, 255, 255, 0.1);
  color: #00ffff;
  font-weight: 600;
}

.blog-body iframe {
  width: 100%;
  height: auto;
  aspect-ratio: 16/9;
  border: none;
  border-radius: 10px;
  margin: 1.5rem 0;
}

.blog-body video {
  width: 100%;
  height: auto;
  border-radius: 10px;
  margin: 1.5rem 0;
}

.blog-body hr {
  border: none;
  height: 2px;
  background: linear-gradient(90deg, transparent, #00ffff, transparent);
  margin: 2rem 0;
}

.blog-body a {
  color: #00ffff;
  text-decoration: none;
  transition: all 0.3s ease;
  border-bottom: 1px solid transparent;
}

.blog-body a:hover {
  border-bottom-color: #00ffff;
  text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}

.blog-body strong {
  color: #00ffff;
  font-weight: 600;
}

.blog-body em {
  color: rgba(255, 255, 255, 0.9);
  font-style: italic;
}

.blog-stats-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.stats-left,
.stats-right {
  display: flex;
  gap: 1rem;
}

.stat-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.8rem 1.5rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 25px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.stat-button:hover {
  background: rgba(0, 255, 255, 0.1);
  border-color: #00ffff;
  transform: scale(1.05);
}

/* Sidebar */
.blog-sidebar {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.sidebar-card {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 1.5rem;
  backdrop-filter: blur(10px);
}

.sidebar-card h3 {
  margin: 0 0 1rem 0;
  color: #00ffff;
  font-size: 1.1rem;
}

.sidebar-card p {
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 1.5rem;
}

.newsletter-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.newsletter-form input {
  padding: 0.8rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.05);
  color: white;
}

.newsletter-form button {
  padding: 0.8rem;
  background: linear-gradient(45deg, #00ffff, #0099cc);
  border: none;
  border-radius: 10px;
  color: #000;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.newsletter-form button:hover {
  transform: scale(1.05);
}

.related-posts {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.related-post {
  display: flex;
  gap: 1rem;
  text-decoration: none;
  color: white;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 10px;
  transition: all 0.3s ease;
}

.related-post:hover {
  background: rgba(0, 255, 255, 0.1);
  transform: scale(1.02);
}

.related-post-image {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  overflow: hidden;
  flex-shrink: 0;
}

.related-post-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.related-post-content h4 {
  margin: 0 0 0.5rem 0;
  font-size: 1rem;
  color: #00ffff;
}

.related-post-content p {
  margin: 0 0 0.5rem 0;
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.7);
}

.related-post-meta {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.5);
}

/* Comments Section */
.comments-section {
  background: linear-gradient(135deg,
    rgba(0, 8, 20, 0.95) 0%,
    rgba(26, 0, 51, 0.9) 50%,
    rgba(0, 8, 20, 0.95) 100%);
  padding: 4rem 0;
}

.comments-section h2 {
  text-align: center;
  font-size: 2.5rem;
  color: #00ffff;
  margin-bottom: 3rem;
}

.comment-form {
  max-width: 600px;
  margin: 0 auto;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  padding: 2rem;
  backdrop-filter: blur(10px);
}

.comment-form h3 {
  margin: 0 0 2rem 0;
  color: #00ffff;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.05);
  color: white;
  font-size: 1rem;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #00ffff;
  box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
}

.submit-comment-btn {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(45deg, #00ffff, #0099cc);
  border: none;
  border-radius: 10px;
  color: #000;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit-comment-btn:hover {
  transform: scale(1.02);
  box-shadow: 0 5px 20px rgba(0, 255, 255, 0.4);
}

/* Mobile Responsiveness */
/* Tablet Styles - Enhanced */
@media (max-width: 1024px) {
  /* Override AppLayout for tablet */
  :global(.main-content) {
    width: 100% !important;
    overflow: hidden !important;
  }

  .container {
    padding: 0 1.5rem !important;
    max-width: 100% !important;
    width: 100% !important;
  }

  /* Ensure responsive content layout */
  .content-layout {
    grid-template-columns: 1fr !important;
    gap: 2rem !important;
    width: 100% !important;
  }

  .blog-hero {
    min-height: 60vh;
  }

  .blog-title {
    font-size: 2.2rem;
    line-height: 1.3;
  }

  .content-layout {
    grid-template-columns: 1fr;
    gap: 2.5rem;
  }

  .table-of-contents {
    order: -1;
    margin-bottom: 2rem;
    border-radius: 15px;
    overflow: hidden;
  }

  .table-of-contents h3 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
  }

  .blog-sidebar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }
}

/* Mobile Styles - Completely Enhanced */
@media (max-width: 768px) {
  /* Override AppLayout container styles for mobile */
  :global(.app-layout) {
    overflow: hidden !important;
  }

  :global(.main-content) {
    width: 100% !important;
    overflow: hidden !important;
    padding: 0 !important;
  }

  .container {
    padding: 0 1rem !important;
    max-width: 100% !important;
    width: 100% !important;
  }

  /* Blog page mobile specific overrides */
  .blog-hero {
    padding: 2rem 1rem !important;
  }

  .blog-content-section {
    padding: 1rem 0 !important;
  }

  .blog-hero {
    padding: 2rem 1rem;
    min-height: 50vh;
    text-align: center;
  }

  .blog-breadcrumb {
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
  }

  .blog-meta-header {
    justify-content: center;
    margin-bottom: 1.5rem;
    gap: 0.75rem;
  }

  .category-badge {
    padding: 0.4rem 1rem;
    font-size: 0.8rem;
  }

  .featured-badge {
    font-size: 0.8rem;
  }

  .blog-title {
    font-size: 1.8rem;
    line-height: 1.4;
    margin-bottom: 1rem;
  }

  .blog-excerpt {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
  }

  .blog-author-info {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .author-avatar {
    margin: 0 auto;
  }

  .author-avatar img {
    width: 60px;
    height: 60px;
  }

  .author-details h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
  }

  .author-details p {
    font-size: 0.9rem;
    margin-bottom: 1rem;
  }

  .author-social {
    display: flex;
    justify-content: center;
    gap: 1rem;
  }

  .social-link {
    font-size: 0.85rem;
    padding: 0.3rem 0.8rem;
  }

  .blog-meta-details {
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
  }

  .meta-item {
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    backdrop-filter: blur(10px);
  }

  .blog-content-section {
    padding: 2rem 0;
  }

  .blog-content {
    padding: 1.5rem;
    border-radius: 20px;
    font-size: 0.95rem;
    line-height: 1.7;
  }

  .blog-tags {
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .tag {
    font-size: 0.8rem;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
  }

  .table-of-contents {
    background: linear-gradient(135deg, #1a2942 0%, #2a1b4d 100%);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 255, 255, 0.2);
  }

  .table-of-contents h3 {
    font-size: 1rem;
    margin-bottom: 1rem;
    color: #00ffff;
  }

  .table-of-contents ul {
    gap: 0.5rem;
  }

  .table-of-contents a {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .table-of-contents a:hover {
    background: rgba(0, 255, 255, 0.2);
    transform: translateX(5px);
  }

  .blog-stats-bar {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
    border-radius: 15px;
  }

  .stat-item {
    font-size: 0.85rem;
  }

  .blog-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .sidebar-card {
    padding: 1.5rem;
    border-radius: 15px;
  }

  .sidebar-card h3 {
    font-size: 1rem;
    margin-bottom: 1rem;
  }

  .related-posts {
    gap: 1rem;
  }

  .related-post {
    padding: 1rem;
    border-radius: 12px;
    transition: transform 0.3s ease;
  }

  .related-post:hover {
    transform: translateY(-2px);
  }

  .related-post h4 {
    font-size: 0.9rem;
    line-height: 1.4;
  }

  .related-post p {
    font-size: 0.8rem;
  }

  .comment-form {
    padding: 1.5rem;
    border-radius: 15px;
  }

  .comment-form h3 {
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
  }

  .form-group label {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
  }

  .form-group input,
  .form-group textarea {
    padding: 0.8rem;
    border-radius: 10px;
    font-size: 0.9rem;
  }

  .submit-btn {
    padding: 0.8rem 2rem;
    font-size: 0.9rem;
  }
}

/* Small Mobile Styles - Ultra Enhanced */
@media (max-width: 480px) {
  /* Override AppLayout for small mobile */
  :global(.main-content) {
    width: 100% !important;
    padding: 0 !important;
  }

  .container {
    padding: 0 0.75rem !important;
    max-width: 100% !important;
    width: 100% !important;
  }

  .blog-hero {
    padding: 1.5rem 0.75rem !important;
    min-height: 45vh;
  }

  .blog-title {
    font-size: 1.6rem;
    line-height: 1.3;
  }

  .blog-excerpt {
    font-size: 0.95rem;
  }

  .blog-content {
    padding: 1rem;
    font-size: 0.9rem;
    line-height: 1.6;
  }

  .container {
    padding: 0 0.75rem;
  }

  .blog-meta-header {
    gap: 0.5rem;
  }

  .category-badge {
    padding: 0.3rem 0.8rem;
    font-size: 0.75rem;
  }

  .meta-item {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
  }

  .blog-author-avatar img {
    width: 50px;
    height: 50px;
  }

  .table-of-contents {
    padding: 1rem;
  }

  .table-of-contents h3 {
    font-size: 0.9rem;
  }

  .table-of-contents a {
    font-size: 0.8rem;
    padding: 0.3rem 0.6rem;
  }

  .sidebar-card {
    padding: 1rem;
  }

  .sidebar-card h3 {
    font-size: 0.9rem;
  }

  .related-post {
    padding: 0.8rem;
  }

  .related-post h4 {
    font-size: 0.85rem;
  }

  .related-post p {
    font-size: 0.75rem;
  }

  .comment-form {
    padding: 1rem;
  }

  .comment-form h3 {
    font-size: 1rem;
  }

  .form-group label {
    font-size: 0.85rem;
  }

  .form-group input,
  .form-group textarea {
    padding: 0.6rem;
    font-size: 0.85rem;
  }

  .submit-btn {
    padding: 0.6rem 1.5rem;
    font-size: 0.85rem;
  }

  .blog-tags {
    margin-bottom: 1rem;
  }

  .tag {
    font-size: 0.75rem;
    padding: 0.25rem 0.6rem;
  }

  .blog-stats-bar {
    padding: 0.8rem;
  }

  .stat-item {
    font-size: 0.8rem;
  }
}

/* Additional Responsive Overrides */
@media (max-width: 768px) {
  /* Force full width blog content */
  .blog-content {
    width: 100% !important;
    max-width: 100% !important;
    padding: 1rem !important;
    margin: 0 !important;
    box-sizing: border-box !important;
  }

  .blog-body {
    width: 100% !important;
    max-width: 100% !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
  }

  .blog-body * {
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* Force responsive images and media */
  .blog-body img,
  .blog-body video,
  .blog-body iframe {
    max-width: 100% !important;
    height: auto !important;
    display: block !important;
    margin: 1rem auto !important;
  }

  /* Ensure tables scroll horizontally */
  .blog-body table {
    display: block !important;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
    width: 100% !important;
  }
}

/* Mobile Touch Enhancements */
@media (max-width: 768px) {
  /* Better touch targets */
  .breadcrumb-link,
  .social-link,
  .table-of-contents a,
  .tag {
    min-height: 44px;
    display: inline-flex;
    align-items: center;
  }

  /* Better tap spacing */
  .blog-tags {
    gap: 0.75rem;
  }

  .tag {
    margin: 0.25rem;
  }

  /* Mobile-friendly hover states */
  .related-post:active,
  .table-of-contents a:active {
    transform: scale(0.98);
    transition: transform 0.1s ease;
  }

  /* Better mobile scrolling for table of contents */
  .table-of-contents {
    max-height: 300px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* Mobile-optimized images */
  .blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 1rem 0;
  }

  /* Better mobile code blocks */
  .blog-content pre {
    border-radius: 10px;
    padding: 1rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    font-size: 0.8rem;
  }

  /* Mobile-friendly blockquotes */
  .blog-content blockquote {
    margin: 1.5rem 0;
    padding: 1rem;
    border-left: 4px solid #00ffff;
    background: rgba(0, 255, 255, 0.1);
    border-radius: 0 10px 10px 0;
  }

  /* Better mobile list spacing */
  .blog-content ul,
  .blog-content ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
  }

  .blog-content li {
    margin: 0.5rem 0;
    line-height: 1.6;
  }

  /* Enhanced responsive styles for blog-body content */
  .blog-body {
    font-size: 0.95rem;
    line-height: 1.7;
  }

  .blog-body h1,
  .blog-body h2,
  .blog-body h3,
  .blog-body h4,
  .blog-body h5,
  .blog-body h6 {
    margin: 1.5rem 0 1rem 0;
    line-height: 1.3;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .blog-body h1 { font-size: 1.6rem; }
  .blog-body h2 { font-size: 1.4rem; }
  .blog-body h3 { font-size: 1.2rem; }
  .blog-body h4 { font-size: 1.1rem; }

  .blog-body p {
    margin-bottom: 1rem;
    line-height: 1.7;
  }

  .blog-body ul,
  .blog-body ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
  }

  .blog-body li {
    margin: 0.5rem 0;
    line-height: 1.6;
  }

  .blog-body img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 1rem 0;
    display: block;
  }

  .blog-body pre {
    border-radius: 10px;
    padding: 1rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    font-size: 0.8rem;
    white-space: pre-wrap;
    word-wrap: break-word;
  }

  .blog-body code {
    font-size: 0.85rem;
    padding: 0.2rem 0.4rem;
    word-wrap: break-word;
  }

  .blog-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
    font-size: 0.9rem;
    display: block;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .blog-body th,
  .blog-body td {
    padding: 0.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    text-align: left;
    min-width: 100px;
  }

  .blog-body blockquote {
    margin: 1rem 0;
    padding: 0.8rem 1rem;
    border-radius: 0 10px 10px 0;
    font-size: 0.95rem;
  }

  .blog-body iframe {
    max-width: 100%;
    height: auto;
    aspect-ratio: 16/9;
    border-radius: 10px;
    margin: 1rem 0;
  }

  .blog-body div {
    max-width: 100%;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .blog-body span {
    word-wrap: break-word;
    overflow-wrap: break-word;
  }
}

/* Small Mobile Responsive Overrides */
@media (max-width: 480px) {
  /* Force full width on small mobile */
  .blog-content {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0.75rem !important;
    margin: 0 !important;
    box-sizing: border-box !important;
  }

  .blog-body {
    width: 100% !important;
    max-width: 100% !important;
    font-size: 0.9rem !important;
    line-height: 1.6 !important;
  }

  .content-layout {
    grid-template-columns: 1fr !important;
    gap: 1.5rem !important;
    width: 100% !important;
  }

  .blog-sidebar {
    display: flex !important;
    flex-direction: column !important;
    gap: 1rem !important;
    width: 100% !important;
  }

  .sidebar-card {
    width: 100% !important;
    padding: 1rem !important;
    box-sizing: border-box !important;
  }
}

/* Small Mobile Touch Optimizations */
@media (max-width: 480px) {
  /* Larger touch targets on small screens */
  .meta-item,
  .category-badge {
    min-height: 40px;
    display: inline-flex;
    align-items: center;
  }

  /* Better mobile typography */
  .blog-content h1,
  .blog-content h2,
  .blog-content h3 {
    margin: 1.5rem 0 1rem 0;
    line-height: 1.3;
  }

  .blog-content h2 {
    font-size: 1.3rem;
  }

  .blog-content h3 {
    font-size: 1.1rem;
  }

  .blog-content p {
    margin: 1rem 0;
  }

  /* Mobile-friendly horizontal scroll */
  .blog-content table {
    display: block;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 10px;
    margin: 1rem 0;
  }

  /* Enhanced responsive blog-body styles for small mobile */
  .blog-body {
    font-size: 0.9rem;
    line-height: 1.6;
  }

  .blog-body h1,
  .blog-body h2,
  .blog-body h3,
  .blog-body h4,
  .blog-body h5,
  .blog-body h6 {
    margin: 1.2rem 0 0.8rem 0;
    line-height: 1.2;
  }

  .blog-body h1 { font-size: 1.4rem; }
  .blog-body h2 { font-size: 1.2rem; }
  .blog-body h3 { font-size: 1.1rem; }
  .blog-body h4 { font-size: 1rem; }

  .blog-body p {
    margin-bottom: 0.8rem;
    line-height: 1.6;
  }

  .blog-body ul,
  .blog-body ol {
    margin: 0.8rem 0;
    padding-left: 1.2rem;
  }

  .blog-body li {
    margin: 0.4rem 0;
    line-height: 1.5;
  }

  .blog-body img {
    margin: 0.8rem 0;
    border-radius: 8px;
  }

  .blog-body pre {
    padding: 0.8rem;
    font-size: 0.75rem;
    margin: 1rem 0;
    border-radius: 8px;
  }

  .blog-body code {
    font-size: 0.8rem;
    padding: 0.15rem 0.3rem;
  }

  .blog-body table {
    font-size: 0.85rem;
    margin: 0.8rem 0;
  }

  .blog-body th,
  .blog-body td {
    padding: 0.4rem;
    min-width: 80px;
  }

  .blog-body blockquote {
    margin: 0.8rem 0;
    padding: 0.6rem 0.8rem;
    font-size: 0.9rem;
    border-radius: 0 8px 8px 0;
  }

  .blog-body iframe {
    border-radius: 8px;
    margin: 0.8rem 0;
  }

  /* Better mobile form inputs */
  .form-group input,
  .form-group textarea {
    font-size: 16px; /* Prevents zoom on iOS */
  }

  /* Mobile-optimized loading states */
  .loading-spinner {
    width: 40px;
    height: 40px;
    border-width: 3px;
  }
}

/* Custom scrollbar styling matching Contact.vue */
:deep(::-webkit-scrollbar) {
  width: 8px;
}

:deep(::-webkit-scrollbar-track) {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 4px;
}

:deep(::-webkit-scrollbar-thumb) {
  background: rgba(0, 0, 0, 0.3);
  border-radius: 4px;
  transition: all 0.3s ease;
}

:deep(::-webkit-scrollbar-thumb:hover) {
  background: rgba(0, 0, 0, 0.5);
}

/* Firefox scrollbar */
:global(html) {
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 0, 0, 0.3) rgba(0, 0, 0, 0.1);
}
</style>