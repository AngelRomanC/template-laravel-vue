<script setup>
import { ref } from 'vue';
import { mdiEye, mdiTrashCan } from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import FileUploader from "@/Components/FileUploader.vue";

const props = defineProps({
    archivos: {
        type: Array,
        default: () => []
    },
    tipo: {
        type: String,
        required: true,
        validator: value => ['principal', 'anexo'].includes(value)
    },
    label: {
        type: String,
        required: true
    },
    error: String
});

const emit = defineEmits(['update:archivos', 'eliminar-archivo', 'agregar-archivos']);

const nuevosArchivos = ref([]);

const mostrarArchivo = (ruta) => {
    window.open(`/storage/${ruta}`, '_blank');
};

const eliminarArchivo = (id) => {
    emit('eliminar-archivo', id);
};

const agregarArchivos = (files) => {
    emit('agregar-archivos', files);
};
</script>

<template>
    <div class="mb-6">
        <h3 class="text-lg font-medium mb-2">{{ label }}</h3>
        
        <!-- Archivos existentes -->
        <div v-if="archivos.length > 0" class="mb-4 space-y-2">
            <div v-for="archivo in archivos" :key="archivo.id" 
                 class="flex items-center justify-between p-2 border rounded">
                <span class="truncate flex-1">{{ archivo.nombre_original }}</span>
                <div class="flex space-x-2 ml-2">
                    <BaseButton 
                        @click="mostrarArchivo(archivo.ruta_archivo)" 
                        :icon="mdiEye" 
                        color="info" 
                        small
                        title="Ver"
                    />
                    <BaseButton 
                        @click="eliminarArchivo(archivo.id)" 
                        :icon="mdiTrashCan" 
                        color="danger" 
                        small
                        title="Eliminar"
                    />
                </div>
            </div>
        </div>
        
        <!-- Uploader para nuevos archivos -->
        <FileUploader 
            :label="`Agregar nuevos ${label.toLowerCase()}`"
            v-model="nuevosArchivos"
            :error="error"
            accept="application/pdf"
            multiple
            @files-selected="agregarArchivos"
        />
    </div>
</template>