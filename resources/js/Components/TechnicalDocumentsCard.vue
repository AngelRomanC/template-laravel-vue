<template>
    <CardBox 
      class="hover:shadow-lg transition-shadow duration-300 cursor-pointer" 
      @click="redirectToCreate"
      :title="tooltip"

    >
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Documentos Técnicos</h3>
          <p class="text-2xl font-bold text-indigo-600">{{ count }}</p>
        </div>
      </div>
    </CardBox>
  </template>
  
  <script setup>
  import { router } from '@inertiajs/vue3';
  import CardBox from "@/components/CardBox.vue";

  
  const props = defineProps({
    count: {
      type: Number,
      required: true
    },
    tooltip: {
      type: String,
      default: 'Crear nuevo documento técnico'
    }
  });
  
  // const redirectToCreate = () => {
  //   router.visit(route('documento.create'), {
  //     method: 'get'
  //   });
  // };

const redirectToCreate = () => {
  router.get(route('documento.index', {
    redirect: route('dashboard') // O el nombre correcto de tu ruta de dashboard
  }))
};

  </script>