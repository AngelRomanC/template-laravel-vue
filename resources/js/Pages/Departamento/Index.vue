<script setup>
import { router } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import Swal from "sweetalert2";
import { mdiTagEdit, mdiDeleteOutline,mdiInformation, mdiPlus } from "@mdi/js";
import Pagination from '@/Shared/Pagination.vue';
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import CardBox from "@/components/CardBox.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import SearchBar from '@/components/SearchBar.vue'
import { ref } from 'vue'


const props = defineProps({
    departamentos: Object,
    titulo: String, //
    routeName:String,
    filters: Object,

});
const filters = ref({
  search: props.filters?.search ?? ''
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
            //router.delete(route("departamento.destroy", id));
            router.delete(route(`${props.routeName}destroy`, id));

        }
    });
};

</script>

<template>
    <LayoutMain>
        <SectionTitleLineWithButton :title="props.titulo" main>
            <BaseButton :href="route(`${props.routeName}create`)" color="warning" label="Crear" :icon="mdiPlus"  />
        </SectionTitleLineWithButton>

        <SearchBar   v-model="filters.search"  :routeName="routeName"  placeholder="Buscar departamento por nombre..."  />

        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.success }}
        </NotificationBar>

        <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.error }}
        </NotificationBar>

        <CardBox v-if="departamentos.data.length < 1">
            <CardBoxComponentEmpty />
        </CardBox>

        <CardBox v-else class="mb-6" has-table>
            <table class="w-full border-collapse border mt-4">
                <thead>
                    <tr>
                        <th />
                        <th class="border p-2">Nombre de Departamento</th>  
                        <th class="border p-2">Acciones</th>               
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="departamento in departamentos.data" :key="departamento.id">
                        <td class="align-items-center">
                        </td>
                        <td data-label="Nombre"class="border p-2">{{ departamento.nombre }}</td>
                       
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                <BaseButton color="info" :icon="mdiTagEdit" small
                                     :href="route(`${props.routeName}edit`,departamento.id)" />                             
                                <BaseButton color="danger" :icon="mdiDeleteOutline" small @click="destroy(departamento.id)" />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :currentPage="departamentos.current_page" :links="departamentos.links" :total="departamentos.last_page" />
        </CardBox>
    </LayoutMain>
</template>
 