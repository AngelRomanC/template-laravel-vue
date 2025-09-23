<script setup>
import { ref } from 'vue'
import LayoutMain from '@/layouts/LayoutMain.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from "@/components/BaseButtons.vue"
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import CardBox from "@/components/CardBox.vue"
import { mdiBallotOutline, mdiPlus, mdiDelete, mdiPencil } from "@mdi/js"
import Pagination from '@/Shared/Pagination.vue'
import { router } from '@inertiajs/vue3'
import Swal from "sweetalert2";
import NotificationBar from '@/components/NotificationBar.vue'


const props = defineProps({
    modules: Object,
    titulo: String,
    routeName: String
})
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
    <LayoutMain>
        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main>
            <BaseButton
                :route-name="routeName + 'create'"
                :icon="mdiPlus"
                label="Nuevo Módulo"
                color="info"
                small
            />
        </SectionTitleLineWithButton>
        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiPlus">
                 {{ $page.props.flash.success }}
        </NotificationBar>

        <CardBox class="mb-6" has-table>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="module in modules.data" :key="module.id">
                        <td>{{ module.name }}</td>
                        <td>{{ module.key }}</td>
                        <td>{{ module.description }}</td>
                        <td>
                            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                <BaseButton                                   
                                    :href="route(`${props.routeName}edit`, module.id)"
                                    color="info"
                                    label="Editar"
                                    :icon="mdiPencil"
                                    small
                                />
                                <BaseButton
                                        @click="destroy(module.id)"
                                        color="danger"
                                        :icon="mdiDelete"
                                        label="Eliminar"
                                        small
                                />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
         <Pagination :currentPage="modules.current_page" :links="modules.links" :total="modules.last_page" />

        </CardBox>
    </LayoutMain>
</template>