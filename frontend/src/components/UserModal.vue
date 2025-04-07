<template>
  <transition name="fade">
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div
        @click="onToggle"
        class="absolute bg-black opacity-70 inset-0 z-10"></div>
      <div
        class="w-full max-w-lg relative mx-auto my-auto rounded-xl shadow-lg bg-white z-15 max-h-[90vh] overflow-y-auto">
        <div
          class="w-full max-w-lg p-3 relative mx-auto my-auto rounded-xl shadow-lg bg-white z-15">
          <div>
            <div class="bg-white rounded-lg p-6 w-full">
              <h2 class="text-xl font-bold mb-4">New User</h2>
              <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                  <label class="block text-sm font-medium mb-1">Avatar</label>
                  
                  <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300">
                      <template v-if="avatarPreview">
                        <img :src="avatarPreview" class="w-full h-full object-cover" alt="Avatar" />
                      </template>
                      <template v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </template>
                    </div>
                    
                    <div class="flex-1">
                      <label for="avatar-upload" class="block w-full cursor-pointer text-center py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span>Upload Avatar</span>
                      </label>
                      <input
                        id="avatar-upload"
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        @change="handleFileChange"
                        class="sr-only" />
                      <p class="text-gray-500 text-xs mt-1 text-center">
                        JPG, PNG o GIF (máx. 1MB)
                      </p>
                    </div>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium mb-1">Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full" />
                  <p v-if="errors.name" class="text-red-500 text-sm">
                    {{ errors.name }}
                  </p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium mb-1">Email</label>
                  <input
                    v-model="form.email"
                    type="email"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full" />
                  <p v-if="errors.email" class="text-red-500 text-sm">
                    {{ errors.email }}
                  </p>
                </div>
                <div class="mb-4">
                  <label class="block text-sm font-medium mb-1">Role</label>
                  <select
                    v-model="form.role"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full">
                    <option value="">Select Role</option>
                    <option
                      v-for="(role, index) in roles"
                      :key="index"
                      :value="role">
                      {{ role }}
                    </option>
                  </select>
                  <p v-if="errors.role" class="text-red-500 text-sm">
                    {{ errors.role }}
                  </p>
                </div>
                <div class="mb-4" v-if="type === 'create'">
                  <label class="block text-sm font-medium mb-1">Password</label>
                  <input
                    v-model="form.password"
                    type="password"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full" />
                  <p v-if="errors.password" class="text-red-500 text-sm">
                    {{ errors.password }}
                  </p>
                </div>
                <div class="mb-4" v-if="type === 'create'">
                  <label class="block text-sm font-medium mb-1"
                    >Confirm Password</label
                  >
                  <input
                    v-model="form.password_confirmation"
                    type="password"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full" />
                  <p
                    v-if="errors.password_confirmation"
                    class="text-red-500 text-sm">
                    {{ errors.password_confirmation }}
                  </p>
                </div>
                <div class="mb-4" v-if="type === 'edit'">
                  <label class="block text-sm font-medium mb-1">Status</label>
                  <div class="inline-flex items-center gap-2">
                    <label
                      for="switch-component-on"
                      class="text-slate-600 text-sm cursor-pointer"
                      >Inactive</label
                    >
                    <div class="relative inline-block w-11 h-5">
                      <input
                        v-model="form.active"
                        id="switch-component-on"
                        type="checkbox"
                        class="peer appearance-none w-11 h-5 bg-slate-100 rounded-full checked:bg-slate-800 cursor-pointer transition-colors duration-300" />
                      <label
                        for="switch-component-on"
                        class="absolute top-0 left-0 w-5 h-5 bg-white rounded-full border border-slate-300 shadow-sm transition-transform duration-300 peer-checked:translate-x-6 peer-checked:border-slate-800 cursor-pointer">
                      </label>
                    </div>
                    <label
                      for="switch-component-on"
                      class="text-slate-600 text-sm cursor-pointer"
                      >Active</label
                    >
                  </div>
                </div>
              </form>
            </div>
            <div class="p-4 mt-2 text-center space-x-4 md:block">
              <button
                type="button"
                @click="onToggle"
                class="mb-2 mr-2 md:mb-0 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-gray-700 border border-gray-300 rounded-md hover:shadow-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                <div class="flex items-center justify-center">
                  <XMarkIcon
                    class="w-4 h-4 mr-2"
                    stroke-width="1.5"
                    stroke="currentColor" />
                  <span class="text-sm">Cancel</span>
                </div>
              </button>
              <button
                type="submit"
                form="userForm"
                class="mb-2 md:mb-0 bg-blue-500 border border-blue-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer"
                @click.prevent="handleSubmit">
                <div class="flex items-center justify-center">
                  <CheckIcon
                    class="w-4 h-4 mr-2"
                    stroke-width="1.5"
                    stroke="currentColor" />
                  <span class="text-sm">{{ formattedType }}</span>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
import {onMounted, ref, computed} from "vue";
import * as yup from "yup";
import {XMarkIcon, CheckIcon} from "@heroicons/vue/24/outline";
import { capitalize } from "../api/utils/filters";
import type { UserFormData } from "../interfaces/Types";
import Swal from "sweetalert2";

const emit = defineEmits<{
  (e: 'submit'): void;
  (e: 'close'): void;
  (e: 'save', formData: globalThis.FormData): void;
  (e: 'update', formData: UserFormData): void;
}>();
const fileInput = ref(null);
const avatarPreview = ref('');

const props = defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
  user: {
    type: Object,
    default: () => ({
      name: "",
      email: "",
      role: "",
      status: undefined,
      active: false,
    }),
  },
  type: {
    type: String,
    default: "create",
  },
});
const roles = ref(props.roles);

const form = ref<UserFormData>({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  role: "",
  status: "active",
  active: false,
});

const formattedType = computed(() => {
  return capitalize(props.type);
});

const errors = ref({});

function handleFileChange(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file && file.size > 1024 * 1024) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "File size exceeds 1MB",
    });
    return;
  }
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      avatarPreview.value = (e.target as FileReader).result as string;
    };
    reader.readAsDataURL(file);
    form.value.avatar = file;
  }
}

const userSchema = computed(() => {
  const baseSchema = {
    name: yup.string().required("Name is required"),
    email: yup.string().email("Invalid email").required("Email is required"),
    role: yup.string().required("Role is required"),
  };

  if (props.type === "create") {
    return yup.object({
      ...baseSchema,
      password: yup.string().required("Password is required"),
      password_confirmation: yup
        .string()
        .oneOf([yup.ref("password")], "Passwords must match")
        .required("Password confirmation is required"),
    });
  } else {
    return yup.object({
      ...baseSchema,
      active: yup.boolean(),
    });
  }
});

function handleSubmit() {
  userSchema.value
    .validate(form.value, {abortEarly: false})
    .then(() => {
      if (props.type === "edit") {
        emitUpdate();
      } else {
        emitSave();
      }
      errors.value = {};
    })
    .catch((validationErrors) => {
      errors.value = validationErrors.inner.reduce((acc: Record<string, string>, err: Record<string, string> ) => {
        acc[err.path] = err.message;
        return acc;
      }, {});
    });
}

function emitSave() {
  const data = new FormData();
  
  data.append('name', form.value.name);
  data.append('email', form.value.email);
  data.append('password', form.value.password);
  data.append('password_confirmation', form.value.password_confirmation);
  data.append('role', form.value.role);
  data.append('status', 'active');
  
  if (form.value.avatar) {
    data.append('avatar', form.value.avatar);
  }
  
  emit("save", data);
}

function emitUpdate() {
  form.value.status = form.value.active ? "active" : "inactive";
  emit("update", form.value);
}

function onToggle() {
  emit("close");
}

onMounted(() => {
  if (props.user) {
    form.value.name = props.user.name;
    form.value.email = props.user.email;
    form.value.role = props.user.role;
    form.value.status = props.user.status;
    form.value.active = props.user.status === "active";
  }
});
</script>
