<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Buscar...',
  },
  debounce: {
    type: Number,
    default: 300,
  },
  routeName: {
    type: String,
    required: true, // Asegura que se pase
  },
})

const emit = defineEmits(['update:modelValue'])

const search = ref(props.modelValue)
watch(() => props.modelValue, (value) => {
  search.value = value
})

let timeout = null

function buscar() {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    emit('update:modelValue', search.value)
    router.get(route(`${props.routeName}index`), { search: search.value }, {
      preserveState: true,
      replace: true,
    })
  }, props.debounce)
}

function limpiar() {
  search.value = ''
  emit('update:modelValue', '')
  router.get(route(`${props.routeName}index`), {}, {
    preserveState: false,
    replace: true,
  })
}
</script>

<template>
  <div class="flex items-center gap-2 mb-4">
    <input
      v-model="search"
      @input="buscar"
      :placeholder="placeholder"
      type="text"
      class="border p-2 rounded w-full dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
    />
    <button 
      @click="buscar" 
      class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800"
    >
      Buscar
    </button>
    <button 
      @click="limpiar" 
      class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500"
    >
      Limpiar
    </button>
  </div>
</template>
