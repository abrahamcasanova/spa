<template>
  <aside
    class="bg-slate-800 shadow-md overflow-y-auto w-64 h-full transition-transform duration-300 ease-in-out fixed z-20">
    <div class="px-4 py-2">
      <nav class="flex-1 w-full flex flex-col items-center">
        <ul class="space-y-2 w-full">
          <li v-if="isAdmin">
            <router-link
              to="/home/dashboard"
              class="flex items-center px-4 py-2 text-slate-200 hover:bg-gray-700 rounded"
              :class="isOpen ? 'justify-start' : 'justify-center'"
              active-class="bg-grey-800 text-white">
              <WindowIcon class="w-6 h-6 flex-shrink-0" />
              <span v-if="isOpen" class="ml-2">Dashboard</span>
            </router-link>
          </li>
          <li v-if="isAdmin">
            <div class="">
              <button
                @click="toggleUsersMenu"
                class="flex items-center px-4 py-2 text-slate-200 hover:bg-gray-700 rounded w-full"
                :class="isOpen ? 'justify-start' : 'justify-center'">
                <UserIcon class="w-6 h-6 flex-shrink-0" />
                <span v-if="isOpen" class="ml-2">Users</span>
                <ChevronDownIcon
                  v-if="isOpen"
                  :class="[
                    'w-5 h-5 transition-transform duration-200 flex ml-auto justify-end items-end',
                    isUsersMenuOpen
                      ? 'rotate-180'
                      : 'flex ml-auto justify-end items-end',
                  ]" />
              </button>
              <transition
                enter-active-class="transition-all duration-200 ease-in-out"
                leave-active-class="transition-all duration-200 ease-in-out"
                enter-from-class="opacity-0 max-h-0 transform -translate-y-2"
                enter-to-class="opacity-100 max-h-screen transform translate-y-0"
                leave-from-class="opacity-100 max-h-screen transform translate-y-0"
                leave-to-class="opacity-0 max-h-0 transform -translate-y-2">
                <ul
                  v-if="isUsersMenuOpen && isOpen"
                  class="ml-8 mt-2 space-y-2">
                  <li>
                    <router-link
                      to="/home/users"
                      class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                      <span>Users</span>
                    </router-link>
                  </li>
                </ul>
              </transition>
            </div>
          </li>
          <li>
            <a
              href="#"
              class="flex items-center px-4 py-2 text-slate-200 hover:bg-gray-700 rounded"
              :class="isOpen ? 'justify-start' : 'justify-center'"
              @click="logout">
              <PowerIcon class="w-6 h-6 flex-shrink-0" />
              <span v-if="isOpen" class="ml-2">Exit</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import {ref, nextTick, computed} from "vue";
import {
  ChevronLeftIcon,
  ChevronDownIcon,
  Bars3Icon,
  UserIcon,
  PowerIcon,
  WindowIcon,
} from "@heroicons/vue/24/outline";
import {useAuthStore} from "../stores/authStore";
import router from "../router";

const isOpen = ref(true);
const isUsersMenuOpen = ref(false);
defineEmits(["close-sidebar"]);

const authStore = useAuthStore();
const isAdmin = computed(() => authStore.user?.role === "admin");

function toggleSidebar() {
  isOpen.value = !isOpen.value;
}

function toggleUsersMenu() {
  isUsersMenuOpen.value = !isUsersMenuOpen.value;
}

async function logout() {
  try {
    await useAuthStore().logout();
  } catch (error) {
    console.error("Error al cerrar sesión:", error);
  } finally {
    router.push("/login");
  }
}
</script>
