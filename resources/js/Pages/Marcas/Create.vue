<script setup>
import { useForm } from '@inertiajs/vue3'
import LayoutMain from '@/layouts/LayoutMain.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import CardBox from '@/components/CardBox.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import BaseButton from '@/components/BaseButton.vue'
import FormControlCheckbox from '@/components/FormControlCheckbox.vue'

const props = defineProps({
  tiposDisponibles: Array,
  routeName: String,

})

const form2 = useForm({
  nombre: '',
  tipos: []
})
const form = useForm({
  nombre: '',
  tipos: props.tiposDisponibles.reduce((acc, tipo) => {
    acc[tipo] = false
    return acc
  }, {})
})

const handleSubmit = () => {
  const tiposSeleccionados = Object.entries(form.tipos)
    .filter(([_, checked]) => checked)
    .map(([tipo]) => tipo)

  form.transform(data => ({
    ...data,
    tipos: tiposSeleccionados,
  })).post(route(`${props.routeName}store`))
}

</script>

<template>
  <LayoutMain title="Registrar Marca">
    <SectionTitleLineWithButton title="Registrar Nueva Marca" main />

    <CardBox form @submit.prevent="handleSubmit">
      <FormField label="Nombre de la Marca" :error="form.errors.nombre">
        <FormControl v-model="form.nombre" type="text" required  @input="form.nombre = form.nombre.toUpperCase()" />
      </FormField>


      <FormField label="Tipos Asociados" :error="form.errors.tipos">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
          <label v-for="tipo in props.tiposDisponibles" :key="tipo" class="inline-flex items-center space-x-2">
            <FormControlCheckbox v-model="form.tipos[tipo]" />
            <span class="capitalize">{{ tipo.replace('_', ' ') }}</span>
          </label>
        </div>
      </FormField>


      <template #footer>
        <BaseButtons>
          <BaseButton @click="handleSubmit" type="submit" color="info" label="Guardar" outline />
          <BaseButton :href="route('marcas.index')" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>
