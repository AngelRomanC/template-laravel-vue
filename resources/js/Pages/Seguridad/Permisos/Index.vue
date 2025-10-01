<script setup>
import { ref } from 'vue';
import Layout from '@/Layouts/LayoutAuthenticated.vue';
import { Link } from '@inertiajs/vue3';
import BaseButton from '@/Components/BaseButton.vue';
import BaseButtons from "@/Components/BaseButtons.vue";
import CardBox from "@/Components/CardBox.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import { mdiBallotOutline, mdiPencil,mdiDelete,mdiPlus,mdiAccountCogOutline  } from "@mdi/js";
import Swal from "sweetalert2";
import Pagination from '@/Shared/Pagination.vue';
import { router } from '@inertiajs/vue3'
import NotificationBar from '@/Components/NotificationBar.vue'




const props = defineProps({
    permisos: Object,
    titulo: String,
    routeName: String
});
const destroy = (id) => {
  Swal.fire({
    title: "¿Está seguro?",
    text: "Esta acción no se puede revertir",
    icon: "warning",
    showCancelButton: true,
    cancelButtonColor: "#d33",
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Sí, eliminar registro!",
  }).then((res) => {
    if (res.isConfirmed) {
      router.delete(route(`${props.routeName}destroy`, id));
    }
  });
};
</script>

<template>
    <Layout>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo">
                <BaseButton                 
                    :href="route(`${props.routeName}create`)"
                    :icon="mdiPlus"
                    label="Nuevo Permiso"
                    color="info"
                    :small="true"
                    
                />
            </SectionTitleLineWithButton>

            <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiPlus">
                {{ $page.props.flash.success }}
            </NotificationBar>

            <CardBox class="mb-6">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="px-3 py-3 text-left">Nombre</th>
                            <th class="px-3 py-3 text-left">Descripción</th>
                            <th class="px-3 py-3 text-left">Key-Módulo</th>
                            <th class="px-3 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="permiso in permisos.data" :key="permiso.id" class="border-b">
                            <td class="px-3 py-2">{{ permiso.name }}</td>
                            <td class="px-3 py-2">{{ permiso.description }}</td>
                            <td class="px-3 py-2">{{ permiso.module_key }}</td>
                            <td class="px-3 py-2 text-center">
                                <BaseButtons >
                                    <BaseButton
                                        :href="route(`${props.routeName}edit`, permiso.id)"
                                        color="info"
                                        :icon="mdiPencil"
                                        small
                                        label="Editar"
                                    />
                                    <BaseButton
                                        @click="destroy(permiso.id)"
                                        color="danger"
                                        :icon="mdiDelete"
                                        small
                                        label="Eliminar"
                                    />
                                </BaseButtons>
                            
                            </td>
                        </tr>
                    </tbody>
                </table>
             <Pagination :currentPage="permisos.current_page" :links="permisos.links" :total="permisos.last_page" />

            </CardBox>
        </SectionMain>
    </Layout>
</template>