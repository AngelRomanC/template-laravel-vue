<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Importar Inventario desde Excel</h1>
    <form @submit.prevent="submit">
      <input type="file" @change="handleFile" accept=".xlsx,.xls,.csv" />
      <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2">Importar</button>
    </form>
  </div>
</template>

<script setup>
import LayoutMain from '@/layouts/LayoutMain.vue' // <-- Asegúrate que esté correcto
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

defineOptions({ layout: LayoutMain })

const archivo = ref(null)
const form = useForm({ archivo: null })

function handleFile(e) {
  archivo.value = e.target.files[0]
  form.archivo = archivo.value
}

function submit() {
  form.post(route('sistema.importar'), {
    onSuccess: () => {
      alert('Importación exitosa')
    },
    onError: () => {
      alert('Error al importar')
    }
  })
}
</script>

