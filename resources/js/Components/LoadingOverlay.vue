<template>
  <div
    v-if="visible"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
    aria-busy="true"
  >
    <div class="text-center text-white" role="alert">
      <svg
        class="animate-spin h-10 w-10 mb-4 mx-auto text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 000 16z"
        ></path>
      </svg>
      <p class="text-lg font-semibold">{{ title }}</p>
      <p class="text-sm">{{ subtitle }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  visible: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: 'Procesando...'
  },
  subtitle: {
    type: String,
    default: 'Por favor, no cierres esta ventana.'
  }
});
</script>
