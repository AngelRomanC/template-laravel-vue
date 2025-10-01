<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/CardBox.vue";
import BaseButton from "@/Components/BaseButton.vue";
import { mdiMonitorDashboard, mdiPlus, mdiArrowRight, mdiMonitor, mdiLaptop, mdiServer, mdiChartBar } from "@mdi/js";
import { Chart, registerables } from 'chart.js';
import { ref, onMounted } from 'vue';

Chart.register(...registerables);

const props = defineProps({
  dato: Number,
  
});




</script>

<template>

  <Head title="Dashboard Admin" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Panel de Administración" main class="mb-6"  :icon="mdiMonitorDashboard" >
          <div class="flex space-x-2">
            <BaseButton label="Nuevo Sistema" :icon="mdiPlus" color="info" small routeName="" />
            <BaseButton label="Registrar Equipo" :icon="mdiPlus" color="success" small routeName="" />
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


  </LayoutDashboard>
</template>