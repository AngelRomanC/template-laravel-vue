<script setup>
import { mdiPlus, mdiClose } from "@mdi/js";

const props = defineProps({
  label: String,
  error: String,
  modelValue: {
    type: Array,
    default: () => []
  },
  accept: {
    type: String,
    default: "application/pdf"
  },
  multiple: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['update:modelValue']);

const addFiles = (event) => {
  const files = Array.from(event.target.files);
  emit('update:modelValue', [...props.modelValue, ...files]);
  event.target.value = '';
};

const removeFile = (index) => {
  const newFiles = [...props.modelValue];
  newFiles.splice(index, 1);
  emit('update:modelValue', newFiles);
};

const clearFiles = () => {
  emit('update:modelValue', []);
};
</script>

<template>
  <div class="flex flex-col gap-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    
    <div class="flex gap-2">
      <label class="cursor-pointer inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <span class="flex items-center">
          <svg :path="mdiPlus" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
          </svg>
          Seleccionar
        </span>
        <input 
          type="file" 
          @change="addFiles" 
          :accept="accept" 
          :multiple="multiple" 
          class="hidden"
        >
      </label>
      
      <button 
        type="button" 
        @click="clearFiles" 
        class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
        :disabled="modelValue.length === 0"
      >
        <span class="flex items-center">
          <svg :path="mdiClose" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
          Limpiar
        </span>
      </button>
    </div>
    
    <div v-if="modelValue.length > 0" class="mt-2 border rounded p-2">
      <div v-for="(file, index) in modelValue" :key="index" class="flex items-center justify-between py-1 px-2 hover:bg-gray-50">
        <span class="text-sm truncate">{{ file.name }}</span>
        <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    </div>
    <div v-else class="text-sm text-gray-500 mt-1">No hay documentos seleccionados</div>
    
    <div v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</div>
  </div>
</template>