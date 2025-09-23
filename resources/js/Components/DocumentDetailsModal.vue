<template>
  <div>
    <!-- Botón para abrir el modal -->
    <BaseButton
      @click="openModal"
      :icon="mdiEye"
      color="lightDark"
      class="!p-1.5 !rounded-full hover:bg-gray-200 transition-colors"
      title="Ver detalles del documento"
    />

    <!-- Modal de detalles -->
    <Modal :show="isOpen" @close="closeModal" max-width="2xl">
      <div class="p-6 text-left space-y-6">
        <!-- Header con icono -->
        <div class="flex items-start space-x-4">
          <div class="bg-blue-100 p-3 rounded-full flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ documento.tipo_de_documento.nombre_documento }}</h2>
            <p class="text-sm text-gray-500">{{ documento.departamento.nombre_departamento }}</p>
          </div>
        </div>

        <!-- Tarjetas de información -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Tarjeta de Revalidación -->
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center space-x-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <div class="w-full">
                <p class="text-sm font-medium text-gray-500">Revalidación</p>
                <p class="text-gray-800 font-medium">{{ formatDate(documento.fecha_revalidacion) }}</p>
                
                <!-- Estado de Revalidación -->
                <div class="mt-2 flex items-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="revalidationStatusClass">
                    {{ revalidationStatusText }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tarjeta de Vigencia -->
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center space-x-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <div class="w-full">
                <p class="text-sm font-medium text-gray-500">Vigencia</p>
                <p class="text-gray-800 font-medium">{{ formatDate(documento.fecha_vigencia) }}</p>
                
                <!-- Estado de Vigencia -->
                <div class="mt-2 flex items-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="expirationStatusClass">
                    {{ expirationStatusText }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Archivos -->
        <div class="border-t border-gray-200 pt-4">
          <div class="flex justify-between items-center mb-3">
            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Visualizar documentos</h4>
            <button 
              v-if="todosArchivos.length > 0"
              @click="downloadAll"
              class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              Descargar todos
            </button>
          </div>

          <!-- Documentos Principales -->
          <div class="mb-4">
            <h5 class="text-sm font-medium text-gray-700 mb-2">Documentos Principales</h5>
            <div class="flex flex-wrap gap-2">
              <button 
                v-for="(archivo, index) in archivosPrincipales" 
                :key="`principal-${index}`"
                @click="showFileInModal(archivo.ruta_archivo, archivo)"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ archivo.nombre_original || `Principal ${archivosPrincipales.length > 1 ? index + 1 : ''}` }}
              </button>
            </div>
          </div>

          <!-- Documentos Anexos -->
          <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Documentos Anexos</h5>
            <div class="flex flex-wrap gap-2">
              <button 
                v-for="(archivo, index) in archivosAnexos" 
                :key="`anexo-${index}`"
                @click="showFileInModal(archivo.ruta_archivo, archivo)"
                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ archivo.nombre_original || `Anexo ${archivosAnexos.length > 1 ? index + 1 : ''}` }}
              </button>
            </div>
          </div>
        </div>

        <!-- Visor de PDF -->
        <div v-if="showPdfViewer" class="mt-4 rounded-lg overflow-hidden border border-gray-300 shadow-lg">
          <div class="bg-gray-800 px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <div>
                <span class="text-sm font-medium text-gray-200">Vista previa del documento</span>
                <p class="text-xs text-gray-400 truncate max-w-xs">{{ currentFileName }}</p>
              </div>
            </div>
            <button @click="closePdfViewer" class="text-gray-300 hover:text-white focus:outline-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
          <iframe :src="pdfUrl" class="w-full h-[500px]" frameborder="0"></iframe>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { mdiEye } from '@mdi/js';
import BaseButton from '@/Components/BaseButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
  documento: {
    type: Object,
    required: true
  },
  tipoDocumento: {
    type: String,
    default: 'documento'
  }
});

const isOpen = ref(false);
const showPdfViewer = ref(false);
const pdfUrl = ref('');
const currentFile = ref(null);

const archivosPrincipales = computed(() => 
  props.documento.archivos?.filter(a => a.tipo === 'principal') || []
);

const archivosAnexos = computed(() => 
  props.documento.archivos?.filter(a => a.tipo === 'anexo') || []
);

const todosArchivos = computed(() => 
  [...archivosPrincipales.value, ...archivosAnexos.value]
);

const currentFileName = computed(() => currentFile.value?.nombre_original || 'Documento');

const revalidationStatusClass = computed(() => {
  if (props.documento.dias_restantes_revalidacion <= 0) return 'bg-red-500 text-white';
  if (props.documento.dias_restantes_revalidacion <= 7) return 'bg-red-200 text-red-800';
  return 'bg-green-100 text-green-800';
});

const revalidationStatusText = computed(() => 
  props.documento.dias_restantes_revalidacion <= 0 
    ? 'Vencido' 
    : `${props.documento.dias_restantes_revalidacion} días restantes`
);

const expirationStatusClass = computed(() => {
  if (props.documento.dias_restantes <= 0) return 'bg-red-500 text-white';
  if (props.documento.dias_restantes <= 7) return 'bg-red-200 text-red-800';
  return 'bg-green-100 text-green-800';
});

const expirationStatusText = computed(() => 
  props.documento.dias_restantes <= 0 
    ? 'Vencido' 
    : `${props.documento.dias_restantes} días restantes`
);

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-MX');
};

const openModal = () => {
  isOpen.value = true;
};

const closeModal = () => {
  isOpen.value = false;
  closePdfViewer();
};

const showFileInModal = (filePath, file) => {
  pdfUrl.value = `/storage/${filePath}`;
  currentFile.value = file;
  showPdfViewer.value = true;
};

const closePdfViewer = () => {
  pdfUrl.value = '';
  currentFile.value = null;
  showPdfViewer.value = false;
};

const downloadAll = () => {
  window.location.href = `/${props.tipoDocumento}/${props.documento.id}/descargar-todos`;
};
</script>