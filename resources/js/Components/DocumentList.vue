<template>
  <CardBox>
    <div class="p-6 border-b" :class="headerClass">
      <h2 class="text-xl font-semibold text-gray-800 flex items-center">
        <slot name="icon"></slot>
        {{ title }}
      </h2>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="header in headers" :key="header.key" 
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{ header.label }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="documento in paginatedData" :key="documento.id" 
              class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900">
              {{ documento.tipo_de_documento.nombre_documento }}
            </td>
            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">
              {{ documento.departamento.nombre_departamento }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="flex flex-col">
                <div>{{ moment(documento.fecha_revalidacion).format("DD/MM/YYYY") }}</div>
                <span :class="statusClass(documento.dias_restantes_revalidacion)" 
                      class="px-3 py-1 inline-flex justify-center text-xs leading-5 font-semibold rounded-full">
                  {{ statusText(documento.dias_restantes_revalidacion) }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="flex flex-col">
                <div>{{ moment(documento.fecha_vigencia).format("DD/MM/YYYY") }}</div>
                <span :class="statusClass(documento.dias_restantes)" 
                      class="px-3 py-1 inline-flex justify-center text-xs leading-5 font-semibold rounded-full">
                  {{ statusText(documento.dias_restantes) }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <slot name="actions" :documento="documento"></slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="flex justify-between items-center px-6 py-4 border-t border-gray-200">
      <div class="text-sm text-gray-500">
        Mostrando {{ showingFrom }} a {{ showingTo }} de {{ total }} registros
      </div>
      <div class="flex space-x-2">
        <button @click="prevPage" :disabled="currentPage === 1" 
                class="px-3 py-1 rounded border bg-white disabled:opacity-50">
          Anterior
        </button>
        <button v-for="page in visiblePages" :key="page" 
                @click="goToPage(page)"
                :class="{'bg-blue-500 text-white': currentPage === page}"
                class="px-3 py-1 rounded border">
          {{ page }}
        </button>
        <button @click="nextPage" :disabled="currentPage === lastPage" 
                class="px-3 py-1 rounded border bg-white disabled:opacity-50">
          Siguiente
        </button>
      </div>
    </div>
  </CardBox>
</template>

<script setup>
import { computed, ref } from 'vue';
import moment from 'moment';

const props = defineProps({
  title: String,
  documents: {
    type: Array,
    required: true
  },
  itemsPerPage: {
    type: Number,
    default: 5
  },
  headers: {
    type: Array,
    default: () => [
      { key: 'document', label: 'Documento' },
      { key: 'department', label: 'Departamento' },
      { key: 'revalidation', label: 'Revalidación' },
      { key: 'expiration', label: 'Vigencia' },
      { key: 'actions', label: 'Acciones' }
    ]
  },
  headerClass: {
    type: String,
    default: 'bg-gradient-to-r from-indigo-50 to-blue-50'
  }
});

const currentPage = ref(1);

// Computed properties
const total = computed(() => props.documents.length);
const lastPage = computed(() => Math.ceil(total.value / props.itemsPerPage));
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * props.itemsPerPage;
  const end = start + props.itemsPerPage;
  return props.documents.slice(start, end);
});
const showingFrom = computed(() => (currentPage.value - 1) * props.itemsPerPage + 1);
const showingTo = computed(() => {
  const end = currentPage.value * props.itemsPerPage;
  return end > total.value ? total.value : end;
});
const visiblePages = computed(() => {
  const pages = [];
  const maxVisible = 10;
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let end = Math.min(lastPage.value, start + maxVisible - 1);
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1);
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  
  return pages;
});

// Methods
function statusClass(days) {
  if (days <= 0) return 'bg-red-500 text-white';
  if (days <= 7) return 'bg-red-200 text-red-800';
  return 'bg-green-100 text-green-800';
}

function statusText(days) {
  return days <= 0 ? 'Vencido' : `${days} días`;
}

function prevPage() {
  if (currentPage.value > 1) currentPage.value--;
}

function nextPage() {
  if (currentPage.value < lastPage.value) currentPage.value++;
}

function goToPage(page) {
  currentPage.value = page;
}
</script>

<style scoped>
/* Puedes añadir estilos personalizados aquí si es necesario */
</style>