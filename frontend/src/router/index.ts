import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      redirect: _to => {
        const authStore = useAuthStore()
        return authStore.isAuthenticated ? '/home' : '/login'
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Login.vue'),
      meta: { requiresAuth: false }
    },
    {
      path: '/home',
      name: 'home',
      component: () => import('../views/Home.vue'),
      meta: { requiresAuth: true },
      children: [
          {
              path: 'users',
              component: () => import('../views/Users.vue'),
          },
          {
            path: 'dashboard',
            component: () => import('../views/Dashboard.vue'),
        },
      ],
    }
  ]
})

router.beforeEach((to, _from, next) => {
  const authStore = useAuthStore()

  if (to.path === '/') {
    return next(authStore.isAuthenticated ? '/home' : '/login')
  }


  if (!authStore.isAuthenticated && to.name !== 'login') {
    next({ name: 'login' })
  } else {
    next()
  }
})

export default router