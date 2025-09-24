<script setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import BaseButton from '@/Components/BaseButton.vue';
import CardBox from '@/Components/CardBox.vue';
import { mdiAccountKey } from '@mdi/js';
import BaseIcon from '@/Components/BaseIcon.vue';

const userRoles = ref([]);
const selectedRole = ref(null);

onMounted(() => {
    // Obtener los roles del usuario desde auth.roles
    const roles = usePage().props.auth.roles;
    userRoles.value = Object.keys(roles).map(role => ({
        name: role,
        active: roles[role]
    }));
});

const selectRole = (role) => {
    // Enviar una petición al servidor para actualizar el rol activo
    router.post(route('user.set-active-role'), {
        role: role
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Redirigir al dashboard después de seleccionar el rol
            window.location.href = route('dashboard');
        }
    });
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-slate-900">
        <CardBox class="w-full max-w-md">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold">Selecciona tu Rol</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Por favor, selecciona el rol con el que deseas iniciar sesión
                </p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div 
                    v-for="role in userRoles" 
                    :key="role.name"
                    @click="selectRole(role.name)"
                    class="p-4 border rounded-lg cursor-pointer transition-colors flex items-center"
                    :class="{
                        'border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedRole === role.name,
                        'hover:bg-gray-50 dark:hover:bg-gray-800': selectedRole !== role.name
                    }"
                >
                    <BaseIcon :path="mdiAccountKey" class="mr-3 text-blue-600" />
                    <span class="font-medium">{{ role.name }}</span>
                </div>
            </div>
        </CardBox>
    </div>
</template>