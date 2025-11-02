import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
    meta: {
      title: '3D Portfolio | Ganesh K P'
    }
  },
  {
    path: '/projects',
    name: 'Projects',
    component: () => import('../views/Projects.vue'),
    meta: {
      title: 'Projects Gallery | 3D Portfolio'
    }
  },
  {
    path: '/blogs',
    name: 'Blogs',
    component: () => import('../views/Blogs.vue'),
    meta: {
      title: 'Tech Blog | Ganesh K P'
    }
  },
  {
    path: '/blog/:slug',
    name: 'BlogDetails',
    component: () => import('../views/BlogDetails.vue'),
    meta: {
      title: 'Blog Article | Ganesh K P'
    }
  },
  {
    path: '/contact',
    name: 'Contact',
    component: () => import('../views/Contact.vue'),
    meta: {
      title: 'Contact Me | 3D Portfolio'
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: {
      title: 'Sign In | Space Portfolio'
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
    meta: {
      title: 'Create Account | Space Portfolio'
    }
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

// Update document title
router.beforeEach((to, from, next) => {
  document.title = to.meta.title || '3D Portfolio | Ganesh K P'
  next()
})

export default router
