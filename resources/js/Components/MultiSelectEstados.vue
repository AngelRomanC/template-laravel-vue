<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import FormControlIcon from '@/components/FormControlIcon.vue';

const props = defineProps({
  estados: {
    type: Array,
    required: true
  },
  modelValue: {
    type: Array,
    default: () => []
  },
  icon: {
    type: String,
    default: null
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']);
const isOpen = ref(false);
const searchTerm = ref('');
const dropdownRef = ref(null);
const ID_NINGUNO = props.estados.find(e => e.name === 'Ninguno')?.id ?? null


const filteredEstados = computed(() => {
  return props.estados.filter(estado =>
    estado.name.toLowerCase().includes(searchTerm.value.toLowerCase())
  );
});

// const toggleEstado = (estadoId) => {
//   if (props.disabled) return;
  
//   const newValue = [...props.modelValue];
//   const index = newValue.indexOf(estadoId);

//   if (index === -1) {
//     newValue.push(estadoId);
//   } else {
//     newValue.splice(index, 1);
//   }

//   emit('update:modelValue', newValue);
// };

const toggleEstado = (estadoId) => {
  if (props.disabled) return;

  const newValue = [...props.modelValue];
  const index = newValue.indexOf(estadoId);

  if (index === -1) {
    newValue.push(estadoId);

    // Si selecciona "Ninguno", eliminar todos los demás
    if (estadoId === ID_NINGUNO) {
      emit('update:modelValue', [ID_NINGUNO]);
      return;
    }

    // Si selecciona otro estado, quitar "Ninguno" si está seleccionado
    if (newValue.includes(ID_NINGUNO)) {
      const filtered = newValue.filter(id => id !== ID_NINGUNO);
      emit('update:modelValue', filtered);
      return;
    }

  } else {
    newValue.splice(index, 1);
  }

  emit('update:modelValue', newValue);
};

// const toggleAll = () => {
//   if (props.disabled) return;
  
//   if (areAllSelected.value) {
//     // Si todos están seleccionados, deseleccionar todos
//     emit('update:modelValue', []);
//   } else {
//     // Si no todos están seleccionados, seleccionar todos
//     emit('update:modelValue', props.estados.map(estado => estado.id));
//   }
// };

const toggleAll = () => {
  if (props.disabled) return;

  // Excluir “Ninguno” si está presente
  const allIdsExceptNinguno = props.estados
    .filter(e => e.id !== ID_NINGUNO)
    .map(e => e.id);

  if (areAllSelected.value) {
    emit('update:modelValue', []);
  } else {
    emit('update:modelValue', allIdsExceptNinguno);
  }
};

// const areAllSelected = computed(() => {
//   return props.estados.length > 0 && props.modelValue.length === props.estados.length;
// });
const areAllSelected = computed(() => {
  const withoutNinguno = props.estados.filter(e => e.id !== ID_NINGUNO);
  return withoutNinguno.every(e => props.modelValue.includes(e.id));
});


const areSomeSelected = computed(() => {
  return props.modelValue.length > 0 && !areAllSelected.value;
});

const selectedEstadosNames = computed(() => {
  if (areAllSelected.value) return 'Todos seleccionados';
  if (props.modelValue.length === 0) return 'Seleccione estados...';
  
  return props.estados
    .filter(estado => props.modelValue.includes(estado.id))
    .map(estado => estado.name)
    .join(', ');
});

// Cerrar el dropdown al hacer clic fuera
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Clases computadas para mantener consistencia con FormControl
const controlIconH = computed(() => 'h-12');
const inputElClass = computed(() => {
  const base = [
    'px-3 py-2 max-w-full focus:ring focus:outline-none border-gray-700 rounded w-full',
    'dark:placeholder-gray-400 h-12',
    'bg-white dark:bg-slate-800 border',
    props.disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
  ];

  if (props.icon) {
    base.push('pl-10');
  }

  return base.join(' ');
});
</script>

<template>
  <div class="relative" ref="dropdownRef">
    <div
      @click="!disabled && (isOpen = !isOpen)"
      :class="inputElClass"
    >
      <span class="block truncate">
        {{ selectedEstadosNames }}
      </span>
      <FormControlIcon v-if="icon" :icon="icon" :h="controlIconH" />
    </div>

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
        class="absolute z-10 mt-1 w-full rounded-md bg-white dark:bg-slate-800 shadow-lg border border-gray-300 dark:border-gray-600"
      >
        <div class="p-2 border-b border-gray-200 dark:border-gray-600">
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Buscar estado..."
            class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-slate-700 dark:text-white"
          />
        </div>
        <div class="max-h-60 overflow-y-auto">
          <!-- Opción "Seleccionar todos" -->
          <div
            @click.stop="toggleAll"
            class="cursor-pointer hover:bg-indigo-50 dark:hover:bg-slate-700 p-2 flex items-center border-b border-gray-200 dark:border-gray-600"
          >
            <input
              type="checkbox"
              :checked="areAllSelected"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              readonly
            />
            <span class="ml-2 dark:text-white font-medium">Seleccionar todos</span>
          </div>
          
          <!-- Lista de estados -->
          <div
            v-for="estado in filteredEstados"
            :key="estado.id"
            @click.stop="toggleEstado(estado.id)"
            class="cursor-pointer hover:bg-indigo-50 dark:hover:bg-slate-700 p-2 flex items-center"
          >
            <input
              type="checkbox"
              :checked="modelValue.includes(estado.id)"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              readonly
            />
            <span class="ml-2 dark:text-white">{{ estado.name }}</span>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>