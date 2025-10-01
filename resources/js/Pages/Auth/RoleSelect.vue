<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import BaseButton from '@/Components/BaseButton.vue';
import CardBox from '@/Components/CardBox.vue';
import { mdiAccountKey, mdiCheck, mdiShieldAccount } from '@mdi/js';
import BaseIcon from '@/Components/BaseIcon.vue';
import LayoutGuest from '@/Layouts/LayoutGuest.vue'


const userRoles = ref([]);
const selectedRole = ref(null);
const isLoading = ref(false);

// Obtener el rol actual si existe
const currentActiveRole = computed(() => {
    return usePage().props.auth.active_role;
});

onMounted(() => {
    initializeRoles();
});

const initializeRoles = () => {
    const roles = usePage().props.auth.roles || {};

    // Convertir roles a array con toda la información
    userRoles.value = Object.keys(roles).map(roleName => {
        const roleData = roles[roleName];
        return {
            name: roleName,
            active: roleData.active,
            description: roleData.description || `Rol de ${roleName}`,
            id: roleData.id
        };
    });

    // Seleccionar el rol actual si existe
    if (currentActiveRole.value) {
        selectedRole.value = currentActiveRole.value;
    }
};

const selectRole = async (role) => {
    if (isLoading.value) return;

    isLoading.value = true;
    selectedRole.value = role;

    try {
        await router.post(route('user.set-active-role'), {
            role: role
        }, {
            preserveScroll: true,
            onSuccess: () => {
                window.location.href = route('dashboard');
            },
            onError: () => {
                isLoading.value = false;
            }
        });
    } catch (error) {
        isLoading.value = false;
    }
};

// Función para obtener color según el rol
const getRoleColor = (roleName) => {
    const colors = {
        'Admin': 'bg-red-100 border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800',
        'Desarrollador': 'bg-blue-100 border-blue-200 text-blue-700 dark:bg-blue-900/20 dark:border-blue-800',
    };
    return colors[roleName] || 'bg-gray-100 border-gray-200 text-gray-700 dark:bg-gray-800 dark:border-gray-700';
};

// Función para obtener icono según el rol
const getRoleIcon = (roleName) => {
    const icons = {
        'Admin': mdiShieldAccount,
        //'Desarrollador': mdiAccountKey,      
    };
    return icons[roleName] || mdiAccountKey;
};
</script>

<template>
    <LayoutGuest>
        <div
            class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 p-4">
            <CardBox class="w-full max-w-2xl mx-auto shadow-2xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <BaseIcon :path="mdiShieldAccount" class="text-blue-600 dark:text-blue-300" size="24" />
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Selecciona tu Rol</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">
                        Elige el rol con el que deseas trabajar en esta sesión
                    </p>
                    <div class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                        {{ userRoles.length }} roles disponibles
                    </div>
                </div>

                <!-- Grid de roles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 max-h-96 overflow-y-auto p-2">
                    <div v-for="role in userRoles" :key="role.name" @click="selectRole(role.name)"
                        class="p-6 border-2 rounded-xl cursor-pointer transition-all duration-200 group relative"
                        :class="[
                            selectedRole === role.name
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 shadow-lg scale-105'
                                : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md',
                            getRoleColor(role.name),
                            isLoading ? 'opacity-50 cursor-not-allowed' : ''
                        ]">
                        <!-- Checkmark para rol seleccionado -->
                        <div v-if="selectedRole === role.name" class="absolute -top-2 -right-2">
                            <div class="bg-blue-500 text-white rounded-full p-1">
                                <BaseIcon :path="mdiCheck" size="16" />
                            </div>
                        </div>

                        <!-- Badge para rol actual -->
                        <div v-if="role.name === currentActiveRole" class="absolute -top-2 -left-2">
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                Actual
                            </span>
                        </div>

                        <div class="flex items-start space-x-4">
                            <!-- Icono del rol -->
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded-lg bg-white dark:bg-gray-800 shadow-sm">
                                    <BaseIcon :path="getRoleIcon(role.name)" class="text-blue-600 dark:text-blue-300"
                                        size="20" />
                                </div>
                            </div>

                            <!-- Contenido del rol -->
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="font-semibold text-lg mb-1 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">
                                    {{ role.name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ role.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Efecto hover -->
                        <div
                            class="absolute inset-0 rounded-xl bg-blue-500 opacity-0 group-hover:opacity-5 transition-opacity duration-200">
                        </div>
                    </div>
                </div>

                <!-- Footer con botón de confirmación -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t dark:border-gray-700">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span v-if="selectedRole">
                            Rol seleccionado: <strong class="text-blue-600 dark:text-blue-400">{{ selectedRole
                                }}</strong>
                        </span>
                        <span v-else>
                            Por favor selecciona un rol
                        </span>
                    </div>

                    <BaseButton color="info" :label="isLoading ? 'Iniciando sesión...' : 'Continuar'"
                        :disabled="!selectedRole || isLoading" :loading="isLoading" @click="selectRole(selectedRole)"
                        class="min-w-32" />
                </div>

                <!-- Información adicional -->
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Podrás cambiar de rol en cualquier momento desde el menú principal
                    </p>
                </div>
            </CardBox>
        </div>
    </LayoutGuest>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

/* Animaciones suaves */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>