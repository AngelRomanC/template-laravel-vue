<template>
    <CardBox class="hover:shadow-lg transition-shadow duration-300 cursor-pointer" @click="openModal">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Usuarios Registrados</h3>
          <p class="text-2xl font-bold text-purple-600">{{ users }}</p>
        </div>
      </div>
  
      <!-- Modal -->
      <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeModal">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-start">
              <h3 class="text-lg font-semibold text-gray-800">Detalles de Últimos  Usuarios Registrados</h3>
              <button @click.stop="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <div class="mt-6">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Correo</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Rol</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Registro</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in latestUsers" :key="user.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.role }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(user.created_at) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end">
              <button 
                @click.stop="closeModal" 
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
              >
                Cerrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </CardBox>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import CardBox from "@/components/CardBox.vue";

  
  const props = defineProps({
    users: {
      type: Number,
      required: true
    },
    latestUsers: {
      type: Array,
      required: true
    }
  });
  
  const isModalOpen = ref(false);
  
  const openModal = () => {
    isModalOpen.value = true;
  };
  
  const closeModal = () => {
    isModalOpen.value = false;
  };
  
  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('es-MX', { hour12: true });
  };
  </script>