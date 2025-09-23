<script setup>
import { ref, computed, defineProps, defineEmits, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: [Array, String, Number],
    default: [],
  },
  options: {
    type: Array,
    required: true,
  },
  valueKey: {
    type: String,
    default: 'id',
  },
  labelKey: {
    type: String,
    default: 'nombre_modalidad',
  },
  perPage: {
    type: Number,
    default: 9,
  },
})

const emit = defineEmits(['update:modelValue'])

const currentPage = ref(1)

const totalPages = computed(() =>
  Math.ceil(props.options.length / props.perPage)
)

const paginatedOptions = computed(() => {
  const start = (currentPage.value - 1) * props.perPage
  return props.options.slice(start, start + props.perPage)
})

const selectedValues = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

function nextPage() {
  if (currentPage.value < totalPages.value) currentPage.value++
}

function prevPage() {
  if (currentPage.value > 1) currentPage.value--
}

watch(() => props.options, () => {
  currentPage.value = 1
})
</script>

<template>
  <div>
    <!-- Checkboxes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="option in paginatedOptions"
        :key="option[valueKey]"
        class="flex items-center"
      >
        <input
          type="checkbox"
          :id="'modalidad_' + option[valueKey]"
          :value="option[valueKey]"
          v-model="selectedValues"
          class="mr-2"
        />
        <label :for="'modalidad_' + option[valueKey]">
          {{ option[labelKey] }}
        </label>
      </div>
    </div>

    <!-- Paginación estilo documentos -->
    <div class="pagination-controls mt-4" v-if="totalPages > 1">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="pagination-button"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
          viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg>
      </button>

      <span class="page-indicator">Página {{ currentPage }} de {{ totalPages }}</span>

      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="pagination-button"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
          viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6" />
        </svg>
      </button>
    </div>
  </div>
</template>

<style scoped>
.pagination-controls {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 0.75rem;
}

.pagination-button {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.2s;
  width: 2rem;
  height: 2rem;
}

.pagination-button:hover:not(:disabled) {
  background: #f1f5f9;
  border-color: #cbd5e0;
}

.pagination-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-indicator {
  font-size: 0.875rem;
  color: #4a5568;
}
</style>
