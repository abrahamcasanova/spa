<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
        <p class="text-3xl font-bold text-blue-600">{{totalUsers.total}}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h3 class="text-gray-500 text-sm font-medium">Users Actives</h3>
        <p class="text-3xl font-bold text-green-600">{{totalUsers.active}}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <h3 class="text-gray-500 text-sm font-medium">Users Inactives</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ totalUsers.inactive }}</p>
      </div>
    </div>      
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
      <h3 class="text-gray-700 text-lg font-medium mb-4">Users by rol</h3>
      <div class="h-full w-full">
        <BarChart :chartData="barChartData" :options="barChartOptions" />
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mt-3.5">
      <h3 class="text-gray-700 text-lg font-medium mb-4">Recent Logs</h3>
      <LogTable :logs="logs" />
    </div>
      
  </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';
import { LineChart, BarChart } from "vue-chart-3";
import { Chart, ChartData, ChartOptions, registerables } from "chart.js";
import api from "@/api/utils/api";
import LogTable from "@/components/LogTable.vue";

const totalUsers = reactive({
  total: 0,
  active: 0,
  inactive: 0,
  admins: 0
});

const logs = ref([]);


async function loadDashboardData() {
  try {
    const response = await api.get('/dashboard/resume');
    const data = response.data;
    totalUsers.total = data.total_users;
    totalUsers.active = data.active_users;
    totalUsers.inactive = data.inactive_users;
    barChartData.datasets[0].data = data.roles;
    logs.value = data.logs;
    
  } catch (error) {
    console.error('Error cargando datos del dashboard:', error);
  }
}

async function getRoles () {
  try {
    const response = await api.get('/roles');
    const roles = response.data;
    barChartData.labels = roles.map((role: { name: string }) => role);
  } catch (error) {
    console.error('Error cargando roles:', error);
  }
};

onMounted(async () => {
  await loadDashboardData();
  await getRoles();
});

Chart.register(...registerables);

const barChartData = reactive({
  labels: [],
  datasets: [
    {
      label: 'Users',
      backgroundColor: [
        'rgba(59, 130, 246, 0.7)',
        'rgba(139, 92, 246, 0.7)',
        'rgba(16, 185, 129, 0.7)',
        'rgba(245, 158, 11, 0.7)'
      ],
      borderColor: [
        'rgba(59, 130, 246, 1)',
        'rgba(139, 92, 246, 1)',
        'rgba(16, 185, 129, 1)',
        'rgba(245, 158, 11, 1)'
      ],
      borderWidth: 1,
      data: [8, 16, 85, 15]
    }
  ]
});

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      suggestedMax: (totalUsers.total + 5)
    }
  }
};

</script>