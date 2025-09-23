<script setup>
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormControlSelect from "@/components/FormControlSelect.vue";
import { onMounted } from 'vue';
import FormControlPassword from "@/components/FormControlPassword.vue"
import Accordion from '@/components/Accordion.vue'



import {
  mdiBallotOutline,
  mdiAccount,
  mdiHomeCity,
  mdiCalendar,
  mdiDesktopClassic,
  mdiMicrosoftWindows,
  mdiChip,
  mdiMemory,
  mdiHarddisk,
  mdiUsb,
  mdiCamera,
  mdiCommentTextOutline,
  mdiBriefcaseAccount
} from "@mdi/js";

const props = defineProps({
  titulo: String,
  routeName: String,
  departamentos: Array,
  inventario: Object,
  usuariosArqueo: Array,
  marcasPorTipo: Object,

});

const form = useForm({
  //fecha_registro: props.inventario.fecha_registro,
  nombre_persona: props.inventario.nombre_persona,
  departamento_id: props.inventario.departamento_id,
  puesto: props.inventario.puesto,
  tipo_pc: props.inventario.tipo_pc,
  marca_equipo: props.inventario.marca_equipo,
  sistema_operativo: props.inventario.sistema_operativo,
  procesador: props.inventario.procesador,
  tarjeta_madre: props.inventario.tarjeta_madre,
  tarjeta_grafica: props.inventario.tarjeta_grafica,
  datos_tarjeta_grafica: props.inventario.datos_tarjeta_grafica,
  tipo_ram: props.inventario.tipo_ram,
  capacidad_ram: props.inventario.capacidad_ram,
  marca_ram: props.inventario.marca_ram,
  tipo_disco: props.inventario.tipo_disco,
  capacidad_disco: props.inventario.capacidad_disco,
  teclado_mouse: props.inventario.teclado_mouse,
  camara_web: props.inventario.camara_web,
  otro_periferico: props.inventario.otro_periferico,

  software_remoto: props.inventario.software_remoto || '',
  id_remoto: props.inventario.id_remoto || '',
  password_remoto: props.inventario.password_remoto || '',

  user_id: props.inventario.user_id,
  observaciones: props.inventario.observaciones,
});


const handleSubmit = () => {
  console.log(form);
  form.put(route(`${props.routeName}update`, props.inventario.id));

};
const sections = [
  { title: "Datos Generales", slotName: "datos-generales" },
  { title: "Hardware", slotName: "hardware" },
  { title: "Acceso Remoto", slotName: "acceso-remoto" },
  { title: "Otros", slotName: "otros" },
]


</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

    <CardBox form @submit.prevent="handleSubmit">
      <Accordion :sections="sections">

        <!-- Datos Generales -->
        <template #datos-generales>
          <FormField label="Nombre de Persona" :error="form.errors.nombre_persona">
            <FormControl v-model="form.nombre_persona" type="text" :icon="mdiAccount" required />
          </FormField>

          <FormField label="Departamento" :error="form.errors.departamento_id">
            <FormControl v-model="form.departamento_id" :options="departamentos" type="select" :icon="mdiHomeCity"
              required />
          </FormField>

          <!-- Nuevo campo Puesto -->
          <FormField label="Puesto" :error="form.errors.puesto">
            <FormControl v-model="form.puesto" type="text" :icon="mdiBriefcaseAccount" required
              @input="form.puesto = form.puesto.toUpperCase()" />
          </FormField>

          <FormField label="Tipo de PC" :error="form.errors.tipo_pc">
            <FormControlSelect v-model="form.tipo_pc" type="select" :icon="mdiDesktopClassic" :options="[
              { value: 'Laptop', text: 'Laptop' },
              { value: 'PC Escritorio', text: 'PC Escritorio' },
              { value: 'PC Escritorio (Servidor)', text: 'PC Escritorio (Servidor)' },
              { value: 'Impresora', text: 'Impresora' }
            ]" required />
          </FormField>

          <FormField label="Marca del Equipo" :error="form.errors.marca_equipo">
            <FormControlSelect v-model="form.marca_equipo" type="select" :icon="mdiDesktopClassic"
              :options="props.marcasPorTipo.marca_equipo.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Sistema Operativo" :error="form.errors.sistema_operativo">
            <FormControl v-model="form.sistema_operativo" type="text" :icon="mdiMicrosoftWindows" required />
          </FormField>

          <FormField label="Procesador" :error="form.errors.procesador">
            <FormControl v-model="form.procesador" type="text" :icon="mdiChip" required />
          </FormField>
        </template>

        <!-- Hardware -->
        <template #hardware>
          <FormField label="Tarjeta Madre" :error="form.errors.tarjeta_madre">
            <FormControlSelect v-model="form.tarjeta_madre" type="select" :icon="mdiChip"
              :options="props.marcasPorTipo.tarjeta_madre.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Tarjeta Gráfica" :error="form.errors.tarjeta_grafica">
            <FormControlSelect v-model="form.tarjeta_grafica" type="text" :icon="mdiChip" :options="[
              { value: 'Integrada al procesador', text: 'INTEGRADA AL PROCESADOR' },
              { value: 'Externa', text: 'EXTERNA' },
            ]" required />
          </FormField>

          <FormField label="Datos Tarjeta Gráfica" :error="form.errors.datos_tarjeta_grafica">
            <FormControl v-model="form.datos_tarjeta_grafica" type="text" :icon="mdiChip" />
          </FormField>

          <FormField label="Tipo de RAM" :error="form.errors.tipo_ram">
            <FormControlSelect v-model="form.tipo_ram" type="text" :icon="mdiMemory" :options="[
              { value: 'DDR2', text: 'DDR2' },
              { value: 'DDR3', text: 'DDR3' },
              { value: 'DDR4', text: 'DDR4' },
              { value: 'DDR5', text: 'DDR5' },
            ]" required />
          </FormField>

          <FormField label="Capacidad RAM" :error="form.errors.capacidad_ram">
            <FormControlSelect v-model="form.capacidad_ram" type="select" :icon="mdiMemory" :options="[
              { value: '2 GB', text: '2 GB' },
              { value: '4 GB', text: '4 GB' },
              { value: '6 GB', text: '6 GB' },
              { value: '8 GB', text: '8 GB' },
              { value: '12 GB', text: '12 GB' },
              { value: '16 GB', text: '16 GB' },
              { value: '20 GB', text: '20 GB' },
              { value: '32 GB', text: '32 GB' },
            ]" required />
          </FormField>

          <FormField label="Marca RAM" :error="form.errors.marca_ram">
            <FormControlSelect v-model="form.marca_ram" type="select" :icon="mdiMemory"
              :options="props.marcasPorTipo.marca_ram.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Tipo de Disco" :error="form.errors.tipo_disco">
            <FormControlSelect v-model="form.tipo_disco" type="text" :icon="mdiHarddisk" :options="[
              { value: 'SSD', text: 'SSD' },
              { value: 'M2', text: 'M2' },
              { value: 'HDD', text: 'HDD' },
              { value: 'HDD & SSD', text: 'HDD & SSD' },
            ]" required />
          </FormField>

          <FormField label="Capacidad Disco" :error="form.errors.capacidad_disco">
            <FormControlSelect v-model="form.capacidad_disco" type="text" :icon="mdiHarddisk" :options="[
              { value: 'Menos de 100 GB', text: 'Menos de 100 GB' },
              { value: '120 GB', text: '120 GB' },
              { value: '256 GB', text: '256 GB' },
              { value: '460 GB', text: '460 GB' },
              { value: '512 GB', text: '512 GB' },
              { value: '1 TB', text: '1 TB' },
              { value: '2 TB', text: '2 TB' },
            ]" required />
          </FormField>

          <FormField label="Teclado y Mouse" :error="form.errors.teclado_mouse">
            <FormControlSelect v-model="form.teclado_mouse" type="select" :icon="mdiUsb"
              :options="props.marcasPorTipo.teclado_mouse.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Cámara Web" :error="form.errors.camara_web">
            <FormControlSelect v-model="form.camara_web" type="select" :icon="mdiCamera"
              :options="props.marcasPorTipo.camara_web.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Otro Periférico" :error="form.errors.otro_periferico">
            <FormControl v-model="form.otro_periferico" type="text" :icon="mdiUsb" />
          </FormField>
        </template>

        <!-- Acceso Remoto -->
        <template #acceso-remoto>
          <FormField label="Software de Acceso Remoto" :error="form.errors.software_remoto">
            <FormControlSelect v-model="form.software_remoto" :options="[
              { value: 'TeamViewer', text: 'TeamViewer' },
              { value: 'AnyDesk', text: 'AnyDesk' },
              { value: 'Chrome Remote Desktop', text: 'Chrome Remote Desktop' },
              { value: 'RustDesk', text: 'RustDesk' },
              { value: 'Otro', text: 'Otro' },
            ]" required />
          </FormField>

          <FormField v-if="form.software_remoto !== ''" label="ID Remoto" :error="form.errors.id_remoto">
            <FormControl v-model="form.id_remoto" type="text" placeholder="Ej. 123 456 789" required />
          </FormField>

          <FormField v-if="form.software_remoto !== ''" label="Contraseña Remota" :error="form.errors.password_remoto">
            <FormControlPassword v-model="form.password_remoto" type="password" placeholder="Contraseña" required />
          </FormField>
        </template>

        <!-- Otros -->
        <template #otros>
          <FormField label="Nombre Arqueo" :error="form.errors.user_id">
            <FormControlSelect v-model="form.user_id" type="select" :icon="mdiAccount"
              :options="props.usuariosArqueo.map(u => ({ value: u.id, text: u.name }))" required />
          </FormField>

          <FormField label="Observaciones" :error="form.errors.observaciones">
            <FormControl v-model="form.observaciones" type="text" :icon="mdiCommentTextOutline" required />
          </FormField>
        </template>

      </Accordion>


      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Actualizar" />
          <BaseButton :href="route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>
