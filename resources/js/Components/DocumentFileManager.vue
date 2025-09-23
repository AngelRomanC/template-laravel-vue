<script setup>
import { mdiEye, mdiDownload, mdiTrashCanOutline, mdiFilePdfBox } from '@mdi/js';
import Swal from 'sweetalert2';

const props = defineProps({
  label: String,
  files: {
    type: Array,
    default: () => []
  },
  type: {
    type: String,
    required: true,
    validator: value => ['principal', 'anexo'].includes(value)
  },
  canEdit: {
    type: Boolean,
    default: true
  },
  error: String
});

const emit = defineEmits(['addFiles', 'deleteFile']);

const showPdf = (filePath) => {
  if (!filePath) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'El archivo no está disponible'
    });
    return;
  }

  const fullPath = filePath.startsWith('/storage/') ? filePath : `/storage/${filePath}`;
  
  Swal.fire({
    html: `
      <div style="width: 100%; height: 70vh;">
        <iframe src="${fullPath}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
      </div>
    `,
    width: '80%',
    showConfirmButton: false,
    showCloseButton: true
  });
};

const downloadFile = (filePath) => {
  const fullPath = filePath.startsWith('/storage/') ? filePath : `/storage/${filePath}`;
  window.open(fullPath, '_blank');
};

const handleFileChange = (event) => {
  const files = Array.from(event.target.files);
  emit('addFiles', { files, type: props.type });
  event.target.value = ''; // Reset input
};

const confirmDelete = (fileIndex) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás revertir esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      emit('deleteFile', { fileIndex, type: props.type });
    }
  });
};
</script>

<template>
  <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
    
    <!-- Lista de archivos existentes -->
    <div v-if="files.length > 0" class="space-y-2 mb-4">
      <div v-for="(file, index) in files" :key="index" class="flex items-center justify-between p-2 border rounded hover:bg-gray-50">
        <div class="flex items-center">
          <svg :path="mdiFilePdfBox" class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20" />
          <span class="text-sm truncate">{{ typeof file === 'string' ? file.split('/').pop() : file.name }}</span>
        </div>
        <div class="flex space-x-2">
          <button 
            type="button" 
            @click="showPdf(typeof file === 'string' ? file : file.path)"
            class="text-blue-500 hover:text-blue-700 p-1"
            title="Ver"
          >
            <svg :path="mdiEye" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" />
          </button>
          <button 
            type="button" 
            @click="downloadFile(typeof file === 'string' ? file : file.path)"
            class="text-green-500 hover:text-green-700 p-1"
            title="Descargar"
          >
            <svg :path="mdiDownload" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" />
          </button>
          <button 
            v-if="canEdit"
            type="button" 
            @click="confirmDelete(index)"
            class="text-red-500 hover:text-red-700 p-1"
            title="Eliminar"
          >
            <svg :path="mdiTrashCanOutline" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" />
          </button>
        </div>
      </div>
    </div>
    <div v-else class="text-sm text-gray-500 mb-2">No hay documentos {{ type === 'principal' ? 'principales' : 'anexos' }}</div>
    
    <!-- Input para agregar nuevos archivos -->
    <div v-if="canEdit" class="flex items-center">
      <label class="cursor-pointer inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <span class="flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
          </svg>
          Agregar
        </span>
        <input 
          type="file" 
          @change="handleFileChange" 
          accept="application/pdf" 
          :multiple="true"
          class="hidden"
        >
      </label>
    </div>
    
    <div v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</div>
  </div>
</template>