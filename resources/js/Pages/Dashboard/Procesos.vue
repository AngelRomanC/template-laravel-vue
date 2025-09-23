<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";
import moment from "moment";
import PaginationDashboard from '@/Shared/PaginationDashboard.vue';

import { 
  mdiMonitorDashboard, 
  mdiPlus, 
  mdiArrowRight, 
  mdiFileDocument,
} from "@mdi/js";
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  totalProcesos: Number,
  procesosPorVencer: Number,
  procesosPorEstatus: Object,
  procesosPaginated: Object,
  
  totalCertificaciones: Number,
  certificacionesPorVencer: Number,
  certificacionesPorEstatus: Object,
  certificacionesPaginated: Object,
  
  actividadesRecientes: Array
});

</script>
<template>
  <Head title="Dashboard Procesos" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton 
        title="Panel de Procesos y Certificaciones" 
        main 
        class="mb-6"  
        :icon="mdiMonitorDashboard"
      >
        <div class="flex space-x-2">
          <BaseButton 
            label="Nuevo Proceso" 
            :icon="mdiPlus" 
            color="info" 
            small 
            routeName="procesos.create" 
          />
          <BaseButton 
            label="Nueva Certificación" 
            :icon="mdiPlus" 
            color="success" 
            small 
            routeName="certificacion.create" 
          />
        </div>
      </SectionTitleLineWithButton>
    </SectionMain>
 <!-- Tarjetas de Métricas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Tarjeta Procesos -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-indigo-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Procesos</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ totalProcesos }}</p>
            <p class="text-xs text-gray-500 mt-1">
              <span class="text-red-500">{{ procesosPorVencer }} por vencer</span>
            </p>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Certificaciones -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-green-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Certificaciones</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ totalCertificaciones }}</p>
            <p class="text-xs text-gray-500 mt-1">
              <span class="text-red-500">{{ certificacionesPorVencer }} por vencer</span>
            </p>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Estados Procesos -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-blue-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
              <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Estados Procesos</h3>
            <div class="flex space-x-4 mt-2">
              <div>
                <p class="text-lg font-bold text-blue-600">{{ procesosPorEstatus?.Diseño || 0 }}</p>
                <p class="text-xs text-gray-500">Diseño</p>
              </div>
              <div>
                <p class="text-lg font-bold text-yellow-600">{{ procesosPorEstatus?.Revisión || 0 }}</p>
                <p class="text-xs text-gray-500">Revisión</p>
              </div>
              <div>
                <p class="text-lg font-bold text-purple-600">{{ procesosPorEstatus?.Validación || 0 }}</p>
                <p class="text-xs text-gray-500">Validación</p>
              </div>
            </div>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Estados Certificaciones -->
      <CardBox class="hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-start">
          <div class="bg-teal-100 p-3 rounded-full mr-4">
            <svg class="w-8 h-8 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Estados Certificaciones</h3>
            <div class="flex space-x-4 mt-2">
              <div>
                <p class="text-lg font-bold text-green-600">{{ certificacionesPorEstatus?.Diseño || 0 }}</p>
                <p class="text-xs text-gray-500">Diseño</p>
              </div>
              <div>
                <p class="text-lg font-bold text-yellow-600">{{ certificacionesPorEstatus?.Revisión || 0 }}</p>
                <p class="text-xs text-gray-500">Revisión</p>
              </div>
              <div>
                <p class="text-lg font-bold text-blue-600">{{ certificacionesPorEstatus?.Validación || 0 }}</p>
                <p class="text-xs text-gray-500">Validación</p>
              </div>
            </div>
          </div>
        </div>
      </CardBox>
    </div>

    <!-- Contenedor de dos columnas para las tablas -->
  <!-- Contenedor de dos columnas para las tablas -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
  <!-- Columna 1: Procesos -->
  <div class="space-y-6">
    <SectionTitleLineWithButton 
      :icon="mdiFileDocument" 
      title="Procesos Recientes" 
    />

    <CardBox class="overflow-y-auto max-h-[600px]">
      <div class="overflow-x-auto flex-grow">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-700">
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Depto</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estatus</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vencimiento</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in procesosPaginated.data" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td data-label="Nombre" class="px-4 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate max-w-[150px]">{{ item.nombre }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]">{{ item.descripcion.substring(0, 30) }}...</div>
              </td>
              <td data-label="Departamento" class="px-4 py-3 whitespace-nowrap">
                <div class="text-xs text-gray-900 dark:text-gray-300">{{ item.departamento?.nombre }}</div>
              </td>
              <td data-label="Estatus" class="px-4 py-3 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': item.estatus === 'Validación',
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': item.estatus === 'Revisión',
                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': item.estatus === 'Diseño',
                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': item.estatus === 'Inactivo'
                      }">
                  {{ item.estatus }}
                </span>
              </td>
              <td data-label="Vencimiento" class="px-4 py-3 whitespace-nowrap text-xs" 
                  :class="new Date(item.fecha_fin_vigencia) < new Date() ? 'text-red-600 dark:text-red-400 font-bold' : 'text-gray-500 dark:text-gray-400'">
                {{ moment(item.fecha_fin_vigencia).format("DD-MM-YYYY") }}
                <div v-if="new Date(item.fecha_fin_vigencia) < new Date()" class="text-xxs">VENCIDO</div>
                <div v-else class="text-xxs">
                  {{ Math.ceil((new Date(item.fecha_fin_vigencia) - new Date()) / (1000 * 60 * 60 * 24)) }} días
                </div>
              </td>
              <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                <BaseButton 
                  label="Ver" 
                  color="info" 
                  x-small 
                  outline 
                  :icon="mdiArrowRight"
                  :href="route('procesos.edit', item.id)"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <PaginationDashboard 
        :currentPage="procesosPaginated.current_page" 
        :links="procesosPaginated.links"
        :total="procesosPaginated.last_page" 
        pageParam="procesos_page"
      />
    </CardBox>
  </div>

  <!-- Columna 2: Certificaciones -->
  <div class="space-y-6">
    <SectionTitleLineWithButton 
      :icon="mdiFileDocument" 
      title="Certificaciones Recientes" 
    />

    <CardBox class="overflow-y-auto max-h-[600px]">
      <div class="overflow-x-auto flex-grow">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-700">
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Depto</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estatus</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vencimiento</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="item in certificacionesPaginated.data" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td data-label="Nombre" class="px-4 py-3 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate max-w-[150px]">{{ item.nombre }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]">{{ item.descripcion.substring(0, 30) }}...</div>
              </td>
              <td data-label="Departamento" class="px-4 py-3 whitespace-nowrap">
                <div class="text-xs text-gray-900 dark:text-gray-300">{{ item.departamento?.nombre }}</div>
              </td>
              <td data-label="Estatus" class="px-4 py-3 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': item.estatus === 'Validación',
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': item.estatus === 'Revisión',
                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': item.estatus === 'Diseño',
                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': item.estatus === 'Inactivo'
                      }">
                  {{ item.estatus }}
                </span>
              </td>
              <td data-label="Vencimiento" class="px-4 py-3 whitespace-nowrap text-xs" 
                  :class="new Date(item.fecha_fin_vigencia) < new Date() ? 'text-red-600 dark:text-red-400 font-bold' : 'text-gray-500 dark:text-gray-400'">
                {{ moment(item.fecha_fin_vigencia).format("DD-MM-YYYY") }}
                <div v-if="new Date(item.fecha_fin_vigencia) < new Date()" class="text-xxs">VENCIDO</div>
                <div v-else class="text-xxs">
                  {{ Math.ceil((new Date(item.fecha_fin_vigencia) - new Date()) / (1000 * 60 * 60 * 24)) }} días
                </div>
              </td>
              <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                <BaseButton 
                  label="Ver" 
                  color="success" 
                  x-small 
                  outline
                  :icon="mdiArrowRight" 
                  :href="route('certificacion.edit', item.id)"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <PaginationDashboard 
        :currentPage="certificacionesPaginated.current_page" 
        :links="certificacionesPaginated.links"
        :total="certificacionesPaginated.last_page" 
        pageParam="certificaciones_page"
      />
    </CardBox>
  </div>
</div>
  </LayoutDashboard>
</template>