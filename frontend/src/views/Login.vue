<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
      <h1 class="mb-6 text-2xl font-bold text-center text-gray-700">Login</h1>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label
            for="email"
            class="block mb-2 text-sm font-medium text-gray-600"
            >Email</label
          >
          <input
            id="email"
            v-model="email"
            type="email"
            required
            class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="relative">
          <label
            for="password"
            class="block mb-2 text-sm font-medium text-gray-600"
            >Password</label
          >
          <input
            id="password"
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            required
            class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <button
            type="button"
            @click="togglePasswordVisibility"
            class="absolute right-0 top-[30px] flex items-center pr-3 text-gray-500 hover:text-gray-700 focus:outline-none h-1/2">
            <EyeIcon
              v-if="showPassword"
              class="w-5 h-5 text-gray-500 hover:text-gray-700" />
            <EyeSlashIcon
              v-else
              class="w-5 h-5 text-gray-500 hover:text-gray-700" />
          </button>
        </div>
        <button
          type="submit"
          class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {useRouter} from "vue-router";
import {useAuthStore} from "../stores/authStore";
import {EyeIcon, EyeSlashIcon} from "@heroicons/vue/24/outline";
import Swal from "sweetalert2";

const email = ref("");
const password = ref("");
const router = useRouter();
const authStore = useAuthStore();
const showPassword = ref(false);

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const handleLogin = () => {
  authStore
    .login({
      email: email.value,
      password: password.value,
    })
    .then(response => {
      console.log("Login successful:", response);
      router.push({name: "home"});
    })
    .catch((error) => {
      Swal.fire({
        icon: "error",
        title: "Login Failed",
        text: error.message || "Invalid email or password.",
        confirmButtonText: "OK",
      });
    });
};
</script>

<style scoped></style>
