<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";
import { mdiMonitorDashboard, mdiPlus, mdiArrowRight, mdiMonitor, mdiLaptop, mdiServer, mdiChartBar } from "@mdi/js";
import { Chart, registerables } from 'chart.js';
import { ref, onMounted } from 'vue';

Chart.register(...registerables);

const props = defineProps({
  sistemasCount: Number,
  equiposCount: Number,
  equiposPorTipo: Object,
  sistemasPorEstatus: Object,
  ultimosEquipos: Array,
  sistemasRecientes: Array
});

// Referencias para los gráficos
const chartEquiposRef = ref(null);
const chartSistemasRef = ref(null);

onMounted(() => {
  // Gráfico de distribución de equipos
  if (chartEquiposRef.value) {
    const ctx = chartEquiposRef.value.getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: Object.keys(props.equiposPorTipo),
        datasets: [{
          data: Object.values(props.equiposPorTipo),
          backgroundColor: [
            '#6366F1', // purple
            '#3B82F6', // blue
            '#10B981', // green
            '#F59E0B', // amber
            '#EF4444'  // red
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  }

  // Gráfico de sistemas por estatus
  if (chartSistemasRef.value) {
    const ctx = chartSistemasRef.value.getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Producción', 'Pruebas', 'Desarrollo', 'Inactivo'],
        datasets: [{
          label: 'Sistemas por Estado',
          data: [
            props.sistemasPorEstatus?.Producción || 0,
            props.sistemasPorEstatus?.Pruebas || 0,
            props.sistemasPorEstatus?.Desarrollo || 0,
            props.sistemasPorEstatus?.Inactivo || 0
          ],
          backgroundColor: [
            '#10B981', // Producción - verde
            '#F59E0B', // Pruebas - amarillo
            '#3B82F6', // Desarrollo - azul
            '#EF4444'  // Inactivo - rojo
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return `${context.raw} sistemas`;
              }
            }
          }
        }
      }
    });
  }
});
</script>

<template>

  <Head title="Dashboard Admin" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Panel de Administración de TI" main class="mb-6"  :icon="mdiMonitorDashboard" >
          <div class="flex space-x-2">
            <BaseButton label="Nuevo Sistema" :icon="mdiPlus" color="info" small routeName="sistema.create" />
            <BaseButton label="Registrar Equipo" :icon="mdiPlus" color="success" small routeName="inventario.create" />
          </div>
      </SectionTitleLineWithButton>
    </SectionMain>

    <!-- Tarjetas de Métricas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Tarjeta Sistemas -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-blue-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Sistemas</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ sistemasCount }}</p>
            <p class="text-xs text-gray-500 mt-1">
              <span class="text-green-500">{{ sistemasPorEstatus?.Producción || 0 }} en producción</span>
            </p>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Equipos -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-green-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Equipos</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ equiposCount }}</p>
            <p class="text-xs text-gray-500 mt-1">
              <span class="text-purple-500">{{ equiposPorTipo?.Laptop || 0 }} laptops</span>
            </p>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Tipos de Equipo -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-purple-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
              <path fill-rule="evenodd"
                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Distribución</h3>
            <div class="flex space-x-4 mt-2">
              <div>
                <p class="text-lg font-bold text-purple-600">{{ equiposPorTipo?.Laptop || 0 }}</p>
                <p class="text-xs text-gray-500">Laptops</p>
              </div>
              <div>
                <p class="text-lg font-bold text-blue-600">{{ equiposPorTipo?.['PC Escritorio'] || 0 }}</p>
                <p class="text-xs text-gray-500">Escritorio</p>
              </div>
              <div>
                <p class="text-lg font-bold text-blue-600">{{ equiposPorTipo?.['Impresora'] || 0 }}</p>
                <p class="text-xs text-gray-500">Impresora</p>
              </div>
                <div>
                <p class="text-lg font-bold text-blue-600">{{ equiposPorTipo?.['PC Escritorio (Servidor)'] || 0 }}</p>
                <p class="text-xs text-gray-500">PC Escritorio (Servidor)</p>
              </div>
            </div>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Estados -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-amber-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Estados</h3>
            <div class="flex space-x-4 mt-2">
              <div>
                <p class="text-lg font-bold text-green-600">{{ sistemasPorEstatus?.Producción || 0 }}</p>
                <p class="text-xs text-gray-500">Producción</p>
              </div>
              <div>
                <p class="text-lg font-bold text-red-600">{{ sistemasPorEstatus?.Inactivo || 0 }}</p>
                <p class="text-xs text-gray-500">Inactivos</p>
              </div>
            </div>
          </div>
        </div>
      </CardBox>
    </div>

    <!-- Sección de Actividad Reciente -->
 <!-- Sección de Actividad Reciente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Últimos Equipos -->
        <CardBox class="overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
          <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30">
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                    clip-rule="evenodd" />
                </svg>
                Últimos Equipos Registrados
              </h2>
              <BaseButton label="Ver todos" :icon="mdiArrowRight" color="info" small outline routeName="inventario.index" />
            </div>
          </div>
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div v-for="equipo in ultimosEquipos" :key="equipo.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ equipo.nombre_persona }}</h3>
                  <div class="flex items-center mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                      'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200': equipo.tipo_pc === 'Laptop',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': equipo.tipo_pc === 'PC Escritorio'
                    }">
                      {{ equipo.tipo_pc }}
                    </span>
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ equipo.marca_equipo }}</span>
                  </div>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ new Date(equipo.fecha_registro).toLocaleDateString() }}
                </span>
              </div>
            </div>
          </div>
        </CardBox>

       <!-- Últimos Sistemas -->
        <CardBox class="overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
          <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-teal-50 dark:from-green-900/30 dark:to-teal-900/30">
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                </svg>
                Sistemas Recientes
              </h2>
              <BaseButton label="Ver todos" :icon="mdiArrowRight" color="success" small outline routeName="sistema.index" />
            </div>
          </div>
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div v-for="sistema in sistemasRecientes" :key="sistema.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ sistema.nombre }}</h3>
                  <div class="flex items-center mt-1">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ sistema.departamento.nombre }}</span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': sistema.estatus === 'Producción',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': sistema.estatus === 'Pruebas',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': sistema.estatus === 'Desarrollo',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': sistema.estatus === 'Inactivo'
                    }">
                      {{ sistema.estatus }}
                    </span>
                  </div>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ new Date(sistema.fecha_produccion).toLocaleDateString() }}
                </span>
              </div>
            </div>
          </div>
        </CardBox>
    </div>

    <!-- Sección de Gráficos -->
<!-- Sección de Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
  <!-- Gráfico de Distribución de Equipos -->
  <CardBox class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/30 dark:to-indigo-900/30">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
        <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
        </svg>
        Distribución de Equipos por Tipo
      </h2>
    </div>
    <div class="p-6">
      <div class="h-64">
        <canvas ref="chartEquiposRef"></canvas>
      </div>
      <div class="grid grid-cols-3 gap-4 mt-4">
        <div v-for="(count, tipo) in equiposPorTipo" :key="tipo" class="text-center">
          <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ count }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ tipo }}</p>
        </div>
      </div>
    </div>
  </CardBox>

  <!-- Gráfico de Sistemas por Estado -->
  <CardBox class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
            clip-rule="evenodd" />
        </svg>
        Sistemas por Estado
      </h2>
    </div>
    <div class="p-6">
      <div class="h-64">
        <canvas ref="chartSistemasRef"></canvas>
      </div>
      <div class="grid grid-cols-4 gap-4 mt-4">
        <div class="text-center">
          <p class="text-lg font-semibold text-green-600 dark:text-green-400">{{ sistemasPorEstatus?.Producción || 0 }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Producción</p>
        </div>
        <div class="text-center">
          <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ sistemasPorEstatus?.Pruebas || 0 }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Pruebas</p>
        </div>
        <div class="text-center">
          <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ sistemasPorEstatus?.Desarrollo || 0 }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Desarrollo</p>
        </div>
        <div class="text-center">
          <p class="text-lg font-semibold text-red-600 dark:text-red-400">{{ sistemasPorEstatus?.Inactivo || 0 }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">Inactivo</p>
        </div>
      </div>
    </div>
  </CardBox>
</div>
  </LayoutDashboard>
</template>