<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import BaseButton from '@/Components/BaseButton.vue';
import CardBox from '@/Components/CardBox.vue';
import { mdiAccountKey, mdiCheckCircle, mdiCloseCircle } from '@mdi/js';
import BaseIcon from '@/Components/BaseIcon.vue';

const props = defineProps({
  show: Boolean,
  autoClose: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['close', 'role-changed']);

const userRoles = ref([]);
const selectedRole = ref(null);
const isLoading = ref(false);
const errorMessage = ref('');

// Computed para el rol activo actual
const currentActiveRole = computed(() => {
  return usePage().props.auth.active_role;
});

onMounted(() => {
  initializeRoles();
});

// Observar cambios en la prop show
watch(() => props.show, (newVal) => {
  if (newVal) {
    initializeRoles();
    errorMessage.value = '';
  }
});

const initializeRoles = () => {
  const pageProps = usePage().props;
  const roles = pageProps.auth.roles || {};

  // userRoles.value = Object.keys(roles).map(role => ({
  //   name: role,
  //   active: roles[role],
  //   description: role.description 
  // }));
  
  userRoles.value = Object.keys(roles).map(roleName => {
    const roleData = roles[roleName]; // ← Ahora tendrá description

    return {
      name: roleName,
      active: roleData.active,
      description: roleData.description // ← Esto ya no será undefined
    };
  });
  console.log(userRoles.value);

  // Establecer rol seleccionado: primero sessionStorage, luego el activo, luego el primero disponible
  const sessionRole = sessionStorage.getItem('activeRole');
  const activeRole = pageProps.auth.active_role;

  selectedRole.value = sessionRole || activeRole || (userRoles.value[0]?.name || null);
};

const getRoleDescription = (role) => {
  const descriptions = {
    'Admin': 'Acceso completo al sistema',
    'Desarrollador': 'Acceso a desarrollo y testing',
    'Procesos': 'Acceso básico al sistema',
    'Supervisor': 'Acceso a supervisión de equipos'
  };
  return descriptions[role] || `Rol de ${role}`;
};

const selectRole = async (role) => {
  if (!role || isLoading.value) return;

  isLoading.value = true;
  errorMessage.value = '';

  try {
    // Verificar que el usuario tenga el rol seleccionado
    const userHasRole = userRoles.value.some(r => r.name === role && r.active);
    if (!userHasRole) {
      throw new Error('No tienes permisos para usar este rol');
    }

    // Guardar en sessionStorage
    sessionStorage.setItem('activeRole', role);

    // Enviar petición al servidor
    await router.post(route('user.set-active-role'), {
      role: role
    }, {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        emit('role-changed', role);

        if (props.autoClose) {
          emit('close');
        }

        // Mostrar feedback visual sin recargar toda la página
        setTimeout(() => {
          // Actualizar solo los datos necesarios
          if (typeof window.updateUserRole === 'function') {
            window.updateUserRole(role);
          }
        }, 100);
      },
      onError: (errors) => {
        errorMessage.value = errors.role?.[0] || 'Error al cambiar de rol';
        sessionStorage.removeItem('activeRole');
      },
      onFinish: () => {
        isLoading.value = false;
      }
    });

  } catch (error) {
    errorMessage.value = error.message;
    isLoading.value = false;
  }
};

const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    emit('close');
  }
};

// Cerrar modal al hacer clic fuera
const closeOnBackdrop = (event) => {
  if (event.target === event.currentTarget) {
    emit('close');
  }
};
</script>

<template>
  <div v-if="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300"
    @click="closeOnBackdrop" @keydown="handleKeydown">
    <CardBox class="w-full max-w-md mx-4 transform transition-transform duration-300 scale-100"
      :class="{ 'scale-95': isLoading }">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6 pb-4 border-b dark:border-gray-700">
        <div class="flex items-center">
          <BaseIcon :path="mdiAccountKey" class="mr-2 text-blue-600" size="20" />
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Seleccionar Rol</h2>
        </div>
        <button @click="$emit('close')"
          class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800"
          :disabled="isLoading">
          <BaseIcon :path="mdiCloseCircle" size="24" />
        </button>
      </div>

      <!-- Mensaje de error -->
      <div v-if="errorMessage"
        class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
        <BaseIcon :path="mdiCloseCircle" class="mr-2 text-red-600" size="20" />
        <span class="text-sm">{{ errorMessage }}</span>
      </div>

      <!-- Instrucciones -->
      <p class="mb-4 text-gray-600 dark:text-gray-300 text-sm">
        Selecciona el rol con el que deseas trabajar en esta sesión:
      </p>

      <!-- Lista de roles -->
      <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
        <div v-for="role in userRoles" :key="role.name" @click="!isLoading && selectRole(role.name)"
          class="p-4 border rounded-lg cursor-pointer transition-all duration-200 flex items-start group" :class="{
            'border-blue-500 bg-blue-50 dark:bg-blue-900/20 shadow-md': selectedRole === role.name,
            'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800': selectedRole !== role.name && !isLoading,
            'opacity-50 cursor-not-allowed': isLoading,
            'border-green-500 bg-green-50 dark:bg-green-900/20': role.name === currentActiveRole
          }">
          <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 mr-3 mt-1">
            <BaseIcon :path="mdiAccountKey" class="text-blue-600 dark:text-blue-300" size="16" />
          </div>

          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-1">
              <span class="font-semibold text-gray-900 dark:text-white truncate">
                {{ role.name }}
                <span v-if="role.name === currentActiveRole"
                  class="ml-2 px-2 py-1 text-xs bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 rounded-full">
                  Actual
                </span>
              </span>

              <BaseIcon v-if="selectedRole === role.name" :path="mdiCheckCircle"
                class="text-blue-600 dark:text-blue-300 ml-2 flex-shrink-0" size="20" />
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
              {{ role.description }}
            </p>
          </div>
        </div>

        <div v-if="userRoles.length === 0" class="text-center py-4 text-gray-500">
          No hay roles disponibles
        </div>
      </div>

      <!-- Footer -->
      <div class="flex justify-between items-center pt-4 border-t dark:border-gray-700">
        <span class="text-sm text-gray-500 dark:text-gray-400">
          {{ currentActiveRole ? `Rol actual: ${currentActiveRole}` : 'Sin rol activo' }}
        </span>

        <div class="flex space-x-2">
          <BaseButton color="gray" label="Cancelar" :disabled="isLoading" @click="$emit('close')" />
          <BaseButton color="info" :label="isLoading ? 'Cambiando...' : 'Confirmar'"
            :disabled="!selectedRole || isLoading" :loading="isLoading" @click="selectRole(selectedRole)" />
        </div>
      </div>
    </CardBox>
  </div>
</template>

<style scoped>
/* Animaciones suaves */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

.dark ::-webkit-scrollbar-track {
  background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
  background: #6b7280;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>