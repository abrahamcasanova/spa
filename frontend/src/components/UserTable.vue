<template>
  <div class="hidden sm:block overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
      <thead class="bg-gray-200">
        <tr class="text-left text-gray-700">
          <th class="hidden md:table-cell px-4 py-3 border-b font-medium">
            ID
          </th>
          <th class="px-4 py-3 border-b font-medium">Nombre</th>
          <th class="px-4 py-3 border-b font-medium">Email</th>
          <th class="hidden md:table-cell px-4 py-3 border-b font-medium">
            Creación
          </th>
          <th class="px-4 py-3 border-b font-medium">Estado</th>
          <th class="px-4 py-3 border-b font-medium">Rol</th>
          <th class="px-4 py-3 border-b font-medium">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100">
          <td class="hidden md:table-cell px-4 py-3 border-b text-gray-600">
            {{ user.id }}
          </td>
          <td class="px-4 py-3 border-b text-gray-600">
            <div class="flex items-center">
              <UserIcon class="w-5 h-5 text-gray-500 inline-block mr-2" />
              <span class="truncate max-w-[120px] lg:max-w-xs">{{
                user.name
              }}</span>
            </div>
          </td>
          <td class="px-4 py-3 border-b text-gray-600">
            <span class="truncate block max-w-[120px] lg:max-w-xs">{{
              user.email
            }}</span>
          </td>
          <td class="hidden md:table-cell px-4 py-3 border-b text-gray-600">
            {{ formatDate(user.created_at) }}
          </td>
          <td class="px-4 py-3 border-b text-gray-600">
            <span
              :class="{
                'bg-green-200 text-green-800': user.status === 'active',
                'bg-red-200 text-red-800': user.status === 'inactive',
              }"
              class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
              {{ user.status.toUpperCase() }}
            </span>
          </td>
          <td class="px-4 py-3 border-b text-gray-600">
            <span
              :class="{
                'bg-blue-200 text-blue-800': user.role === 'admin',
                'bg-yellow-200 text-yellow-800': user.role === 'user',
              }"
              class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
              {{ user.role.toUpperCase() }}
            </span>
          </td>
          <td class="px-4 py-3 border-b text-gray-600">
            <div class="flex space-x-2">
              
              <PencilIcon
                @click="$emit('edit', user.id)"
                class="w-5 h-5 text-blue-500 hover:text-blue-700 cursor-pointer"
                aria-label="Editar usuario" />
              <TrashIcon
                @click="$emit('delete', user.id)"
                class="w-5 h-5 text-red-500 hover:text-red-700 cursor-pointer"
                aria-label="Eliminar usuario" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="sm:hidden space-y-4 mt-4">
    <div
      v-for="user in users"
      :key="user.id"
      class="bg-white rounded-lg shadow p-4 border border-gray-200">
      <div class="flex justify-between items-center mb-2">
        <div class="flex items-center">
          <UserIcon class="w-5 h-5 text-gray-500 mr-2" />
          <h3 class="font-medium text-gray-800">{{ user.name }}</h3>
        </div>
        <div class="flex space-x-2">
          <PencilIcon
            @click="$emit('edit', user.id)"
            class="w-5 h-5 text-blue-500 hover:text-blue-700 cursor-pointer" />
          <TrashIcon
            @click="$emit('delete', user.id)"
            class="w-5 h-5 text-red-500 hover:text-red-700 cursor-pointer" />
        </div>
      </div>
      <div class="text-sm text-gray-600 mb-1">
        <span class="font-medium">Email:</span> {{ user.email }}
      </div>
      <div class="flex justify-between mt-2">
        <span
          :class="{
            'bg-green-200 text-green-800': user.status === 'active',
            'bg-red-200 text-red-800': user.status === 'inactive',
          }"
          class="px-2 py-1 rounded-full text-xs font-semibold">
          {{ user.status.toUpperCase() }}
        </span>
        <span
          :class="{
            'bg-blue-200 text-blue-800': user.role === 'admin',
            'bg-yellow-200 text-yellow-800': user.role === 'user',
          }"
          class="px-2 py-1 rounded-full text-xs font-semibold">
          {{ user.role.toUpperCase() }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {defineProps} from "vue";
import {TrashIcon, PencilIcon, UserIcon, EyeIcon} from "@heroicons/vue/24/outline";
import {formatDate} from "@/api/utils/filters";
import type { User } from "@/api/interfaces/User";

defineEmits(["delete", "edit"]);

defineProps<{
  users: User[]
}>();

</script>
