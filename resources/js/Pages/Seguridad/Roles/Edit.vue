<script setup>
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import CardBox from '@/components/CardBox.vue';
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from '@/components/BaseButtons.vue';
import FormField from '@/components/FormField.vue';
import FormControl from '@/components/FormControl.vue';
import { mdiBallotOutline, mdiAccountBadge, mdiViewModule } from "@mdi/js";

const props = defineProps({
    role: Object,          // Rol que se está editando
    permissions: Object,   // Permisos agrupados por módulo
    modules: Array,
    titulo: String,
    routeName: String
});

const form = useForm({
    name: props.role.name || '',
    permissions: props.role.permissions?.map(p => p.name) || []
});

const submit = () => {
    form.put(route(props.routeName + 'update', props.role.id));
};

const getModuleName = (moduleKey) => {
    if (!moduleKey || moduleKey === 'null') return 'Sin Módulo';
    const module = props.modules.find(m => m.key === moduleKey);
    return module ? module.name : moduleKey;
};
</script>

<template>
    <LayoutMain>
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Nombre del Rol -->
            <FormField label="Nombre del Rol" :error="form.errors.name">
                <FormControl v-model="form.name" placeholder="Ingrese el nombre del rol" required
                    :icon="mdiAccountBadge" />
            </FormField>

            <!-- Grid de Módulos con Permisos -->
            <FormField label="Permisos" :error="form.errors.permissions">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <CardBox v-for="(modulePermissions, moduleKey) in permissions" :key="moduleKey"
                        class="hover:shadow-lg transition-shadow duration-200">
                        <!-- Encabezado -->
                        <div class="flex items-center mb-4 pb-2 border-b">
                            <svg class="w-6 h-6 mr-2 text-blue-600" viewBox="0 0 24 24">
                                <path :d="mdiViewModule" fill="currentColor" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ getModuleName(moduleKey) }}
                            </h3>
                            <span class="ml-2 text-sm text-gray-500">
                                ({{ modulePermissions.length }})
                            </span>
                        </div>

                        <!-- Lista de Permisos -->
                        <div class="space-y-3">
                            <div v-for="permission in modulePermissions" :key="permission.id" class="flex items-start">
                                <input type="checkbox" :id="'permission-' + permission.id" v-model="form.permissions"
                                    :value="permission.name"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1" />
                                <label :for="'permission-' + permission.id" class="ml-2 text-sm text-gray-900">
                                    <span class="font-medium">{{ permission.name }}</span>
                                    <p v-if="permission.description" class="text-xs text-gray-500 mt-1">
                                        {{ permission.description }}
                                    </p>
                                </label>
                            </div>
                        </div>
                    </CardBox>
                </div>
            </FormField>

            <!-- Botones -->
            <BaseButtons>
                <BaseButton type="submit" color="info" label="Actualizar" :loading="form.processing" outline="" />
                <BaseButton :href="route(props.routeName + 'index')" color="danger" outline label="Cancelar" />
            </BaseButtons>
        </form>
    </LayoutMain>
</template>
