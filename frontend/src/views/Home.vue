<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <Header @toggle-sidebar="toggleSidebar" class="w-full z-30" />

    <div class="flex flex-1 relative md:mt-16 sm:mt-16">
      <div
        v-if="sidebarOpen"
        class="md:hidden fixed inset-0 z-20 sidebar-overlay"
        @click="closeSidebar"></div>

      <div
        :class="{
          'fixed top-16 bottom-0 left-0 md:relative md:top-0': true,
          'hidden md:block': !sidebarOpen,
          block: sidebarOpen,
        }"
        class="w-64 z-20 bg-white shadow-md overflow-hidden h-auto md:h-full">
        <Sidebar @close-sidebar="closeSidebar" />
      </div>

      <main class="flex-1 p-4 bg-gray-100 overflow-y-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>
<script setup>
import {ref} from "vue";
import Header from "../components/Header.vue";
import Sidebar from "../components/Sidebar.vue";

const sidebarOpen = ref(false);

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value;
}

function closeSidebar() {
  sidebarOpen.value = false;
}
</script>

<style>
.main-content {
  height: calc(100vh - 64px);
  overflow-y: auto;
}

.sidebar-overlay {
  background-color: rgb(0 0 0 / 0.1);
}
</style>
