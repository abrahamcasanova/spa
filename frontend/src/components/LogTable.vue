<template>
  <div class="hidden sm:block rounded-lg border border-gray-300 shadow-md">
    <div class="bg-gray-200 rounded-t-lg">
  <table class="min-w-full">
    <thead>
      <tr class="text-gray-700">
        <th class="px-4 py-3 font-medium text-center align-middle">User</th>
        <th class="px-4 py-3 font-medium text-right align-middle">Actions</th>
        <th class="hidden md:table-cell px-4 py-3 font-medium text-center align-middle">IP</th>
        <th class="hidden lg:table-cell px-4 py-3 font-medium text-center align-middle">Agent</th>
        <th class="px-4 py-3 font-medium text-center align-middle">Date</th>
      </tr>
    </thead>
  </table>
</div>
    
    <div class="overflow-y-auto max-h-80 bg-white rounded-b-lg">
      <table class="min-w-full">
        <tbody>
          <tr v-for="log in logs" :key="log.id" class="hover:bg-gray-100">
            <td class="px-4 py-3 border-b text-gray-600">
              <div class="flex items-center">
                <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mr-3">
                  <img 
                    v-if="log.user && log.user.avatar_url" 
                    :src="log.user.avatar_url" 
                    alt="Avatar" 
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="w-full h-full bg-blue-500 flex items-center justify-center text-white">
                    {{ log.user ? log.user.name.charAt(0).toUpperCase() : '?' }}
                  </div>
                </div>
                <div>
                  <p class="font-medium">{{ log.user ? log.user.name : 'Usuario eliminado' }}</p>
                  <p class="text-xs text-gray-500">{{ log.user ? log.user.email : '' }}</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-3 border-b text-center ">
              <span 
                :class="{
                  'bg-blue-200 text-blue-800': log.details.action === 'login',
                  'bg-green-200 text-green-800': log.details.action === 'updated' || log.details.action === 'created',
                  'bg-yellow-200 text-yellow-800': log.details.action === 'upload avatar',
                  'bg-red-200 text-red-800': log.details.action === 'deleted',
                  'bg-purple-200 text-purple-800': log.details.action === 'logout',
                  'bg-gray-200 text-gray-800': !['login', 'updated', 'created', 'upload avatar', 'deleted', 'logout'].includes(log.details.action)
                }"
                class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                {{ log.details.action.toUpperCase() }}
              </span>
            </td>
            <td class="hidden md:table-cell px-4 py-3 border-b text-gray-600">
              {{ log.details.ip }}
            </td>
            <td class="hidden lg:table-cell px-4 py-3 border-b text-gray-600 text-right">
              <span class="truncate block max-w-[200px]" :title="getBrowserInfo(log.details.agent)">
                {{ getBrowserInfo(log.details.agent) }}
              </span>
            </td>
            <td class="px-4 py-3 border-b text-gray-600 text-right">
              {{ formatDateTime(log.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="sm:hidden mt-4">
    <div class="overflow-y-auto max-h-96">
      <div class="space-y-4 p-1">
        <div 
          v-for="log in logs" 
          :key="log.id" 
          class="bg-white rounded-lg shadow p-4 border border-gray-200">
          <div class="flex justify-between items-start mb-3">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 mr-3">
                <img 
                  v-if="log.user && log.user.avatar_url" 
                  :src="log.user.avatar_url" 
                  alt="Avatar" 
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full bg-blue-500 flex items-center justify-center text-white">
                  {{ log.user ? log.user.name.charAt(0).toUpperCase() : '?' }}
                </div>
              </div>
              <div>
                <p class="font-medium">{{ log.user ? log.user.name : 'User deleted' }}</p>
                <p class="text-xs text-gray-500">{{ log.user ? log.user.email : '' }}</p>
              </div>
            </div>
            <span 
              :class="{
                'bg-blue-200 text-blue-800': log.details.action === 'login',
                'bg-green-200 text-green-800': log.details.action === 'updated' || log.details.action === 'created',
                'bg-yellow-200 text-yellow-800': log.details.action === 'upload avatar',
                'bg-red-200 text-red-800': log.details.action === 'deleted',
                'bg-purple-200 text-purple-800': log.details.action === 'logout',
                'bg-gray-200 text-gray-800': !['login', 'updated', 'created', 'upload avatar', 'deleted', 'logout'].includes(log.details.action)
              }"
              class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
              {{ log.details.action.toUpperCase() }}
            </span>
          </div>
          
          <div class="grid grid-cols-2 gap-2 text-sm">
            <div>
              <span class="font-semibold text-gray-600">IP:</span>
              <span class="text-gray-700">{{ log.details.ip }}</span>
            </div>
            <div>
              <span class="font-semibold text-gray-600">Date:</span>
              <span class="text-gray-700">{{ formatDateTime(log.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import { formatDistanceToNow, parseISO } from 'date-fns';
import type { Log } from '../interfaces/Log';

defineProps({
  logs: {
    type: Array as () => Log[],
    required: true,
  },
});

function formatDateTime(dateString: string): string {
  try {
    const date = parseISO(dateString);
    return formatDistanceToNow(date, { addSuffix: true});
  } catch (error) {
    return dateString;
  }
}

function getBrowserInfo(userAgent: string): string {
  if (!userAgent) return 'Unknown';
  
  if (userAgent.includes('Chrome')) {
    return 'Chrome';
  } else if (userAgent.includes('Firefox')) {
    return 'Firefox';
  } else if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) {
    return 'Safari';
  } else if (userAgent.includes('Edge')) {
    return 'Edge';
  } else if (userAgent.includes('MSIE') || userAgent.includes('Trident/')) {
    return 'Internet Explorer';
  } else {
    return 'Unknown';
  }
}
</script>