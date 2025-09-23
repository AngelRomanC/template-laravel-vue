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

import {
  mdiBallotOutline,
  mdiAccount,
  mdiHomeCity,
  mdiDesktopClassic,
  mdiMicrosoftWindows,
  mdiChip,
  mdiMemory,
  mdiHarddisk,
  mdiUsb,
  mdiCamera,
  mdiCommentTextOutline, 
  mdiBriefcaseAccount,
} from "@mdi/js";

const props = defineProps({
  titulo: String,
  routeName: String,
  departamentos: Array,
  marcasPorTipo: Object,
  usuariosArqueo: Array,
});
const form = useForm({
  nombre_persona: '',
  departamento_id: '',
  puesto:'',
  tipo_pc: '',
  marca_equipo: '',
  sistema_operativo: '',
  procesador: '',
  tarjeta_madre: '',
  tarjeta_grafica: '',
  datos_tarjeta_grafica: '',
  tipo_ram: '',
  capacidad_ram: '',
  marca_ram: '',
  tipo_disco: '',
  capacidad_disco: '',
  teclado_mouse: '',
  camara_web: '',
  otro_periferico: '',
  software_remoto: '',
  id_remoto: '',
  password_remoto: '',
  user_id: '',
  observaciones: '',
});

const handleSubmit = () => {
  form.post(route(`${props.routeName}store`));
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

    <CardBox form @submit.prevent="handleSubmit" class="space-y-4">

      <Accordion :sections="sections">

        <!-- Datos Generales -->
        <template #datos-generales>
          <FormField label="Nombre de Persona" :error="form.errors.nombre_persona">
            <FormControl v-model="form.nombre_persona" type="text" :icon="mdiAccount" required
              @input="form.nombre_persona = form.nombre_persona.toUpperCase()" />
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
            <FormControl v-model="form.sistema_operativo" type="text" :icon="mdiMicrosoftWindows" required
              @input="form.sistema_operativo = form.sistema_operativo.toUpperCase()" />
          </FormField>

          <FormField label="Procesador" :error="form.errors.procesador">
            <FormControl v-model="form.procesador" type="text" :icon="mdiChip" required
              @input="form.procesador = form.procesador.toUpperCase()" />
          </FormField>
        </template>

        <!-- Hardware -->
        <template #hardware>
          <FormField label="Tarjeta Madre" :error="form.errors.tarjeta_madre">
            <FormControlSelect v-model="form.tarjeta_madre" type="select" :icon="mdiChip"
              :options="props.marcasPorTipo.tarjeta_madre.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Tarjeta Gráfica" :error="form.errors.tarjeta_grafica">
            <FormControlSelect v-model="form.tarjeta_grafica" type="select" :icon="mdiChip" :options="[
              { value: 'Integrada al procesador', text: 'INTEGRADA AL PROCESADOR' },
              { value: 'Externa', text: 'EXTERNA' }
            ]" required />
          </FormField>

          <FormField label="Datos Tarjeta Gráfica" :error="form.errors.datos_tarjeta_grafica">
            <FormControl v-model="form.datos_tarjeta_grafica" type="text" :icon="mdiChip"
              @input="form.datos_tarjeta_grafica = form.datos_tarjeta_grafica.toUpperCase()" />
          </FormField>

          <FormField label="Tipo de RAM" :error="form.errors.tipo_ram">
            <FormControlSelect v-model="form.tipo_ram" type="select" :icon="mdiMemory" :options="[
              { value: 'DDR2', text: 'DDR2' },
              { value: 'DDR3', text: 'DDR3' },
              { value: 'DDR4', text: 'DDR4' },
              { value: 'DDR5', text: 'DDR5' }
            ]" required />
          </FormField>

          <FormField label="Capacidad RAM" :error="form.errors.capacidad_ram">
            <FormControlSelect v-model="form.capacidad_ram" type="select" :icon="mdiMemory" :options="[
              { value: '2 GB', text: '2 GB' },
              { value: '4 GB', text: '4 GB' },
              { value: '8 GB', text: '8 GB' },
              { value: '16 GB', text: '16 GB' },
              { value: '32 GB', text: '32 GB' }
            ]" required />
          </FormField>

          <FormField label="Marca RAM" :error="form.errors.marca_ram">
            <FormControlSelect v-model="form.marca_ram" type="select" :icon="mdiMemory"
              :options="props.marcasPorTipo.marca_ram.map(m => ({ value: m.nombre, text: m.nombre }))" required />
          </FormField>

          <FormField label="Tipo de Disco" :error="form.errors.tipo_disco">
            <FormControlSelect v-model="form.tipo_disco" type="select" :icon="mdiHarddisk" :options="[
              { value: 'SSD', text: 'SSD' },
              { value: 'M2', text: 'M2' },
              { value: 'HDD', text: 'HDD' },
              { value: 'HDD & SSD', text: 'HDD & SSD' }
            ]" required />
          </FormField>

          <FormField label="Capacidad Disco" :error="form.errors.capacidad_disco">
            <FormControlSelect v-model="form.capacidad_disco" type="select" :icon="mdiHarddisk" :options="[
              { value: 'Menos de 100 GB', text: 'Menos de 100 GB' },
              { value: '120 GB', text: '120 GB' },
              { value: '256 GB', text: '256 GB' },
              { value: '512 GB', text: '512 GB' },
              { value: '1 TB', text: '1 TB' }
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
            <FormControl v-model="form.otro_periferico" type="text" :icon="mdiUsb"
              @input="form.otro_periferico = form.otro_periferico.toUpperCase()" />
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
            <FormControl v-model="form.password_remoto" type="password" placeholder="Contraseña" required />
          </FormField>
        </template>

        <!-- Otros -->
        <template #otros>
          <FormField label="Nombre Arqueo" :error="form.errors.user_id">
            <FormControlSelect v-model="form.user_id" type="select" :icon="mdiAccount"
              :options="props.usuariosArqueo.map(u => ({ value: u.id, text: u.name }))" required />
          </FormField>

          <FormField label="Observaciones" :error="form.errors.observaciones">
            <FormControl v-model="form.observaciones" type="text" :icon="mdiCommentTextOutline" required
              @input="form.observaciones = form.observaciones.toUpperCase()" />
          </FormField>
        </template>

      </Accordion>

      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Guardar" />
          <BaseButton :href="route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>

    </CardBox>
  </LayoutMain>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  max-height: 0;
}
</style>
