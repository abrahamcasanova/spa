export interface User {
    id: number
    name: string
    email: string
    role: 'admin' | 'user'
    avatar?: string
  }
  
  export interface LoginForm {
    email: string
    password: string
  }
  
  export interface AuthResponse {
    user: User
    token: string
  }