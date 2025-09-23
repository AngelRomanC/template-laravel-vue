<script setup>
import LayoutMain from '@/layouts/LayoutMain.vue'
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import BaseButton from "@/components/BaseButton.vue"
import BaseButtons from "@/components/BaseButtons.vue";
import FormField from "@/components/FormField.vue"
import FormControl from "@/components/FormControl.vue"
import NotificationBar from "@/components/NotificationBar.vue"
import CardBox from "@/components/CardBox.vue";
import { useForm } from '@inertiajs/vue3'

// Íconos
import {
  mdiAccount,
  mdiAccountCircle,
  mdiAccountTie,
  mdiPhone,
  mdiMail
} from '@mdi/js'

const props = defineProps({
  usuario: Object,
  routeName: String,
  titulo: String,
})

const form = useForm({
  name: props.usuario.name,
  apellido_paterno: props.usuario.apellido_paterno,
  apellido_materno: props.usuario.apellido_materno,
  numero: props.usuario.numero,
  email: props.usuario.email,
})

const guardar = () => {
  form.put(route(`${props.routeName}update`, props.usuario.id))
}
</script>
<template>
  <LayoutMain>
    <SectionTitleLineWithButton :title="props.titulo" main />

    <NotificationBar
      v-if="$page.props.flash.message"
      color="success"
      :icon="'mdi-information'"
      :outline="false"
    >
      {{ $page.props.flash.message }}
    </NotificationBar>

    <CardBox form @submit.prevent="guardar">
      <FormField :error="form.errors.name" label="Nombre">
        <FormControl v-model="form.name" type="text" required :icon="mdiAccount" />
      </FormField>

      <FormField :error="form.errors.apellido_paterno" label="Apellido Paterno">
        <FormControl v-model="form.apellido_paterno" type="text" required :icon="mdiAccountCircle" />
      </FormField>

      <FormField :error="form.errors.apellido_materno" label="Apellido Materno">
        <FormControl v-model="form.apellido_materno" type="text" required :icon="mdiAccountTie" />
      </FormField>

      <FormField :error="form.errors.numero" label="Número Telefónico">
        <FormControl
          v-model="form.numero"
          required
          placeholder="Teléfono"
          type="tel"
          maxlength="10"
          pattern="^\d{10}$"
          title="El número debe contener exactamente 10 dígitos"
          :icon="mdiPhone"
        />
      </FormField>

      <FormField :error="form.errors.email" label="Correo Electrónico">
        <FormControl v-model="form.email" type="email" required :icon="mdiMail" />
      </FormField>

      <BaseButtons>
        <BaseButton @click="guardar" type="submit" color="info" outline label="Actualizar" />
        <BaseButton :href="route(`${routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
      </BaseButtons>
    </CardBox>
  </LayoutMain>
</template>
