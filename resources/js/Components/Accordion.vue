<script setup>
import { ref } from 'vue'
const props = defineProps({
  sections: {
    type: Array,
    required: true
  }
})

const openSections = ref([props.sections[0]?.title || ""]) // Abre el primero por defecto

const toggleSection = (title) => {
  if (openSections.value.includes(title)) {
    openSections.value = openSections.value.filter(s => s !== title)
  } else {
    openSections.value.push(title)
  }
}
</script>

<template>
  <div>
    <div
      v-for="section in sections"
      :key="section.title"
      class="border rounded-lg overflow-hidden mb-2"
    >
      <button
        class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 hover:bg-gray-200 transition"
        @click="toggleSection(section.title)"
        type="button"
      >
        <span class="font-semibold">{{ section.title }}</span>
        <svg v-if="openSections.includes(section.title)" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <transition name="fade">
        <div v-show="openSections.includes(section.title)" class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <slot :name="section.slotName" />
        </div>
      </transition>
    </div>
  </div>
</template>

<style>
.fade-enter-active, .fade-leave-active { transition: all 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; max-height: 0; }
</style>
