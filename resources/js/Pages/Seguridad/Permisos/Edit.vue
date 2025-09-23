<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/LayoutAuthenticated.vue';
import BaseButton from '@/Components/BaseButton.vue';
import BaseButtons from "@/Components/BaseButtons.vue";
import FormControl from "@/Components/FormControl.vue";
import FormField from "@/Components/FormField.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import { mdiBallotOutline, mdiKeyChain, mdiTextShort, mdiViewModule} from "@mdi/js";

const props = defineProps({
    permiso: Object,
    modules: Array,
    titulo: String,
    routeName: String
});

const form = useForm({
    name: props.permiso.name,
    guard_name: props.permiso.guard_name,
    description: props.permiso.description,
    module_key: props.permiso.module_key
});

const submit = () => {
    form.put(route(props.routeName + 'update', props.permiso.id));
};
</script>

<template>
    <Layout>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" />

            <CardBox is-form @submit.prevent="submit">
                
                <!-- Nombre -->
                <FormField label="Nombre" :error="form.errors.name">
                    <FormControl
                        v-model="form.name"
                        required
                        :icon="mdiKeyChain"
                    />
                </FormField>

                <!-- Descripción -->
                <FormField label="Descripción" :error="form.errors.description">
                    <FormControl
                        v-model="form.description"
                        type="textarea"
                        required
                        :icon="mdiTextShort"
                    />
                </FormField>

                <!-- Módulo -->
                <FormField label="Módulo" :error="form.errors.module_key">
                    <FormControl
                        v-model="form.module_key"
                        type="select"
                        :options="modules"
                        option-label="name"
                        valueSelect="key"
                        required
                        :icon="mdiViewModule"
                    />
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
        </SectionMain>
    </Layout>
</template>
