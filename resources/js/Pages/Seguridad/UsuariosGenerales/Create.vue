<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue'
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import BaseButton from "@/components/BaseButton.vue"
import BaseButtons from "@/components/BaseButtons.vue";
import FormField from "@/components/FormField.vue"
import FormControl from "@/components/FormControl.vue"
import NotificationBar from "@/components/NotificationBar.vue"
import CardBox from "@/components/CardBox.vue"

// Íconos
import {
  mdiAccount,
  mdiAccountCircle,
  mdiAccountTie,
  mdiPhone,
  mdiMail,
  mdiLock
} from '@mdi/js'

const props = defineProps({
  routeName: String,
  roles: Array,
  titulo: String,
})

const form = useForm({
  name: '',
  apellido_paterno: '',
  apellido_materno: '',
  numero: '',
  email: '',
  password: '',
  role: 'Desarrollador',
})

const submit = () => {
  form.post(route(`${props.routeName}store`))
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

    <CardBox form @submit.prevent="submit">
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
          type="tel"
          required
          maxlength="10"
          pattern="^\d{10}$"
          title="El número debe contener exactamente 10 dígitos"
          :icon="mdiPhone"
        />
      </FormField>

      <FormField :error="form.errors.email" label="Correo Electrónico">
        <FormControl v-model="form.email" type="email" required :icon="mdiMail" />
      </FormField>

      <FormField :error="form.errors.password" label="Contraseña">
        <FormControl v-model="form.password" type="password" required :icon="mdiLock" />
      </FormField>

      <input type="hidden" v-model="form.role" />

      <BaseButtons>
        <BaseButton @click="submit" type="submit" color="info" outline label="Crear" />
        <BaseButton :href="route(`${routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
      </BaseButtons>
    </CardBox>
  </LayoutMain>
</template>
