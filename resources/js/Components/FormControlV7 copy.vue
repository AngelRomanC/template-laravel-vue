<script setup>
import { defineProps, computed, defineEmits } from 'vue';

// Definir los props
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
});

// Definir los eventos que el componente emitirá
const emit = defineEmits(['update:modelValue']);

const selectedValues = computed({
  get() {
    return props.modelValue;
  },
  set(value) {
    // Emitir el valor actualizado hacia el padre
    emit('update:modelValue', value);
  },
});
</script>

<template>
  <div class="space-y-2">
    <div
      v-for="option in options"
      :key="option[valueKey]"
      class="flex items-center"
    >
      <input
        type="checkbox"
        :id="option[valueKey]"
        :value="option[valueKey]"
        v-model="selectedValues"
        class="mr-2"
      />
      <label :for="option[valueKey]">{{ option[labelKey] }}</label>
    </div>
  </div>
</template>
