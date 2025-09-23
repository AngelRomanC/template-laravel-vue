<script setup>
import { defineProps, computed } from 'vue'
import LayoutDashboard from '@/Layouts/LayoutDashboard.vue'
import CardBox from "@/Components/CardBox.vue"
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/SectionMain.vue";
import { mdiChartBar, mdiArrowRight, mdiFileDocument } from "@mdi/js";
import BaseButton from '@/Components/BaseButton.vue'
import PaginationDashboard from '@/Shared/PaginationDashboard.vue';



const props = defineProps({
  titulo: String,
  stats: Object,
  porEstatus: Object,
  proximosProcesos: Array,
  procesos: Object,
})
// Colores de estatus
const estatusColor = (estatus) => {
  switch (estatus) {
    case 'Diseño': return 'bg-yellow-100 text-yellow-800'
    case 'Revisión': return 'bg-blue-100 text-blue-800'
    case 'Validación': return 'bg-green-100 text-green-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}
</script>

<template>
  <LayoutDashboard :title="titulo">
    <!-- Título -->
    <SectionMain>
      <SectionTitleLineWithButton :title=props.titulo main class="mb-6" :icon="mdiChartBar">
        <BaseButton label="Ver procesos" color="success" small outline routeName="procesos.index"
          :icon="mdiArrowRight" />
      </SectionTitleLineWithButton>
    </SectionMain>
    <!-- Tarjetas de métricas principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-indigo-100 p-4 rounded-full mr-4">
            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Total de Procesos</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.total }}</p>
          </div>
        </div>
      </CardBox>

      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-yellow-100 p-4 rounded-full mr-4">
            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Pendientes</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.pendientes }}</p>
          </div>
        </div>
      </CardBox>

      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-green-100 p-4 rounded-full mr-4">
           <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
          </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Con Documentos</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.con_documentos }}</p>
          </div>
        </div>
      </CardBox>

      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-red-100 p-4 rounded-full mr-4">
           <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm10 7a1 1 0 10-2 0 1 1 0 002 0zm-4 0a1 1 0 10-2 0 1 1 0 002 0z" clip-rule="evenodd"/>
          </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Sin Documentos</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ stats.sin_documentos }}</p>
          </div>
        </div>
      </CardBox>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

      <!-- Columna 1: Próximos a vencer -->
      <CardBox class="mb-0 p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-semibold flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M12 1.75a10.25 10.25 0 1010.25 10.25A10.262 10.262 0 0012 1.75zm0 18.5a8.25 8.25 0 118.25-8.25 8.26 8.26 0 01-8.25 8.25z" />
              <path
                d="M12 6a.75.75 0 01.75.75v5.25l4.5 2.7a.75.75 0 11-.75 1.3l-5-3A.75.75 0 0112 12V6.75A.75.75 0 0112 6z" />
            </svg>
            Próximos procesos a vencer (30 días)
          </h2>
        </div>

        <ul class="space-y-2">
          <li v-for="p in proximosProcesos" :key="p.id"
            class="flex justify-between items-center p-3 border rounded-lg shadow-sm hover:shadow-md transition bg-white">

            <div>
              <p class="font-medium text-gray-800">{{ p.nombre }}</p>
              <p class="text-xs text-gray-500">Departamento: {{ p.departamento?.nombre }}</p>
            </div>

            <div class="flex items-center space-x-2">
              <span :class="{
                'bg-red-100 text-red-700': new Date(p.fecha_fin_vigencia) <= new Date(Date.now() + 7 * 24 * 60 * 60 * 1000),
                'bg-yellow-100 text-yellow-700': new Date(p.fecha_fin_vigencia) > new Date(Date.now() + 7 * 24 * 60 * 60 * 1000) && new Date(p.fecha_fin_vigencia) <= new Date(Date.now() + 15 * 24 * 60 * 60 * 1000),
                'bg-green-100 text-green-700': new Date(p.fecha_fin_vigencia) > new Date(Date.now() + 15 * 24 * 60 * 60 * 1000)
              }" class="text-xs font-semibold px-2 py-1 rounded-full">
                {{ p.fecha_fin_vigencia }}
              </span>

              <span class="text-xs text-gray-400">
                {{ Math.ceil((new Date(p.fecha_fin_vigencia) - new Date()) / (1000 * 60 * 60 * 24)) }} días restantes
              </span>
            </div>
          </li>

          <li v-if="proximosProcesos.length === 0" class="text-gray-400 italic">No hay procesos próximos a vencer</li>
        </ul>
      </CardBox>

      <!-- Columna 2: Últimos registrados -->
        <div class="space-y-6">
          <SectionTitleLineWithButton 
            :icon="mdiFileDocument" 
            title="Procesos Recientes (Últimos 5)" 
          />
      <CardBox class="overflow-y-auto max-h-[600px]">
        <div class="overflow-x-auto flex-grow">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Departamento</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estatus</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fecha fin de Vigencia</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Documentos</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="p in procesos.data" :key="p.id" class="hover:bg-gray-50 transition even:bg-gray-50">
                <td data-label="Nombre" class="px-6 py-4 font-medium text-gray-700">{{ p.nombre }}</td>
                <td data-label="Departamento" class="px-6 py-4 text-gray-600">{{ p.departamento?.nombre }}</td>
                <td data-label="Estatus" class="px-6 py-4">
                  <span :class="['px-3 py-1 rounded-full text-xs font-semibold', estatusColor(p.estatus)]">
                    {{ p.estatus }}
                  </span>
                </td>
                <td data-label="Fin vigencia" class="px-6 py-4 text-gray-600">{{ p.fecha_fin_vigencia }}</td>
                <td data-label="Archivo(s)" class="px-6 py-4 text-gray-700">{{ p.archivos.length }} archivo(s)</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <BaseButton :icon="mdiArrowRight" color="info" small outline label="Ver"
                    :href="route('procesos.edit', p.id)" title="Ver detalles del proceso" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
            <PaginationDashboard 
              :currentPage="procesos.current_page" 
              :links="procesos.links"
              :total="procesos.last_page" 
              pageParam="procesos_ejecutivo"
              routeName="dashboard.ejecutivo"          
            />
       
       </CardBox>
      </div>
    </div>
  </LayoutDashboard>
</template>
