<script setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import BaseButton from '@/Components/BaseButton.vue';
import CardBox from '@/Components/CardBox.vue';
import { mdiAccountKey } from '@mdi/js';
import BaseIcon from '@/Components/BaseIcon.vue';

const props = defineProps({
  show: Boolean,
});

const emit = defineEmits(['close']);

const userRoles = ref([]);
const selectedRole = ref(null);

onMounted(() => {
  // Obtener los roles del usuario desde auth.roles
  const roles = usePage().props.auth.roles;
  userRoles.value = Object.keys(roles).map(role => ({
    name: role,
    active: roles[role]
  }));
  
  // Si hay un rol activo en sessionStorage, seleccionarlo
  const activeRole = sessionStorage.getItem('activeRole');
  if (activeRole) {
    selectedRole.value = activeRole;
  }
});

const selectRole = (role) => {
  // Guardar el rol seleccionado en sessionStorage
  sessionStorage.setItem('activeRole', role);
  
  // Enviar una petición al servidor para actualizar el rol activo
  router.post(route('user.set-active-role'), {
    role: role
  }, {
    preserveScroll: true,
    onSuccess: () => {
      // Recargar la página para actualizar el menú
      window.location.reload();
    }
  });
  
  emit('close');
};
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <CardBox class="w-full max-w-md">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Seleccionar Rol</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <p class="mb-4 text-gray-600">Selecciona el rol con el que deseas iniciar sesión:</p>
      
      <div class="space-y-3 mb-6">
        <div 
          v-for="role in userRoles" 
          :key="role.name"
          @click="selectRole(role.name)"
          class="p-3 border rounded-lg cursor-pointer transition-colors flex items-center"
          :class="{
            'border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedRole === role.name,
            'hover:bg-gray-50 dark:hover:bg-gray-800': selectedRole !== role.name
          }"
        >
          <BaseIcon :path="mdiAccountKey" class="mr-3 text-blue-600" />
          <span class="font-medium">{{ role.name }}</span>
        </div>
      </div>
      
      <div class="flex justify-end">
        <BaseButton 
          color="info" 
          label="Confirmar" 
          :disabled="!selectedRole"
          @click="selectRole(selectedRole)"
        />
      </div>
    </CardBox>
  </div>
</template>