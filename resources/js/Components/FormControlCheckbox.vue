<script setup>
import { defineProps, defineEmits, computed } from "vue";

const props = defineProps({
  modelValue: {
    type: [Boolean, Array], // ✅ soporta boolean y array
    required: true,
  },
  value: {
    type: [String, Number, Boolean],
    default: true,
  },
  label: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const isChecked = computed(() => {
  if (Array.isArray(props.modelValue)) {
    return props.modelValue.includes(props.value);
  }
  return props.modelValue === true;
});

const toggle = () => {
  if (Array.isArray(props.modelValue)) {
    let newValue = [...props.modelValue];
    const index = newValue.indexOf(props.value);
    if (index === -1) {
      newValue.push(props.value);
    } else {
      newValue.splice(index, 1);
    }
    emit("update:modelValue", newValue);
  } else {
    emit("update:modelValue", !props.modelValue);
  }
};
</script>

<template>
  <label class="inline-flex items-center space-x-2 cursor-pointer">
    <input
      type="checkbox"
      :checked="isChecked"
      @change="toggle"
      class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
    />
    <span>{{ label }}</span>
  </label>
</template>
