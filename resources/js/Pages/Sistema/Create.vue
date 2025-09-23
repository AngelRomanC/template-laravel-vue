<script setup>
import { useForm,Link } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormControlSelect from "@/components/FormControlSelect.vue"
import { mdiAbjadHebrew ,mdiBallotOutline, mdiFormatListChecks, mdiOfficeBuilding, mdiFileDocument, mdiMapMarker, mdiCalendar,mdiPlus } from "@mdi/js";
import FormControlV7 from '@/Components/FormControlV7.vue';
import FileUploader from '@/Components/FileUploader.vue';
import CatalogoRedirectButton from '@/Components/CatalogoRedirectButton.vue';

import { ref } from 'vue'
import LoadingOverlay from '@/components/LoadingOverlay.vue';

const isUploading = ref(false)

// Función para manejar el inicio de la carga
const props = defineProps({
    titulo: String,
    routeName: String,
    departamentos: Array,
});


const form = useForm({
    nombre: '',
    descripcion: '',
    departamento_id: '',
    url: '',
    fecha_creacion: '',
    fecha_produccion: '',
    estatus: '',
    numero_usuarios: '',
    nombre_servidor: '',
    ip_servidor: '',
    sistema_operativo: '',
    nombre_servidor_bd: '',
    ip_servidor_bd: '',
    lenguaje_desarrollo: '',
    version_lenguaje: '',
    ruta_documento: [],
});

const handleSubmit = () => {    
    console.log('Formulario enviado:', form);
    // form.post(route(`${props.routeName}store`)); // Corregida sintaxis de ruta
    form.post(route(`${props.routeName}store`));
};
</script>

<template>
    <LayoutMain :title="titulo">
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />
        <CardBox form @submit.prevent="handleSubmit" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Nombre del sistema  -->
                <FormField label="Nombre del sistema" :error="form.errors.nombre">
                    <FormControl v-model="form.nombre" type="text" placeholder="Nombre del sistema "
                        :icon="mdiAbjadHebrew " required class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo" />
                </FormField>
                <!-- Descripcion -->
                <FormField label="Descripcion del sistema" :error="form.errors.descripcion">
                    <FormControl v-model="form.descripcion" type="text" placeholder="Descripcion del sistema "
                        :icon="mdiFormatListChecks" required class="bg-gray-100 cursor-not-allowed"
                        title="Campo no editable - Documento Técnico fijo" />
                </FormField>
                <!-- Departamento -->
                <FormField label="Departamento" :error="form.errors.departamento_id">
                    <FormControl v-model="form.departamento_id" :options="departamentos" type="select"
                        label-key="nombre_departamento" value-key="id" :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- URL del sistema -->
                <FormField label="URL del sistema" :error="form.errors.url">
                    <FormControl v-model="form.url" type="text" placeholder="URL del sistema" :icon="mdiFileDocument"
                        required />
                </FormField>
                <!-- Fecha de creación -->
                <FormField label="Fecha de creación" :error="form.errors.fecha_creacion">
                    <FormControl v-model="form.fecha_creacion" type="date" placeholder="Fecha de creación"
                        :icon="mdiCalendar" required />
                </FormField>
                <!-- Fecha de Producción -->
                <FormField label="Fecha de Producción" :error="form.errors.fecha_produccion">
                    <FormControl v-model="form.fecha_produccion" type="date" placeholder="Fecha de publicación"
                        :icon="mdiCalendar" required />
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
                    <FormControl v-model="form.numero_usuarios" type="text" placeholder="Número de usuarios"
                        :icon="mdiFormatListChecks" required />
                </FormField>
                <!-- Nombre del servidor -->
                <FormField label="Nombre del servidor" :error="form.errors.nombre_servidor">
                    <FormControl v-model="form.nombre_servidor" type="text" placeholder="Nombre del servidor"
                        :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- IP del servidor -->
                <FormField label="IP del servidor" :error="form.errors.ip_servidor">
                    <FormControl v-model="form.ip_servidor" type="text" placeholder="IP del servidor"
                        :icon="mdiMapMarker" required />
                </FormField>
                <!-- Sistema operativo -->
                <FormField label="Sistema operativo" :error="form.errors.sistema_operativo">
                    <FormControl v-model="form.sistema_operativo" type="text" placeholder="Sistema operativo"
                        :icon="mdiFileDocument" required />
                </FormField>
                <!-- Nombre del servidor de base de datos -->
                <FormField label="Nombre del servidor BD" :error="form.errors.nombre_servidor_bd">
                    <FormControl v-model="form.nombre_servidor_bd" type="text" placeholder="Nombre del servidor BD"
                        :icon="mdiOfficeBuilding" required />
                </FormField>
                <!-- IP del servidor de base de datos -->
                <FormField label="IP del servidor BD" :error="form.errors.ip_servidor_bd">
                    <FormControl v-model="form.ip_servidor_bd" type="text" placeholder="IP del servidor BD"
                        :icon="mdiMapMarker" required />
                </FormField>
                <!-- lenguaje de desarrollo -->
                <FormField label="Lenguaje de desarrollo" :error="form.errors.lenguaje_desarrollo">
                    <FormControl v-model="form.lenguaje_desarrollo" type="text" placeholder="Lenguaje de desarrollo"
                        :icon="mdiFileDocument" required />
                </FormField>
                <!-- version de lenguaje -->
                <FormField label="Versión de lenguaje" :error="form.errors.version_lenguaje">
                    <FormControl v-model="form.version_lenguaje" type="text" placeholder="Versión de lenguaje"
                        :icon="mdiFileDocument" required />
                </FormField>
                <!-- Archivo de documentación técnica -->
                <FormField label="Archivo de documentación técnica" :error="form.errors.ruta_documento">
                    <FileUploader v-model="form.ruta_documento" :error="form.errors.ruta_documento" :icon="mdiPlus"
                        accept="application/pdf" multiple required />
                </FormField>
            </div>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Guardar" />

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