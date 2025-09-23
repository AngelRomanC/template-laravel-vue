<template>
  <div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <input 
      type="file" 
      ref="fileInput"
      class="hidden"
      :accept="accept"
      :multiple="multiple"
      @change="handleFileChange"
    >
    <button
      type="button"
      @click="$refs.fileInput.click()"
      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
    >
      Seleccionar Archivos
    </button>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['files-selected']);

const props = defineProps({
  label: String,
  error: String,
  accept: {
    type: String,
    default: 'application/pdf'
  },
  multiple: {
    type: Boolean,
    default: true
  }
});

const handleFileChange = (event) => {
  const files = Array.from(event.target.files);
  emit('files-selected', files);
  event.target.value = ''; // Reset input
};
</script>