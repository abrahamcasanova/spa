import api from '@/api/utils/api'
import type { LoginForm } from '@/types/auth'

export async function loginUser(formData: LoginForm) {
  try {
    const response = await api.post('/login', formData)
    return response.data
  } catch (error: any) {
    if (error.response) {
      throw new Error(error.response?.data?.error || 'Error')
    } else if (error.request) {
      throw new Error('Server error')
    } else {
      throw new Error('Error to process the request')
    }
  }
}

export async function logoutUser() {
  try {
    const response = await api.post('/logout')
    return response.data
  } catch (error: any) {
    if (error.response) {
      throw new Error(error.response.data.message || 'Error')
    } else {
      throw new Error('Error to process the request')
    }
  }
}