<script setup>
import { defineProps } from 'vue';
import { useForm} from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";

import { mdiBallotOutline, mdiHomeCity, mdiText, mdiMapMarker,mdiPhone ,mdiEmail} from "@mdi/js"; //agregado

//const props = defineProps(['titulo', 'empresa', 'routeName']); //Recibir la persona por id

 const props = defineProps({
     titulo: String,       
     routeName: String,    
     departamento: Object       
 });

const form = useForm({ ...props.departamento});

const guardar = () => {
    form.put(route(`${props.routeName}update`, props.departamento.id));
};

</script>

<template>
    <LayoutMain :title="titulo">
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main>
           
        </SectionTitleLineWithButton>

        <CardBox form @submit.prevent="guardar">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Campo: Nombre -->
                <FormField label="Nombre" :error="form.errors.nombre">
                    <FormControl
                        v-model="form.nombre"
                        type="text"
                        placeholder="Nombre de área"
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
                    <BaseButton @click="guardar" type="submit" color="info" outline label="Actualizar" />
                    <BaseButton :href="route(`${routeName}index`)" type="reset" color="danger" outline
                        label="Cancelar" />
                </BaseButtons>
            </template>
        </CardBox>
    </LayoutMain>
</template>
