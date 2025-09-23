<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import LayoutMain from '@/layouts/LayoutMain.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from "@/components/BaseButtons.vue"
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import CardBox from "@/components/CardBox.vue"
import FormField from "@/components/FormField.vue"
import FormControl from "@/components/FormControl.vue"
import FormControlSelect from "@/components/FormControlSelect.vue"
import Accordion from '@/components/Accordion.vue'
import FileUploader from '@/Components/FileUploader.vue';
import { mdiAbjadHebrew, mdiBallotOutline, mdiFormatListChecks, mdiOfficeBuilding, mdiFileDocument, mdiMapMarker, mdiCalendar, mdiPlus } from "@mdi/js";


const props = defineProps({
  titulo: String,
  routeName: String,
  departamentos: Array,
  departamento_id: Number,
});
console.log(props.departamento_id);

const form = useForm({
  nombre: '',
  descripcion: '',
  //departamento_id: '',
  departamento_id: props.departamento_id || '',
  fecha_solicitud: '',
  fecha_entrega: '',
  fecha_inicio_vigencia: '',
  fecha_fin_vigencia: '',
  estatus: '',
  numero_usuarios: '',
  nombre_responsable: '',
  nombre_autorizacion: '',
  ruta_documento: [],
});
const handleSubmit = () => {
  form.post(route(`${props.routeName}store`));
};


</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />
    <CardBox form @submit.prevent="handleSubmit" enctype="multipart/form-data">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <!-- Nombre de certificacion -->
        <FormField label="Nombre del Proceso" :error="form.errors.nombre">
          <FormControl v-model="form.nombre" type="text" placeholder="Nombre del Proceso " :icon="mdiAbjadHebrew"
            required  />
        </FormField>
        <!-- Descripcion -->
        <FormField label="Descripcion del proceso" :error="form.errors.descripcion">
          <FormControl v-model="form.descripcion" type="text" placeholder="Descripcion del Proceso "
            :icon="mdiFormatListChecks" required  />
        </FormField>
        <!-- Departamento -->
        <FormField label="Departamento" :error="form.errors.departamento_id">
          <FormControl v-model="form.departamento_id" :options="departamentos" type="select"
            label-key="nombre" value-key="id" :icon="mdiOfficeBuilding" required  :disabled="!!form.departamento_id"
            title="Campo no editable - Departamento fijo" />
        </FormField>
        <!-- Fecha de creación -->
        <FormField label="Fecha de creación" :error="form.errors.fecha_solicitud">
          <FormControl v-model="form.fecha_solicitud" type="date" placeholder="Fecha de creación" :icon="mdiCalendar"
            required />
        </FormField>
        <!-- Fecha de Entrega -->
        <FormField label="Fecha de Entrega" :error="form.errors.fecha_entrega">
          <FormControl v-model="form.fecha_entrega" type="date" placeholder="Fecha de entrega" :icon="mdiCalendar"
            required />
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
        <!-- Archivo de documentación técnica -->
        <FormField label="Archivo de documentación técnica" :error="form.errors.ruta_documento">
          <FileUploader v-model="form.ruta_documento" :error="form.errors.ruta_documento" :icon="mdiPlus"
            accept="application/pdf" multiple required />
        </FormField>
      </div>


      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Guardar" />
          <BaseButton :href="route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>
