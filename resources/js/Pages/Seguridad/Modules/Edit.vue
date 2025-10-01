<script setup>
import { useForm } from '@inertiajs/vue3'
import LayoutMain from '@/Layouts/LayoutMain.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from "@/Components/BaseButtons.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from "@/Components/FormField.vue"
import FormControl from "@/Components/FormControl.vue"
import { mdiBallotOutline,mdiTagTextOutline, mdiKeyVariant, mdiTextShort } from "@mdi/js"

const props = defineProps({
    module: Object,
    titulo: String,
    routeName: String
})

const form = useForm({
    name: props.module.name,
    key: props.module.key,
    description: props.module.description
})

const submit = () => {
    form.put(route(props.routeName + 'update', props.module.id))
}
</script>

<template>
    <LayoutMain>
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main />

        <CardBox is-form @submit.prevent="submit">
            <FormField label="Nombre" :error="form.errors.name">
                <FormControl v-model="form.name" type="text" required :icon="mdiTagTextOutline" />
            </FormField>

            <FormField label="Clave" :error="form.errors.key">
                <FormControl v-model="form.key" type="text" required  :icon="mdiKeyVariant" />
            </FormField>

            <FormField label="Descripción" :error="form.errors.description">
                <FormControl v-model="form.description" type="textarea"     :icon="mdiTextShort" />
            </FormField>

            <BaseButtons>
                <BaseButton
                    type="submit"
                    color="info"
                    label="Actualizar"
                    :loading="form.processing"
                    outline
                />
                <BaseButton
                    :route-name="routeName + 'index'"
                    color="danger"
                    outline
                    label="Cancelar"
                />
            </BaseButtons>
        </CardBox>
    </LayoutMain>
</template>