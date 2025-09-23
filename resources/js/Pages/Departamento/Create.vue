<script setup>
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import { mdiBallotOutline, mdiAccount, mdiHomeCity,mdiEmail} from "@mdi/js"; //agregado

const props = defineProps({
    titulo: String,
    routeName: String, 
});
const urlParams = new URLSearchParams(window.location.search);
const redirectParam = urlParams.get('redirect');
const form = useForm({
    nombre: '',
    email: '',
    redirect: redirectParam,
    
});
const handleSubmit = () => {
    form.post(route(`${props.routeName}store`));

};
</script>

<template>
    <LayoutMain :title="titulo">
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main>

        </SectionTitleLineWithButton>

        <CardBox form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Campo: Nombre -->
                <FormField label="Nombre" :error="form.errors.nombre">
                    <FormControl
                        v-model="form.nombre"
                        type="text"
                        placeholder="Nombre de Departamento"
                        :icon="mdiHomeCity"
                        required
                    />
                </FormField>  

                <FormField label="Correo" :error="form.errors.email">
                    <FormControl
                        v-model="form.email"
                        type="email"
                        placeholder="Correo Departamento"
                        :icon="mdiEmail"
                        required
                    />
                </FormField>              
            </div>
     
            <template #footer>
                <BaseButtons>
                    <BaseButton @click="handleSubmit" type="submit" color="info" outline label="Guardar" />
                    <BaseButton :href="redirectParam || route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>
</template>
