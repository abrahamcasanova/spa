import { defineStore } from 'pinia'
import { loginUser, logoutUser } from '@/api/authApi'
import type { LoginForm } from '@/types/auth'
import router from '../router'

interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  updated_at: string
  status: string
  role: string
}

interface AuthState {
  user: User | null
  token: string | null
  isLoading: boolean
  error: string | null
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user') as string) : null,
    token: localStorage.getItem('token') || null,
    isLoading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user
  },

  actions: {
    async login(formData: LoginForm) {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await loginUser(formData)
        
        this.user = response.user
        this.token = response.token
        localStorage.setItem('user', JSON.stringify(response.user))
        localStorage.setItem('token', response.token)
        
        return response
      } catch (error: any) {
        this.error = error.message || 'Error'
        throw error 
      } finally {
        this.isLoading = false
      }
    },

    async logout() {
      try {
        await logoutUser()
      } finally {
        this.resetAuth()
        router.push('/login')
      }
    },

    resetAuth() {
      this.$reset()
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },

    loadAuthFromStorage() {
      const storedUser = localStorage.getItem('user')
      const storedToken = localStorage.getItem('token')
      if (storedUser) this.user = JSON.parse(storedUser)
      if (storedToken) this.token = storedToken
    }
  }
})