<template>
  <div class="w-full h-full p-6 bg-gray-50">
    <div>
      <h1 class="text-2xl font-bold text-gray-800 mb-6">
        <UserCircleIcon class="w-8 h-8 text-blue-500 inline-block mr-2" />
        Users
      </h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="col-span-1 md:col-span-2">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by name or email"
          class="border border-gray-300 rounded-lg px-4 py-2 w-full" />
      </div>
      <div>
        <select
          v-model="selectedRole"
          class="border border-gray-300 rounded-lg px-4 py-2 w-full">
          <option value="">All Roles</option>
          <option v-for="(role, index) in roles" :key="index" :value="role">
            {{ role }}
          </option>
        </select>
      </div>
      <div>
        <select
          v-model="selectedStatus"
          class="border border-gray-300 rounded-lg px-4 py-2 w-full">
          <option value="">All Statuses</option>
          <option
            v-for="(statusItem, index) in status"
            :key="index"
            :value="statusItem">
            {{ statusItem }}
          </option>
        </select>
      </div>
      <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-between items-center">
        <button
          @click="showUserModal = true; type = 'create'; user = null"
          class="bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600 focus:outline-none cursor-pointer">
          <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add User
          </span>
        </button>
        <button
          @click="getUsers"
          class="bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300 focus:outline-none cursor-pointer flex items-center">
          <ArrowPathIcon class="w-5 h-5 text-gray-500 mr-2" />
          Refresh
        </button>
      </div>
    </div>
    <UserTable :users="filteredUsers" @delete="deleteUser" @edit="showEditUser" />
  </div>
  <UserModal
    v-if="showUserModal"
    :roles="roles"
    :user="user"
    :type="type"
    @close="showUserModal = false"
    @save="createUser"
    @update="updateUser"
  />
</template>

<script setup lang="ts">
import {ref, computed, onMounted} from "vue";
import UserTable from "../components/UserTable.vue";
import UserModal from "../components/UserModal.vue";
import api from "@/api/utils/api";
import {UserCircleIcon, ArrowPathIcon} from "@heroicons/vue/24/outline";
import Swal from 'sweetalert2';
import type { User } from "../interfaces/User";
import type { Role } from "../interfaces/Role";
import type { Status } from "../interfaces/Status";
import type { UserFormData } from "../interfaces/Types";

const users = ref<User[]>([]);
const roles = ref(<Role[]>([]));
const status = ref(<Status[]>([]));
const searchQuery = ref("");
const selectedRole = ref("");
const selectedStatus = ref("");
const showUserModal = ref(false);
const type = ref("create");
const user = ref<User | null>(null);

onMounted(async () => {
  try {
    await [getUsers(), getRoles(), getStatus()];
  } catch (error) {
    console.error("Error fetching users:", error);
  }
});

function getUsers() {
  api
    .get("/users")
    .then((response: {data: User[]}) => {
      users.value = response.data;
    })
    .catch((error : any) => {
      console.error("Error fetching users:", error);
    });
}

function getRoles() {
  api
    .get("/roles")
    .then((response: {data: Role[]}) => {
      roles.value = response.data
    })
    .catch((error: any) => {
      console.error("Error fetching roles:", error);
    });
}

function getStatus() {
  api
    .get("/status")
    .then((response: {data: Status[]}) => {
      status.value = response.data;
    })
    .catch((error: any) => {
      console.error("Error fetching status:", error);
    });
}

function deleteUser(userId: number) {
  Swal.fire({
    title: '¿Delete user?',
    text: '¿This action cannot be reverse?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Delete',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      api
        .delete(`/users/${userId}?force=true`)
        .then(() => {
          Swal.fire(
            'Deleted!',
            'User has been deleted.',
            'success'
          );
          users.value = users.value.filter((user) => user.id !== userId);
          getUsers();
        })
        .catch((error: any) => {
          console.error("Error deleting user:", error.response.data.message);
          Swal.fire(
              'Error!',
              error.response.data.message,
              'error'
            );
            return
        });
    }
  });
}

async function createUser(userData: UserFormData) {
  try {
    await saveUser(userData)
  } catch (error) {
    console.error("Error fetching users:", error);
  }
}

function saveUser(userData: UserFormData) {
  api
    .post("/users", userData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    .then((_response: { data: User }) => {
      Swal.fire(
        'Success!',
        'User has been created.',
        'success'
      );
      getUsers();
      showUserModal.value = false;
    })
    .catch((error: any) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.response.data.message,
      });
      console.error("Error creating user:", error);
    });
}

function showEditUser(userId: number) {
  const foundUser = users.value.find((u) => u.id === userId);
  if (foundUser) {
    user.value = foundUser;
    showUserModal.value = true;
    type.value = "edit";
  }
}

function updateUser(userData: UserFormData) {
  api
    .post(`/users/${user.value?.id}?_method=PUT`, userData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
    })
    .then(() => {
      getUsers();
      showUserModal.value = false;
    })
    .catch((error: any) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.response.data.message,
      });
      console.error("Error updating user:", error);
    });
}

const filteredUsers = computed(() => {
  return users.value.filter((user) => {
    const matchesSearch =
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesRole = selectedRole.value
      ? user.role === selectedRole.value
      : true;
    const matchesStatus = selectedStatus.value
      ? user.status === selectedStatus.value
      : true;
    return matchesSearch && matchesRole && matchesStatus;
  });
});
</script>
