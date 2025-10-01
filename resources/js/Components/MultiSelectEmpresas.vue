<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import FormControlIcon from '@/Components/FormControlIcon.vue';

const props = defineProps({
  empresas: {
    type: Array,
    required: true,
    validator: (value) => {
      return value.every(empresa => empresa.id && empresa.name);
    }
  },
  modelValue: {
    type: Array,
    default: () => [],
    validator: (value) => Array.isArray(value)
  },
  icon: {
    type: String,
    default: null
  },
  disabled: {
    type: Boolean,
    default: false
  },
  placeholder: {
    type: String,
    default: 'Seleccione empresas...'
  },
  noOptionsText: {
    type: String,
    default: 'No hay empresas disponibles'
  },
  searchPlaceholder: {
    type: String,
    default: 'Buscar empresa...'
  }
});

const emit = defineEmits(['update:modelValue']);

// Estado del componente
const isOpen = ref(false);
const searchTerm = ref('');
const dropdownRef = ref(null);

// Empresas filtradas por búsqueda
const filteredEmpresas = computed(() => {
  if (!searchTerm.value) return props.empresas;
  
  return props.empresas.filter(empresa =>
    empresa.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
    (empresa.rfc && empresa.rfc.toLowerCase().includes(searchTerm.value.toLowerCase()))
  );
});

// Manejar selección/deselección
const toggleEmpresa = (empresaId) => {
  if (props.disabled) return;
  
  const newValue = [...props.modelValue];
  const index = newValue.indexOf(empresaId);

  if (index === -1) {
    newValue.push(empresaId);
  } else {
    newValue.splice(index, 1);
  }

  emit('update:modelValue', newValue);
};

// Nombres de empresas seleccionadas para mostrar
const selectedEmpresasNames = computed(() => {
  if (props.modelValue.length === 0) return props.placeholder;
  
  return props.empresas
    .filter(empresa => props.modelValue.includes(empresa.id))
    .map(empresa => empresa.name)
    .join(', ');
});

// Cerrar dropdown al hacer clic fuera
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false;
  }
};

// Event listeners
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Clases CSS computadas
const controlIconH = computed(() => 'h-12');
const inputElClass = computed(() => {
  const base = [
    'px-3 py-2 max-w-full focus:ring focus:outline-none border-gray-700 rounded w-full',
    'dark:placeholder-gray-400 h-12',
    'bg-white dark:bg-slate-800 border',
    props.disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
    'text-left flex items-center justify-between'
  ];

  if (props.icon) {
    base.push('pl-10');
  }

  return base.join(' ');
});

// Limpiar búsqueda al cerrar
watch(isOpen, (val) => {
  if (!val) searchTerm.value = '';
});
</script>

<template>
  <div class="relative" ref="dropdownRef">
    <!-- Campo de visualización -->
    <div
      @click="!disabled && (isOpen = !isOpen)"
      :class="inputElClass"
      :title="selectedEmpresasNames"
    >
      <span class="block truncate flex-1">
        {{ selectedEmpresasNames }}
      </span>
      <svg
        class="h-5 w-5 text-gray-400"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        aria-hidden="true"
      >
        <path
          fill-rule="evenodd"
          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
      <FormControlIcon v-if="icon" :icon="icon" :h="controlIconH" />
    </div>

    <!-- Dropdown con opciones -->
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="isOpen"
        class="absolute z-50 mt-1 w-full rounded-md bg-white dark:bg-slate-800 shadow-lg border border-gray-300 dark:border-gray-600"
      >
        <!-- Buscador -->
        <div class="p-2 border-b border-gray-200 dark:border-gray-600">
          <input
            v-model="searchTerm"
            type="text"
            :placeholder="searchPlaceholder"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white p-2"
            @click.stop
          />
        </div>

        <!-- Lista de empresas -->
        <div class="max-h-60 overflow-y-auto">
          <template v-if="filteredEmpresas.length > 0">
            <div
              v-for="empresa in filteredEmpresas"
              :key="empresa.id"
              @click.stop="toggleEmpresa(empresa.id)"
              class="cursor-pointer hover:bg-indigo-50 dark:hover:bg-slate-700 p-2 flex items-center"
            >
              <input
                type="checkbox"
                :checked="modelValue.includes(empresa.id)"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                readonly
              />
              <div class="ml-2 dark:text-white">
                <div class="font-medium">{{ empresa.name }}</div>
                <div v-if="empresa.rfc" class="text-xs text-gray-500 dark:text-gray-400">
                  {{ empresa.rfc }}
                </div>
              </div>
            </div>
          </template>
          <div v-else class="p-3 text-center text-gray-500 dark:text-gray-400">
            {{ noOptionsText }}
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
/* Estilos para scrollbar en modo oscuro */
.dark ::-webkit-scrollbar {
  width: 8px;
}
.dark ::-webkit-scrollbar-track {
  background: #1e293b;
}
.dark ::-webkit-scrollbar-thumb {
  background: #475569;
  border-radius: 4px;
}
.dark ::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}
</style>