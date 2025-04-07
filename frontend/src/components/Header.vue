<template>
  <header
  class="bg-white shadow-sm py-2 px-4 flex items-center justify-between w-full md:fixed sm:fixed  md:top-0 md:left-0 md:right-0 md:z-30">
  <button
      @click="$emit('toggle-sidebar')"
      class="md:hidden text-gray-600 focus:outline-none"
      aria-label="Menú">
      <Bars3BottomLeftIcon class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10" />
    </button>

    <div class="flex items-center space-x-4 w-full">
      <div
        class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full mx-2">
        <span class="text-blue-600 font-bold">
          <img
            v-if="user.avatar_url"
            :src="user.avatar_url"
            alt="Avatar"
            class="w-10 h-10 rounded-full" />
          <span v-else class="text-lg font-bold">
            {{ profilettext }}
          </span>
        </span>
      </div>
      <div class="flex-1 flex items-end justify-end">
        <div class="mx-2 flex items-center">
          <UserCircleIcon
            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-gray-500" />
          <div class="mx-2 justify-end items-end flex flex-col">
            <h2 class="text-lg font-semibold text-gray-800 ml-2">
              {{ formattedName }}
            </h2>
            <p class="text-sm text-gray-500 ml-2">{{ formattedRole }}</p>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import {ref, computed, onMounted} from "vue";
import {useAuthStore} from "../stores/authStore";
import {capitalize} from "../api/utils/filters";
import {UserCircleIcon} from "@heroicons/vue/24/outline";
import {Bars3BottomLeftIcon} from "@heroicons/vue/16/solid";
defineEmits(["toggle-sidebar"]);

const authStore = useAuthStore();
const user = computed(() => authStore.user);
const formattedRole = computed(() => {
  if (user.value.role) {
    return capitalize(user.value.role);
  }
  return "";
});

const formattedName = computed(() => {
  if (user.value.name) {
    return user.value.name;
  }
  return "";
});
const profilettext = computed(() => {
  if (user.value.name) {
    return user.value.name.charAt(0).toUpperCase();
  }
  return "";
});
</script>

<style scoped></style>
