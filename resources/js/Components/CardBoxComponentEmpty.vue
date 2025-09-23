<template>
  <div class="text-center py-24 text-gray-500 dark:text-slate-400">
    <h2 class="text-xl font-semibold mb-2" v-if="title">{{ title }}</h2>
    <p v-if="description">{{ description }}</p>
    <p v-else>Sin resultados...</p>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: '',
  },
  description: {
    type: String,
    default: '',
  },
})
</script>
