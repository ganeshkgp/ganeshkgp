<template>
  <AppLayout>
    <div v-if="loading" class="min-h-screen flex items-center justify-center">
      <div class="relative">
        <div class="w-20 h-20 border-4 border-cyan-500/20 rounded-full animate-spin border-t-cyan-500"></div>
        <div class="absolute inset-0 w-20 h-20 border-4 border-purple-500/20 rounded-full animate-spin border-b-purple-500" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
      </div>
      <p class="text-white/70 mt-6 text-lg ml-4">Loading cosmic content...</p>
    </div>

    <div v-else-if="error" class="min-h-screen flex items-center justify-center">
      <div class="text-center">
        <div class="text-6xl mb-4">🌌</div>
        <h2 class="text-3xl font-bold text-cyan-400 mb-4">Blog Not Found</h2>
        <p class="text-white/70 mb-6">{{ error }}</p>
        <router-link to="/blogs" class="inline-block bg-linear-to-r from-cyan-500 to-purple-500 text-black px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300">
          ← Back to Blogs
        </router-link>
      </div>
    </div>

    <article v-else class="min-h-screen">
      <!-- Hero Section -->
      <section class="relative bg-linear-to-br from-purple-900/20 via-black/50 to-cyan-900/20 backdrop-blur-lg overflow-hidden">
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute top-10 left-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl animate-pulse"></div>
          <div class="absolute top-20 right-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
          <div class="absolute bottom-10 left-1/3 w-72 h-72 bg-pink-500/10 rounded-full blur-3xl animate-pulse delay-2000"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 px-6 pt-24 pb-16 lg:pt-32 lg:pb-24">
          <div class="max-w-4xl mx-auto text-center">
            <!-- Category Badge -->
            <div class="mb-6 flex justify-center">
              <span class="px-4 py-2 bg-linear-to-r from-cyan-500/20 to-purple-500/20 border border-cyan-500/30 rounded-full backdrop-blur-sm text-cyan-400 text-sm font-semibold">
                {{ formatCategory(blog.category) }}
              </span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
              <span class="bg-linear-to-r from-cyan-400 via-purple-400 to-pink-400 bg-clip-text text-transparent block">
                {{ blog.title }}
              </span>
            </h1>

            <!-- Excerpt -->
            <p class="text-xl text-white/80 mb-8 max-w-3xl mx-auto leading-relaxed">
              {{ blog.excerpt }}
            </p>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-white/60 text-sm">
              <div class="flex items-center gap-2">
                <span>👨‍🚀</span>
                <span>{{ blog.author.name }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span>📅</span>
                <span>{{ blog.published_at }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span>⏱️</span>
                <span>{{ blog.reading_time }} min read</span>
              </div>
              <div class="flex items-center gap-2">
                <span>👁️</span>
                <span>{{ formatNumber(blog.stats.views) }} views</span>
              </div>
            </div>

            <!-- Tags -->
            <div v-if="blog.tags && blog.tags.length > 0" class="flex flex-wrap items-center justify-center gap-2 mt-6">
              <span v-for="tag in blog.tags" :key="tag" class="px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded-full text-cyan-400 text-xs font-medium">
                #{{ tag }}
              </span>
            </div>
          </div>
        </div>
      </section>

      <!-- Featured Image -->
      <section v-if="blog.featured_image" class="relative">
        <div class="max-w-6xl mx-auto px-6 -mt-12 relative z-20">
          <div class="rounded-2xl overflow-hidden shadow-2xl border border-cyan-500/20">
            <img
              :src="blog.featured_image"
              :alt="blog.title"
              class="w-full h-[400px] object-cover"
            />
            <div class="absolute inset-0 bg-linear-to-t from-black/50 to-transparent"></div>
          </div>
        </div>
      </section>

      <!-- Table of Contents -->
      <section v-if="blog.table_of_contents && blog.table_of_contents.length > 0" class="py-12">
        <div class="max-w-4xl mx-auto px-6">
          <div class="bg-black/50 border border-cyan-500/20 rounded-2xl p-6 backdrop-blur-lg">
            <h3 class="text-xl font-semibold mb-4 bg-linear-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
              🗺️ Navigation
            </h3>
            <nav class="space-y-2">
              <a
                v-for="item in blog.table_of_contents"
                :key="item.slug"
                :href="`#${item.slug}`"
                :class="[
                  'block py-2 px-4 rounded-lg text-white/70 hover:text-cyan-400 hover:bg-cyan-500/10 transition-all duration-300',
                  `pl-${Math.min(item.level * 4, 16)}`
                ]"
              >
                {{ item.title }}
              </a>
            </nav>
          </div>
        </div>
      </section>

      <!-- Content -->
      <section class="py-12">
        <div class="max-w-4xl mx-auto px-6">
          <div class="prose prose-lg prose-invert max-w-none">
            <div v-html="blog.content" class="blog-content text-white/90 leading-relaxed"></div>
          </div>

          <!-- Blog Stats & Actions -->
          <div class="mt-12 pt-8 border-t border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-4">
              <div class="flex items-center gap-6">
                <button
                  @click="toggleLike"
                  :disabled="likeLoading"
                  class="flex items-center gap-2 px-4 py-2 bg-linear-to-r from-pink-500/20 to-red-500/20 border border-pink-500/30 rounded-lg hover:from-pink-500/30 hover:to-red-500/30 transition-all duration-300 disabled:opacity-50"
                >
                  <span :class="isLiked ? 'text-red-500' : 'text-white/70'">
                    {{ isLiked ? '❤️' : '🤍' }}
                  </span>
                  <span class="text-white/80">{{ formatNumber(blog.stats.likes) }}</span>
                </button>

                <div class="flex items-center gap-2 text-white/60">
                  <span>💬</span>
                  <span>{{ formatNumber(blog.stats.comments) }} comments</span>
                </div>
              </div>

              <div class="flex items-center gap-4">
                <button
                  @click="shareBlog"
                  class="p-2 bg-cyan-500/20 border border-cyan-500/30 rounded-lg hover:bg-cyan-500/30 transition-all duration-300"
                  title="Share"
                >
                  📤
                </button>
                <button
                  @click="copyLink"
                  class="p-2 bg-purple-500/20 border border-purple-500/30 rounded-lg hover:bg-purple-500/30 transition-all duration-300"
                  title="Copy Link"
                >
                  🔗
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Author Section -->
      <section class="py-12">
        <div class="max-w-4xl mx-auto px-6">
          <div class="bg-linear-to-r from-cyan-500/10 to-purple-500/10 border border-cyan-500/30 rounded-2xl p-8">
            <div class="flex items-center gap-6">
              <div class="w-20 h-20 bg-linear-to-r from-cyan-400 to-purple-500 rounded-full flex items-center justify-center text-black text-2xl font-bold">
                {{ blog.author.name.charAt(0) }}
              </div>
              <div class="flex-1">
                <h3 class="text-xl font-semibold text-cyan-400 mb-2">{{ blog.author.name }}</h3>
                <p class="text-white/70 mb-4">{{ blog.author.bio }}</p>
                <div class="flex gap-3">
                  <a
                    v-for="(url, platform) in blog.author.social"
                    :key="platform"
                    :href="url"
                    target="_blank"
                    class="text-white/60 hover:text-cyan-400 transition-colors duration-300"
                  >
                    {{ platform === 'github' ? '🐙' : platform === 'linkedin' ? '💼' : '🐦' }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Related Posts -->
      <section v-if="blog.related_posts && blog.related_posts.length > 0" class="py-12">
        <div class="max-w-6xl mx-auto px-6">
          <h3 class="text-2xl font-semibold mb-8 text-center bg-linear-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
            🚀 Related Cosmic Articles
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <article
              v-for="related in blog.related_posts"
              :key="related.id"
              @click="navigateToBlog(related.slug)"
              class="bg-black/50 border border-cyan-500/20 rounded-xl p-6 cursor-pointer hover:border-cyan-500/40 hover:shadow-lg hover:shadow-cyan-500/10 transition-all duration-300 backdrop-blur-lg"
            >
              <h4 class="text-lg font-semibold text-cyan-400 mb-2 line-clamp-2">{{ related.title }}</h4>
              <p class="text-white/60 text-sm mb-4 line-clamp-3">{{ related.excerpt }}</p>
              <div class="flex items-center justify-between text-white/50 text-xs">
                <span>⏱️ {{ related.reading_time }} min</span>
                <span>{{ related.published_at }}</span>
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- Comments Section -->
      <section class="py-12">
        <div class="max-w-4xl mx-auto px-6">
          <h3 class="text-2xl font-semibold mb-8 bg-linear-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
            💬 Join the Discussion
          </h3>

          <!-- Comment Form -->
          <div v-if="isAuthenticated" class="bg-black/50 border border-cyan-500/20 rounded-2xl p-6 mb-8 backdrop-blur-lg">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-linear-to-r from-cyan-400 to-purple-500 rounded-full flex items-center justify-center text-black text-sm font-bold">
                {{ userInitials }}
              </div>
              <div>
                <h4 class="text-cyan-400 font-medium">{{ currentUser.name }}</h4>
                <p class="text-white/50 text-sm">Share your thoughts on this cosmic article</p>
              </div>
            </div>
            <form @submit.prevent="submitComment" class="space-y-4">
              <div>
                <label class="block text-white/70 text-sm mb-2">Your Comment</label>
                <textarea
                  v-model="commentForm.content"
                  required
                  rows="4"
                  class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/40 focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none transition-all duration-300 resize-none"
                  placeholder="Share your thoughts..."
                ></textarea>
              </div>
              <button
                type="submit"
                :disabled="commentLoading || !commentForm.content.trim()"
                class="px-6 py-3 bg-linear-to-r from-cyan-500 to-purple-500 text-black rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ commentLoading ? 'Posting...' : '🚀 Launch Comment' }}
              </button>
            </form>
          </div>

          <!-- Login to Comment -->
          <div v-else class="bg-black/50 border border-cyan-500/20 rounded-2xl p-8 mb-8 backdrop-blur-lg text-center">
            <div class="mb-6">
              <div class="w-16 h-16 bg-linear-to-r from-cyan-500/20 to-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl">🔒</span>
              </div>
              <h4 class="text-xl font-semibold text-cyan-400 mb-2">Join the Discussion</h4>
              <p class="text-white/70">Sign in to share your thoughts and engage with the community</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <router-link
                :to="{ path: '/login', query: { redirect: $route.fullPath } }"
                class="px-6 py-3 bg-linear-to-r from-cyan-500 to-purple-500 text-black rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300"
              >
                Sign In
              </router-link>
              <router-link
                :to="{ path: '/register', query: { redirect: $route.fullPath } }"
                class="px-6 py-3 bg-white/10 border border-white/20 rounded-lg text-white/70 hover:bg-white/20 transition-all duration-300"
              >
                Create Account
              </router-link>
            </div>
          </div>

          <!-- Comments List -->
          <div v-if="commentsLoading && pagination.current_page === 1" class="text-center py-8">
            <div class="w-12 h-12 border-4 border-cyan-500/20 rounded-full animate-spin border-t-cyan-500 mx-auto"></div>
            <p class="text-white/70 mt-4">Loading comments...</p>
          </div>

          <div v-else-if="comments.length === 0 && !commentsLoading" class="text-center py-8">
            <div class="text-4xl mb-4">💭</div>
            <p class="text-white/70">No comments yet. Be the first to share your thoughts!</p>
          </div>

          <div v-else class="space-y-6">
            <div
              v-for="comment in comments"
              :key="comment.id"
              class="bg-black/50 border border-cyan-500/20 rounded-xl p-6 backdrop-blur-lg"
            >
              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-linear-to-r from-cyan-400 to-purple-500 rounded-full flex items-center justify-center text-black font-bold">
                    {{ comment.name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <h4 class="font-semibold text-cyan-400">{{ comment.name }}</h4>
                    <p class="text-white/50 text-xs">{{ comment.created_at }}</p>
                  </div>
                </div>
                <button
                  @click="likeComment(comment.id)"
                  class="flex items-center gap-1 text-white/50 hover:text-red-400 transition-colors duration-300"
                >
                  <span>{{ comment.isLiked ? '❤️' : '🤍' }}</span>
                  <span class="text-xs">{{ comment.likes_count }}</span>
                </button>
              </div>
              <p class="text-white/80 leading-relaxed">{{ comment.content }}</p>

              <!-- Reply Button -->
              <button
                @click="toggleReplyForm(comment.id)"
                class="mt-4 text-cyan-400 hover:text-cyan-300 text-sm transition-colors duration-300"
              >
                💬 Reply
              </button>

              <!-- Reply Form -->
              <div v-if="replyFormVisible === comment.id" class="mt-4 space-y-3">
                <div class="flex items-center gap-2 mb-2">
                  <div class="w-8 h-8 bg-linear-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-black text-sm font-bold">
                    {{ authStore.userInitials }}
                  </div>
                  <span class="text-cyan-400 text-sm">Replying as {{ authStore.displayName }}</span>
                </div>
                <textarea
                  v-model="replyForm.content"
                  required
                  rows="3"
                  class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/40 focus:border-cyan-500/30 focus:bg-white/10 focus:outline-none text-sm resize-none"
                  placeholder="Write your reply..."
                ></textarea>
                <div class="flex gap-2">
                  <button
                    @click="submitReply(comment.id)"
                    :disabled="replyLoading"
                    class="px-4 py-2 bg-linear-to-r from-cyan-500 to-purple-500 text-black rounded-lg font-semibold text-sm hover:shadow-lg hover:shadow-cyan-500/25 transition-all duration-300 disabled:opacity-50"
                  >
                    {{ replyLoading ? 'Posting...' : 'Reply' }}
                  </button>
                  <button
                    @click="toggleReplyForm(null)"
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white/70 hover:bg-white/20 transition-all duration-300 text-sm"
                  >
                    Cancel
                  </button>
                </div>
              </div>

              <!-- Replies -->
              <div v-if="comment.replies && comment.replies.length > 0" class="mt-6 space-y-4 pl-8 border-l-2 border-cyan-500/20">
                <div
                  v-for="reply in comment.replies"
                  :key="reply.id"
                  class="bg-black/30 rounded-lg p-4"
                >
                  <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                      <div class="w-8 h-8 bg-linear-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-black text-sm font-bold">
                        {{ reply.name.charAt(0).toUpperCase() }}
                      </div>
                      <div>
                        <h5 class="font-medium text-purple-400 text-sm">{{ reply.name }}</h5>
                        <p class="text-white/50 text-xs">{{ reply.created_at }}</p>
                      </div>
                    </div>
                  </div>
                  <p class="text-white/70 text-sm leading-relaxed">{{ reply.content }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Load More Button -->
          <div v-if="pagination.has_more" class="text-center mt-8">
            <button
              @click="loadMoreComments"
              :disabled="loadMoreLoading"
              class="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-cyan-500/20 to-purple-500/20 border border-cyan-500/30 rounded-lg hover:from-cyan-500/30 hover:to-purple-500/30 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loadMoreLoading" class="w-4 h-4 border-2 border-cyan-400/20 border-t-cyan-400 rounded-full animate-spin"></span>
              <span v-else>Load More Comments</span>
            </button>
          </div>

          <!-- Comments Info -->
          <div v-if="comments.length > 0" class="text-center mt-6 text-white/50 text-sm">
            <span v-if="pagination.total > pagination.per_page">
              Showing {{ pagination.from }}-{{ pagination.to }} of {{ pagination.total }} comments
            </span>
            <span v-else>
              {{ pagination.total }} {{ pagination.total === 1 ? 'comment' : 'comments' }}
            </span>
          </div>
        </div>
      </section>

      <!-- Back to Blogs -->
      <section class="py-12">
        <div class="max-w-4xl mx-auto px-6 text-center">
          <router-link to="/blogs" class="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-cyan-500/20 to-purple-500/20 border border-cyan-500/30 rounded-lg hover:from-cyan-500/30 hover:to-purple-500/30 transition-all duration-300">
            <span>←</span>
            <span>Back to Blog Universe</span>
          </router-link>
        </div>
      </section>
    </article>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

// Store original overflow settings
let originalAppStyles = {}
let originalBodyStyles = {}
let originalHtmlStyles = {}

// Reactive data
const loading = ref(true)
const error = ref('')
const blog = ref(null)
const comments = ref([])
const commentsLoading = ref(false)
const isLiked = ref(false)
const likeLoading = ref(false)
const commentLoading = ref(false)

// Pagination data
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 5,
  total: 0,
  has_more: false,
  from: null,
  to: null
})
const loadMoreLoading = ref(false)

// Reply form
const replyFormVisible = ref(null)
const replyForm = ref({
  content: ''
})
const replyLoading = ref(false)

// Computed properties for authentication
const isAuthenticated = computed(() => authStore.isAuthenticated)
const currentUser = computed(() => authStore.user)

// Pre-fill comment form with user data if authenticated
const commentForm = ref({
  name: currentUser.value?.name || '',
  email: currentUser.value?.email || '',
  content: ''
})

// Fetch blog details
const fetchBlogDetails = async () => {
  try {
    const slug = route.params.slug
    const response = await fetch(`/api/v1/home/blogs/${slug}`)

    if (!response.ok) {
      throw new Error('Blog not found')
    }

    const data = await response.json()
    blog.value = data

    // Fetch comments after blog is loaded
    await fetchComments()

  } catch (err) {
    error.value = err.message || 'Failed to load blog'
  } finally {
    loading.value = false
  }
}

// Fetch comments
const fetchComments = async (page = 1, append = false) => {
  if (!blog.value) return

  try {
    if (!append) {
      commentsLoading.value = true
    } else {
      loadMoreLoading.value = true
    }

    const response = await fetch(`/api/v1/blogs/${blog.value.slug}/comments?page=${page}&per_page=${pagination.value.per_page}`)

    if (response.ok) {
      const data = await response.json()

      if (append) {
        // Append new comments to existing ones
        comments.value = [...comments.value, ...data.comments.map(comment => ({
          ...comment,
          isLiked: false
        }))]
      } else {
        // Replace all comments
        comments.value = data.comments.map(comment => ({
          ...comment,
          isLiked: false
        }))
      }

      // Update pagination data
      pagination.value = data.pagination
    }
  } catch (err) {
    console.error('Error fetching comments:', err)
  } finally {
    commentsLoading.value = false
    loadMoreLoading.value = false
  }
}

// Load more comments
const loadMoreComments = async () => {
  if (pagination.value.has_more && !loadMoreLoading.value) {
    await fetchComments(pagination.value.current_page + 1, true)
  }
}

// Toggle like
const toggleLike = async () => {
  if (!blog.value || likeLoading.value) return

  try {
    likeLoading.value = true
    const response = await fetch(`/api/v1/blogs/${blog.value.slug}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      }
    })

    if (response.ok) {
      const data = await response.json()
      isLiked.value = data.liked
      blog.value.stats.likes = data.likes_count
    }
  } catch (err) {
    console.error('Error toggling like:', err)
  } finally {
    likeLoading.value = false
  }
}

// Submit comment
const submitComment = async () => {
  if (!blog.value || commentLoading.value) return

  // Check if user is authenticated
  if (!isAuthenticated.value) {
    // Redirect to login with return URL
    router.push({
      path: '/login',
      query: { redirect: route.fullPath }
    })
    return
  }

  try {
    commentLoading.value = true
    const response = await fetch(`/api/v1/blogs/${blog.value.slug}/comments`, {
      method: 'POST',
      headers: authStore.getAuthHeaders(),
      body: JSON.stringify({
        content: commentForm.value.content
      })
    })

    if (response.ok) {
      // Reset form content
      commentForm.value.content = ''

      // Refresh comments to get the latest data and ensure persistence
      await fetchComments(1, false) // Reset to first page to show new comment

      // Update comment count if blog stats exist
      if (blog.value.stats) {
        blog.value.stats.comments++
      }
    } else {
      const errorData = await response.json()
      throw new Error(errorData.message || 'Failed to post comment')
    }
  } catch (err) {
    console.error('Error submitting comment:', err)
    // You could show a toast notification here
  } finally {
    commentLoading.value = false
  }
}

// Toggle reply form
const toggleReplyForm = (commentId) => {
  replyFormVisible.value = replyFormVisible.value === commentId ? null : commentId
  replyForm.value = {
    content: ''
  }
}

// Submit reply
const submitReply = async (commentId) => {
  if (!blog.value || replyLoading.value) return

  // Check if user is authenticated
  if (!isAuthenticated.value) {
    router.push({
      path: '/login',
      query: { redirect: route.fullPath }
    })
    return
  }

  try {
    replyLoading.value = true
    const response = await fetch(`/api/v1/comments/${commentId}/reply`, {
      method: 'POST',
      headers: authStore.getAuthHeaders(),
      body: JSON.stringify({
        content: replyForm.value.content
      })
    })

    if (response.ok) {
      // Reset form
      toggleReplyForm(null)

      // Refresh comments to get the latest data and ensure persistence
      await fetchComments(pagination.value.current_page, false)

      // Update comment count if blog stats exist
      if (blog.value.stats) {
        blog.value.stats.comments++
      }
    } else {
      const errorData = await response.json()
      throw new Error(errorData.message || 'Failed to post reply')
    }
  } catch (err) {
    console.error('Error submitting reply:', err)
  } finally {
    replyLoading.value = false
  }
}

// Like comment
const likeComment = async (commentId) => {
  const comment = comments.value.find(c => c.id === commentId)
  if (!comment) return

  try {
    const response = await fetch(`/api/v1/comments/${commentId}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      }
    })

    if (response.ok) {
      const data = await response.json()
      comment.isLiked = data.liked
      comment.likes_count = data.likes_count
    }
  } catch (err) {
    console.error('Error liking comment:', err)
  }
}

// Share blog
const shareBlog = async () => {
  if (navigator.share) {
    try {
      await navigator.share({
        title: blog.value.title,
        text: blog.value.excerpt,
        url: window.location.href
      })
    } catch (err) {
      if (err.name !== 'AbortError') {
        copyLink()
      }
    }
  } else {
    copyLink()
  }
}

// Copy link
const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href)
    // You could add a toast notification here
    console.log('Link copied to clipboard!')
  } catch (err) {
    console.error('Error copying link:', err)
  }
}

// Navigate to blog
const navigateToBlog = (slug) => {
  router.push(`/blog/${slug}`)
}

// Helper functions
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

// Enhance code blocks with language detection and copy functionality
const enhanceCodeBlocks = () => {
  const codeBlocks = document.querySelectorAll('.blog-content pre')

  codeBlocks.forEach((pre) => {
    const code = pre.querySelector('code')
    if (!code) return

    // Detect language from class or content
    const language = detectLanguage(code)
    if (language) {
      pre.setAttribute('data-language', language)
    }

    // Add copy button
    const copyButton = document.createElement('button')
    copyButton.innerHTML = '📋 Copy'
    copyButton.className = 'code-copy-btn'
    copyButton.style.cssText = `
      position: absolute;
      top: 0.75rem;
      left: 0.75rem;
      background: rgba(0, 255, 255, 0.2);
      color: #00ffff;
      border: 1px solid rgba(0, 255, 255, 0.3);
      padding: 0.25rem 0.75rem;
      border-radius: 1rem;
      font-size: 0.75rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
    `

    copyButton.addEventListener('click', async () => {
      try {
        const text = code.textContent || code.innerText
        await navigator.clipboard.writeText(text)

        copyButton.innerHTML = '✅ Copied!'
        copyButton.style.background = 'rgba(0, 255, 0, 0.3)'
        copyButton.style.color = '#00ff00'

        setTimeout(() => {
          copyButton.innerHTML = '📋 Copy'
          copyButton.style.background = 'rgba(0, 255, 255, 0.2)'
          copyButton.style.color = '#00ffff'
        }, 2000)
      } catch (err) {
        console.error('Failed to copy code:', err)
      }
    })

    pre.appendChild(copyButton)
  })
}

// Detect programming language from code content
const detectLanguage = (codeElement) => {
  const code = codeElement.textContent || codeElement.innerText
  const className = codeElement.className || ''

  // Check for explicit language class
  if (className.includes('language-')) {
    const match = className.match(/language-(\w+)/)
    if (match) return match[1].toUpperCase()
  }

  // Detect from patterns
  if (code.includes('def ') && code.includes(':')) return 'PYTHON'
  if (code.includes('function ') && code.includes('{')) return 'JAVASCRIPT'
  if (code.includes('public class ') || code.includes('private ')) return 'JAVA'
  if (code.includes('<?php') || code.includes('$')) return 'PHP'
  if (code.includes('#include') || code.includes('int main')) return 'C++'
  if (code.includes('using System') || code.includes('namespace ')) return 'C#'
  if (code.includes('import React') || code.includes('useState')) return 'REACT'
  if (code.includes('import ') && code.includes('from ')) return 'JAVASCRIPT'
  if (code.includes('<html') || code.includes('<div>')) return 'HTML'
  if (code.includes('SELECT ') || code.includes('FROM ')) return 'SQL'
  if (code.includes('git ')) return 'GIT'
  if (code.includes('npm ') || code.includes('yarn ')) return 'NPM'

  return 'CODE'
}


onMounted(async () => {
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

  // Fetch blog details
  await fetchBlogDetails()

  // Enhance code blocks after content is loaded
  enhanceCodeBlocks()
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

:global(body.blog-details-page-active html) {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}

/* Blog content styling */
.blog-content {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.7;
}

.blog-content h1,
.blog-content h2,
.blog-content h3,
.blog-content h4,
.blog-content h5,
.blog-content h6 {
  color: #00ffff;
  margin-top: 2.5rem;
  margin-bottom: 1.2rem;
  font-weight: 700;
  text-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
  scroll-margin-top: 100px; /* For anchor links */
}

.blog-content h1 {
  font-size: 2.5rem;
  border-bottom: 2px solid rgba(0, 255, 255, 0.3);
  padding-bottom: 0.5rem;
}
.blog-content h2 {
  font-size: 2rem;
  border-bottom: 1px solid rgba(0, 255, 255, 0.2);
  padding-bottom: 0.3rem;
}
.blog-content h3 { font-size: 1.5rem; }
.blog-content h4 { font-size: 1.25rem; }
.blog-content h5 { font-size: 1.1rem; }
.blog-content h6 { font-size: 1rem; }

.blog-content p {
  margin-bottom: 1.5rem;
  line-height: 1.8;
  color: rgba(255, 255, 255, 0.9);
  text-align: justify;
}

.blog-content ul,
.blog-content ol {
  margin-bottom: 1.5rem;
  padding-left: 2rem;
}

.blog-content li {
  margin-bottom: 0.6rem;
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
}

.blog-content blockquote {
  border-left: 4px solid #00ffff;
  margin: 1.5rem 0;
  font-style: italic;
  color: rgba(255, 255, 255, 0.8);
  background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.05));
  padding: 1.5rem;
  border-radius: 0.75rem;
  position: relative;
  box-shadow: 0 4px 20px rgba(0, 255, 255, 0.1);
}

.blog-content blockquote::before {
  content: '"';
  position: absolute;
  top: -10px;
  left: 20px;
  font-size: 4rem;
  color: rgba(0, 255, 255, 0.3);
  font-family: Georgia, serif;
}

/* Inline code */
.blog-content code:not(pre code) {
  background: linear-gradient(135deg, rgba(255, 0, 255, 0.2), rgba(255, 0, 255, 0.1));
  color: #ff00ff;
  padding: 0.2rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-family: 'Fira Code', 'Courier New', monospace;
  border: 1px solid rgba(255, 0, 255, 0.3);
  text-shadow: 0 0 5px rgba(255, 0, 255, 0.5);
  font-weight: 500;
}

/* Enhanced code blocks */
.blog-content pre {
  background: rgba(0, 8, 20, 0.95);
  border: 1px solid rgba(0, 255, 255, 0.3);
  border-radius: 0.75rem;
  padding: 0;
  margin: 2rem 0;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.blog-content pre::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #00ffff, #ff00ff, #00ffff);
  background-size: 200% 100%;
  animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

/* Code block header */
.blog-content pre::after {
  content: attr(data-language);
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  background: rgba(0, 255, 255, 0.2);
  color: #00ffff;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: 1px solid rgba(0, 255, 255, 0.3);
  z-index: 5;
}

/* Scrollable code container */
.blog-content pre code {
  background: none;
  color: #ffffff;
  padding: 1.5rem;
  font-family: 'Fira Code', 'Courier New', monospace;
  font-size: 0.9rem;
  line-height: 1.6;
  display: block;
  white-space: pre;
  overflow-x: auto;
  max-height: 500px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 255, 255, 0.5) rgba(0, 8, 20, 0.8);
}

/* Custom scrollbar for code blocks */
.blog-content pre code::-webkit-scrollbar {
  width: 12px;
  height: 12px;
}

.blog-content pre code::-webkit-scrollbar-track {
  background: rgba(0, 8, 20, 0.8);
  border-radius: 6px;
}

.blog-content pre code::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, rgba(0, 255, 255, 0.6), rgba(255, 0, 255, 0.6));
  border-radius: 6px;
  border: 1px solid rgba(0, 255, 255, 0.3);
}

.blog-content pre code::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, rgba(0, 255, 255, 0.8), rgba(255, 0, 255, 0.8));
}

.blog-content pre code::-webkit-scrollbar-corner {
  background: rgba(0, 8, 20, 0.8);
}

/* Syntax highlighting colors */
.blog-content pre .token.comment,
.blog-content pre .token.prolog,
.blog-content pre .token.doctype,
.blog-content pre .token.cdata {
  color: #6a9955;
  font-style: italic;
}

.blog-content pre .token.punctuation {
  color: #d4d4d4;
}

.blog-content pre .token.property,
.blog-content pre .token.tag,
.blog-content pre .token.boolean,
.blog-content pre .token.number,
.blog-content pre .token.constant,
.blog-content pre .token.symbol,
.blog-content pre .token.deleted {
  color: #b5cea8;
}

.blog-content pre .token.selector,
.blog-content pre .token.attr-name,
.blog-content pre .token.string,
.blog-content pre .token.char,
.blog-content pre .token.builtin,
.blog-content pre .token.inserted {
  color: #ce9178;
}

.blog-content pre .token.operator,
.blog-content pre .token.entity,
.blog-content pre .token.url,
.blog-content pre .language-css .token.string,
.blog-content pre .style .token.string {
  color: #d4d4d4;
}

.blog-content pre .token.atrule,
.blog-content pre .token.attr-value,
.blog-content pre .token.keyword {
  color: #c586c0;
}

.blog-content pre .token.function,
.blog-content pre .token.class-name {
  color: #dcdcaa;
}

.blog-content pre .token.regex,
.blog-content pre .token.important,
.blog-content pre .token.variable {
  color: #d16969;
}

.blog-content a {
  color: #00ffff;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: all 0.3s ease;
  position: relative;
  display: inline-block;
}

.blog-content a:hover {
  border-bottom-color: #00ffff;
  transform: translateY(-1px);
  text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}

.blog-content img {
  max-width: 100%;
  height: auto;
  border-radius: 0.75rem;
  margin: 2rem 0;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-content img:hover {
  transform: scale(1.02);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
}

.blog-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 2rem 0;
  background: rgba(0, 8, 20, 0.6);
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}

.blog-content th,
.blog-content td {
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 1rem;
  text-align: left;
  transition: background-color 0.3s ease;
}

.blog-content th {
  background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(255, 0, 255, 0.1));
  color: #00ffff;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.875rem;
}

.blog-content tr:nth-child(even) td {
  background: rgba(255, 255, 255, 0.02);
}

.blog-content tr:hover td {
  background: rgba(0, 255, 255, 0.05);
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

/* Custom scrollbar for BlogDetails page */
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

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .blog-content h1 { font-size: 2rem; }
  .blog-content h2 { font-size: 1.5rem; }
  .blog-content h3 { font-size: 1.25rem; }
}

/* Animation delays */
.delay-1000 {
  animation-delay: 1s;
}

.delay-2000 {
  animation-delay: 2s;
}
</style>
