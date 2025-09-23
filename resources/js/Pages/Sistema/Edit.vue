<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormControlSelect from "@/components/FormControlSelect.vue"
import { mdiAbjadHebrew, mdiBallotOutline, mdiFormatListChecks, mdiOfficeBuilding, mdiFileDocument, mdiMapMarker, mdiCalendar, mdiPlus, mdiTrashCan, mdiEye } from "@mdi/js";
import FileUploader from '@/Components/FileUploader.vue';
import { ref } from 'vue';
import LoadingOverlay from '@/components/LoadingOverlay.vue';
import Swal from 'sweetalert2';

const isUploading = ref(false);

const props = defineProps({
    titulo: String,
    sistema: Object,
    routeName: String,
    departamentos: Array,
    archivosPrincipales: {
        type: Array,
        default: () => []
    },
});

// Inicializar el formulario con todos los campos necesarios
const form = useForm({
    _method: 'PUT', // Importante para las actualizaciones
    id: props.sistema?.id,
    nombre: props.sistema?.nombre || '',
    descripcion: props.sistema?.descripcion || '',
    departamento_id: props.sistema?.departamento_id || null,
    url: props.sistema?.url || '',
    fecha_creacion: props.sistema?.fecha_creacion || '',
    fecha_produccion: props.sistema?.fecha_produccion || '',
    estatus: props.sistema?.estatus || '',
    numero_usuarios: props.sistema?.numero_usuarios || '',
    nombre_servidor: props.sistema?.nombre_servidor || '',
    ip_servidor: props.sistema?.ip_servidor || '',
    sistema_operativo: props.sistema?.sistema_operativo || '',
    nombre_servidor_bd: props.sistema?.nombre_servidor_bd || '',
    ip_servidor_bd: props.sistema?.ip_servidor_bd || '',
    lenguaje_desarrollo: props.sistema?.lenguaje_desarrollo || '',
    version_lenguaje: props.sistema?.version_lenguaje || '',
    nuevos_documentos_principales: [],
    archivos_a_eliminar: []
});

// Manejar archivos existentes
const archivosExistentes = ref(
    Array.isArray(props.archivosPrincipales) 
        ? props.archivosPrincipales.map(a => ({
            id: a.id,
            nombre_original: a.nombre_original || 'Documento sin nombre',
            ruta_archivo: a.ruta_archivo
        })) 
        : []
);

// Ver archivo
const mostrarArchivo = (ruta) => {
    if (!ruta) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'No se ha proporcionado una ruta válida para el archivo PDF.',
        });
        return;
    }
    Swal.fire({
        html: `
            <div style="width: 100%; height: 500px;">
                <iframe src="/storage/${ruta}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
            </div>
        `,
        width: "80%",
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: "Cerrar",
        allowOutsideClick: true,
        allowEscapeKey: true,
    });
};

// Manejar eliminación de archivos
const toggleEliminarArchivo = (archivoId) => {
    const index = form.archivos_a_eliminar.indexOf(archivoId);
    if (index === -1) {
        form.archivos_a_eliminar.push(archivoId);
    } else {
        form.archivos_a_eliminar.splice(index, 1);
    }
};

// Enviar formulario
const handleSubmit = () => {
    isUploading.value = true;

    console.log('Datos del formulario antes de enviar:', form);
    
    form.post(route(`${props.routeName}update`, props.sistema.id), {
        onFinish: () => {
            isUploading.value = false;
        }
    });
};
</script>

<template>
    <LayoutMain :title="titulo">
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />
        <CardBox form @submit.prevent="handleSubmit" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Nombre del sistema -->
                <FormField label="Nombre del sistema" :error="form.errors.nombre">
                    <FormControl
                        v-model="form.nombre"
                        type="text"
                        placeholder="Nombre del sistema"
                        :icon="mdiAbjadHebrew"
                        required
                    />
                </FormField>
                <!-- Descripcion -->
                <FormField label="Descripcion del sistema" :error="form.errors.descripcion">
                    <FormControl
                        v-model="form.descripcion"
                        type="text"
                        placeholder="Descripcion del sistema"
                        :icon="mdiFormatListChecks"
                        required
                    />
                </FormField>
                <!-- Departamento -->
                <FormField label="Departamento" :error="form.errors.departamento_id">
                    <FormControl
                        v-model="form.departamento_id"
                        :options="departamentos"
                        type="select"
                        label-key="name"
                        value-key="id"
                        :icon="mdiOfficeBuilding"
                        required
                    />
                </FormField>
                <!-- URL del sistema -->
                <FormField label="URL del sistema" :error="form.errors.url">
                    <FormControl
                        v-model="form.url"
                        type="text"
                        placeholder="URL del sistema"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- Fecha de creación -->
                <FormField label="Fecha de creación" :error="form.errors.fecha_creacion">
                    <FormControl
                        v-model="form.fecha_creacion"
                        type="date"
                        placeholder="Fecha de creación"
                        :icon="mdiCalendar"
                        required
                    />
                </FormField>
                <!-- Fecha de Producción -->
                <FormField label="Fecha de Producción" :error="form.errors.fecha_produccion">
                    <FormControl
                        v-model="form.fecha_produccion"
                        type="date"
                        placeholder="Fecha de publicación"
                        :icon="mdiCalendar"
                        required
                    />
                </FormField>
                <!-- Estatus -->
                <FormField label="Estatus" :error="form.errors.estatus">
                    <FormControlSelect v-model="form.estatus" type="select" :icon="mdiFormatListChecks" :options="[
                    { value: 'Diseño', text: 'En Diseño' },
                    { value: 'Producción', text: 'Producción' },
                    { value: 'Pruebas', text: 'En Pruebas' },
                    { value: 'Desarrollo', text: 'Desarrollo' },
                    { value: 'Mantenimiento', text: 'Mantenimiento' }
                    ]" required />
                </FormField>
                <!-- Número de usuarios -->
                <FormField label="Número de usuarios" :error="form.errors.numero_usuarios">
                    <FormControl
                        v-model="form.numero_usuarios"
                        type="number"
                        placeholder="Número de usuarios"
                        :icon="mdiFormatListChecks"
                        required
                        min="0"
                    />
                </FormField>
                <!-- Nombre del servidor -->
                <FormField label="Nombre del servidor" :error="form.errors.nombre_servidor">
                    <FormControl
                        v-model="form.nombre_servidor"
                        type="text"
                        placeholder="Nombre del servidor"
                        :icon="mdiOfficeBuilding"
                        required
                    />
                </FormField>
                <!-- IP del servidor -->
                <FormField label="IP del servidor" :error="form.errors.ip_servidor">
                    <FormControl
                        v-model="form.ip_servidor"
                        type="text"
                        placeholder="IP del servidor"
                        :icon="mdiMapMarker"
                        required
                    />
                </FormField>
                <!-- Sistema operativo -->
                <FormField label="Sistema operativo" :error="form.errors.sistema_operativo">
                    <FormControl
                        v-model="form.sistema_operativo"
                        type="text"
                        placeholder="Sistema operativo"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- Nombre del servidor de base de datos -->
                <FormField label="Nombre del servidor BD" :error="form.errors.nombre_servidor_bd">
                    <FormControl
                        v-model="form.nombre_servidor_bd"
                        type="text"
                        placeholder="Nombre del servidor BD"
                        :icon="mdiOfficeBuilding"
                        required
                    />
                </FormField>
                <!-- IP del servidor de base de datos -->
                <FormField label="IP del servidor BD" :error="form.errors.ip_servidor_bd">
                    <FormControl
                        v-model="form.ip_servidor_bd"
                        type="text"
                        placeholder="IP del servidor BD"
                        :icon="mdiMapMarker"
                        required
                    />
                </FormField>
                <!-- lenguaje de desarrollo -->
                <FormField label="Lenguaje de desarrollo" :error="form.errors.lenguaje_desarrollo">
                    <FormControl
                        v-model="form.lenguaje_desarrollo"
                        type="text"
                        placeholder="Lenguaje de desarrollo"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
                <!-- version de lenguaje -->
                <FormField label="Versión de lenguaje" :error="form.errors.version_lenguaje">
                    <FormControl
                        v-model="form.version_lenguaje"
                        type="text"
                        placeholder="Versión de lenguaje"
                        :icon="mdiFileDocument"
                        required
                    />
                </FormField>
            </div>
            <!-- Sección de archivos existentes -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos existentes</h3>
                <div v-if="archivosExistentes.length > 0" class="space-y-3">
                    <div v-for="archivo in archivosExistentes" :key="archivo.id"
                        class="flex items-center justify-between p-3 border rounded-lg"
                        :class="{ 'bg-red-50': form.archivos_a_eliminar.includes(archivo.id) }">
                        
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-medium text-gray-700 truncate max-w-xs">
                                {{ archivo.nombre_original }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-2">
                            <BaseButton
                                @click="mostrarArchivo(archivo.ruta_archivo)"
                                color="info"
                                :icon="mdiEye"
                                small
                                title="Ver documento"
                            />
                            <BaseButton
                                @click="toggleEliminarArchivo(archivo.id)"
                                :color="form.archivos_a_eliminar.includes(archivo.id) ? 'success' : 'danger'"
                                :icon="mdiTrashCan"
                                small
                                :title="form.archivos_a_eliminar.includes(archivo.id) ? 'Cancelar eliminación' : 'Eliminar documento'"
                            />
                        </div>
                    </div>
                </div>
                <div v-else class="text-sm text-gray-500 italic">
                    No hay documentos asociados a este sistema.
                </div>
            </div>

            <!-- Sección para nuevos archivos -->
            <div class="mt-8">
                <FormField label="Agregar nuevos documentos" :error="form.errors.nuevos_documentos_principales">
                    <FileUploader
                        v-model="form.nuevos_documentos_principales"
                        :error="form.errors.nuevos_documentos_principales"
                        :icon="mdiPlus"
                        accept="application/pdf"
                        multiple
                    />
                </FormField>
                <p class="text-xs text-gray-500 mt-1">
                    Formatos aceptados: PDF. Tamaño máximo por archivo: 10MB.
                </p>
            </div>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="handleSubmit"
                        type="submit"
                        color="info"
                        label="Actualizar"
                        :disabled="form.processing"
                    />
                    <BaseButton
                        :href="route(`${routeName}index`)"
                        type="button"
                        color="danger"
                        outline
                        label="Cancelar"
                    />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>

    <LoadingOverlay 
        :visible="isUploading" 
        title="Actualizando sistema..." 
        subtitle="Por favor, no cierres esta ventana." 
    />
</template>