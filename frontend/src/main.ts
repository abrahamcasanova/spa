import { createApp } from 'vue'
import App from './App.vue'
import { configure } from 'vee-validate'
import * as yup from 'yup'
import './style.css'
import { createPinia } from 'pinia'
import router from './router'
import { useAuthStore } from './stores/authStore'
import Swal from 'sweetalert2'

configure({
  validateOnInput: true,
})

const app = createApp(App)

app.provide('yup', yup)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.config.globalProperties.$swal = Swal

app.mount('#app')

const authStore = useAuthStore()
authStore.loadAuthFromStorage()