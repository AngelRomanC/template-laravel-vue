<script setup>
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import BaseButton from '@/Components/BaseButton.vue'

const props = defineProps({
  catalogRouteName: String, // por ejemplo: 'tipo-documento'
  returnRoute: String, // por ejemplo: 'documentos.edit'
  returnId: [String, Number], // el ID del documento actual
  label: String,
  icon: String,
  color: {
    type: String,
    default: 'info',
  },
  small: {
    type: Boolean,
    default: true,
  }
})

const redirectUrl = computed(() => {
  if (props.returnId) {
    return route(props.returnRoute, props.returnId)
  }
  return route(props.returnRoute)  // Si no hay ID, solo retorna la ruta sin parámetro.
})

function redirectToCatalog() {
     // Imprime el valor de redirectUrl.value en la consola
  console.log('Redirect URL:', redirectUrl.value);
  router.get(route(`${props.catalogRouteName}.create`, {
    redirect: redirectUrl.value // Pasa la URL de retorno como parámetro.
  }))
    
}



</script>

<template>
  <BaseButton
    :icon="icon"
    :color="color"
    :small="small"
    :title="label"
    @click="redirectToCatalog"
  />
</template>
