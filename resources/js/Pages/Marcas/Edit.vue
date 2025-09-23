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
    marca: Object,
    tiposPosibles: String,
})

const form = useForm({
    nombre: props.marca.nombre,
    tipos: props.tiposPosibles.reduce((acc, tipo) => {
        acc[tipo] = props.marca.tipos.some(t => t.tipo === tipo)
        return acc
    }, {})
})

const handleSubmit = () => {
    form.put(route('marcas.update', props.marca.id))
}
</script>

<template>
    <LayoutMain title="Editar Marca">
        <SectionTitleLineWithButton title="Editar Marca" main />

        <CardBox form @submit.prevent="handleSubmit">
            <FormField label="Nombre de la Marca" :error="form.errors.nombre">
                <FormControl v-model="form.nombre" type="text" required />
            </FormField>

            <FormField label="Tipos Asociados" :error="form.errors.tipos">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <label v-for="tipo in tiposPosibles" :key="tipo" class="inline-flex items-center space-x-2">
                        <FormControlCheckbox v-model="form.tipos[tipo]" />
                        <span class="capitalize">{{ tipo.replace('_', ' ') }}</span>
                    </label>
                </div>
            </FormField>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="handleSubmit" type="submit" color="info" label="Guardar Cambios" />
                    <BaseButton :href="route('marcas.index')" color="danger" outline label="Cancelar" />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>
</template>
