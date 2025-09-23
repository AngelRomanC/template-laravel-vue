<script setup>
import { Head } from "@inertiajs/vue3";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";
import { mdiArrowRight, mdiCodeBraces } from "@mdi/js";
import { computed } from 'vue'; // <-- Añade esta importación
const props = defineProps({
  sistemasAsignados: Number,
  sistemasEnDesarrollo: Number,
  sistemasAsignadosLista: Array
});

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('es-ES', options);
};
// Calculamos sistemas en producción basado en la lista
const sistemasEnProduccion = computed(() => {
  return props.sistemasAsignadosLista.filter(
    sistema => sistema.estatus === 'Producción'
  ).length;
});

const calcularProgreso = () => {
  if (props.sistemasAsignados === 0) return 0;

  // Ponderación:
  // Producción = 100%
  // Pruebas = 75%
  // Desarrollo = 25%
  let puntajeTotal = 0;

  props.sistemasAsignadosLista.forEach(sistema => {
    switch (sistema.estatus) {
      case 'Producción':
        puntajeTotal += 100;
        break;
      case 'Pruebas':
        puntajeTotal += 75;
        break;
      case 'Desarrollo':
        puntajeTotal += 25;
        break;
      default:
        puntajeTotal += 0;
    }
  });

  return Math.round(puntajeTotal / props.sistemasAsignados);
};
</script>


<template>

  <Head title="Dashboard Desarrollador" />
  <LayoutDashboard>
    <SectionMain>
      <SectionTitleLineWithButton title="Mi Panel de Desarrollo" main class="mb-6" :icon="mdiCodeBraces">
        <template #action>
          <BaseButton label="Nuevo Sistema" color="info" small route="sistema.create" />
        </template>
      </SectionTitleLineWithButton>
    </SectionMain>

    <!-- Tarjetas de Métricas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Tarjeta Sistemas Asignados -->
      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-indigo-100 p-3 rounded-full mr-4">
            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Sistemas Asignados</h3>
            <p class="text-2xl font-semibold text-gray-900">{{ sistemasAsignados }}</p>
            <p class="text-xs text-gray-500 mt-1">Total proyectos</p>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta En Desarrollo -->
      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-blue-100 p-3 rounded-full mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">En Desarrollo</h3>
            <p class="text-2xl font-semibold text-blue-600">{{ sistemasEnDesarrollo }}</p>
            <p class="text-xs text-gray-500 mt-1">Activos</p>
          </div>
        </div>
      </CardBox>

      <!-- Tarjeta Progreso -->
      <CardBox class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center">
          <div class="bg-green-100 p-3 rounded-full mr-4">
            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Progreso de Sistemas</h3>
            <p class="text-2xl font-semibold text-green-600">
              {{ calcularProgreso() }}%
            </p>
            <p class="text-xs text-gray-500 mt-1">
              {{ sistemasEnProduccion }} en producción
            </p>
          </div>
        </div>
      </CardBox>
    </div>

    <!-- Listado de Sistemas -->
    <CardBox class="overflow-hidden shadow-sm">
      <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
            Mis Sistemas Recientes
          </h2>
          <BaseButton label="Ver todos" :icon="mdiArrowRight" color="info" small outline routeName="sistema.index" />
        </div>
      </div>
      <div class="divide-y divide-gray-100">
        <div v-for="sistema in sistemasAsignadosLista" :key="sistema.id"
          class="p-4 hover:bg-gray-50 transition-colors duration-150">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                  <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <div>
                <h3 class="font-medium text-gray-900">{{ sistema.nombre }}</h3>
                <div class="flex items-center mt-1">
                  <span class="text-sm text-gray-500">{{ sistema.departamento?.nombre }}</span>
                  <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                    'bg-green-100 text-green-800': sistema.estatus === 'Producción',
                    'bg-yellow-100 text-yellow-800': sistema.estatus === 'Pruebas',
                    'bg-blue-100 text-blue-800': sistema.estatus === 'Desarrollo',
                    'bg-red-100 text-red-800': sistema.estatus === 'Inactivo'
                  }">
                    {{ sistema.estatus }}
                  </span>
                </div>
              </div>
            </div>
            <div class="flex items-center">
              <span class="text-sm text-gray-500 mr-3">
                {{ formatDate(sistema.updated_at) }}
              </span>
              <BaseButton :icon="mdiArrowRight" color="info" small outline :href="route('sistema.edit', sistema.id)" />
            </div>
          </div>
        </div>
      </div>
    </CardBox>
  </LayoutDashboard>
</template>
