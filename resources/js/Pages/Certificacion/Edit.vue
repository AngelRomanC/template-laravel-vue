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
import { ref } from 'vue'
import LoadingOverlay from '@/components/LoadingOverlay.vue';
import Swal from 'sweetalert2';

const isUploading = ref(false)

// Función para manejar el inicio de la carga
const props = defineProps({
    titulo: String,
    certificacion: Object,
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
    id: props.certificacion?.id,
    nombre: props.certificacion?.nombre || '',
    descripcion: props.certificacion?.descripcion || '',
    departamento_id: props.certificacion?.departamento_id || '',
    fecha_solicitud: props.certificacion?.fecha_solicitud || '',
    fecha_entrega: props.certificacion?.fecha_entrega || '',
    fecha_inicio_vigencia: props.certificacion?.fecha_inicio_vigencia || '',
    fecha_fin_vigencia: props.certificacion?.fecha_fin_vigencia || '',
    estatus: props.certificacion?.estatus || '',
    numero_usuarios: props.certificacion?.numero_usuarios || '',
    nombre_responsable: props.certificacion?.nombre_responsable || '',
    nombre_autorizacion: props.certificacion?.nombre_autorizacion || '',
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

    form.post(route(`${props.routeName}update`, props.certificacion.id), {
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
                <!-- Nombre de certificacion -->
                <FormField label="Nombre del sistema" :error="form.errors.nombre">
                    <FormControl v-model="form.nombre" type="text" placeholder="Nombre de certificación "
                        :icon="mdiAbjadHebrew" required class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo" />
                </FormField>
                <!-- Descripcion -->
                <FormField label="Descripcion de la certificación" :error="form.errors.descripcion">
                    <FormControl v-model="form.descripcion" type="text" placeholder="Descripcion de la certificación "
                        :icon="mdiFormatListChecks" required class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo" />
                </FormField>
                <!-- Departamento -->
                <FormField label="Departamento" :error="form.errors.departamento_id">
                    <FormControl v-model="form.departamento_id" :options="departamentos" type="select"
                        label-key="nombre_departamento" value-key="id" :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- Fecha de creación -->
                <FormField label="Fecha de creación" :error="form.errors.fecha_solicitud">
                    <FormControl v-model="form.fecha_solicitud" type="date" placeholder="Fecha de creación"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Fecha de Entrega -->
                <FormField label="Fecha de Entrega" :error="form.errors.fecha_entrega">
                    <FormControl v-model="form.fecha_entrega" type="date" placeholder="Fecha de entrega"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Fecha de Inicio Vigencia -->
                <FormField label="Fecha de Inicio Vigencia" :error="form.errors.fecha_inicio_vigencia">
                    <FormControl v-model="form.fecha_inicio_vigencia" type="date" placeholder="Fecha de inicio vigencia"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Fecha de Fin Vigencia -->
                <FormField label="Fecha de Fin Vigencia" :error="form.errors.fecha_fin_vigencia">
                    <FormControl v-model="form.fecha_fin_vigencia" type="date" placeholder="Fecha de fin vigencia"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Estatus -->
                <FormField label="Estatus" :error="form.errors.estatus">
                    <FormControlSelect v-model="form.estatus" type="select" :icon="mdiFormatListChecks" :options="[
                        { value: 'Diseño', text: 'En Diseño' },
                        { value: 'Revisión', text: 'Revisión' },
                        { value: 'Validación', text: 'Validación' },
                    ]" required />
                </FormField>
                <!-- Número de usuarios -->
                <FormField label="Número de usuarios" :error="form.errors.numero_usuarios">
                    <FormControl v-model="form.numero_usuarios" type="text" placeholder="Número de usuarios"
                        :icon="mdiFormatListChecks" required />
                </FormField>
                <!-- Nombre del Responsable -->
                <FormField label="Nombre del responsable" :error="form.errors.nombre_responsable">
                    <FormControl v-model="form.nombre_responsable" type="text" placeholder="Nombre del responsable"
                        :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- Nombre de la autorización -->
                <FormField label="Nombre de la autorización" :error="form.errors.nombre_autorizacion">
                    <FormControl v-model="form.nombre_autorizacion" type="text" placeholder="Nombre de la autorización"
                        :icon="mdiMapMarker" required />
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
                            <BaseButton @click="mostrarArchivo(archivo.ruta_archivo)" color="info" :icon="mdiEye" small
                                title="Ver documento" />
                            <BaseButton @click="toggleEliminarArchivo(archivo.id)"
                                :color="form.archivos_a_eliminar.includes(archivo.id) ? 'success' : 'danger'"
                                :icon="mdiTrashCan" small
                                :title="form.archivos_a_eliminar.includes(archivo.id) ? 'Cancelar eliminación' : 'Eliminar documento'" />
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
                    <FileUploader v-model="form.nuevos_documentos_principales"
                        :error="form.errors.nuevos_documentos_principales" :icon="mdiPlus" accept="application/pdf"
                        multiple />
                </FormField>
                <p class="text-xs text-gray-500 mt-1">
                    Formatos aceptados: PDF. Tamaño máximo por archivo: 10MB.
                </p>
            </div>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Actualizar"
                        :disabled="form.processing" />
                    <BaseButton :href="route(`${routeName}index`)" type="button" color="danger" outline
                        label="Cancelar" />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>
    <!-- Overlay de carga -->
    <LoadingOverlay :visible="isUploading" title="Subiendo archivo(s)..."
        subtitle="Por favor, no cierres esta ventana." />
</template>