<script setup>
import LayoutMain from '@/Layouts/LayoutMain.vue';
import BaseButton from '@/Components/BaseButton.vue';
import BaseButtons from "@/Components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/CardBox.vue";
import { mdiBallotOutline, mdiPlus, mdiTagEdit, mdiDelete } from "@mdi/js";
import Pagination from '@/Shared/Pagination.vue';
import { router } from '@inertiajs/vue3';
import Swal from "sweetalert2";
import NotificationBar from '@/Components/NotificationBar.vue'
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import SearchBar from '@/Components/SearchBar.vue'
import { ref } from 'vue'

const props = defineProps({
    roles: Object,
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
const filters = ref({
    search: props.filters?.search ?? '',
})
</script>

<template>
    <LayoutMain>

        <SectionTitleLineWithButton :icon="mdiBallotOutline" :title="titulo" main>
            <BaseButton :route-name="routeName + 'create'" :icon="mdiPlus" label="Nuevo Rol" color="info" small />
        </SectionTitleLineWithButton>
        <SearchBar v-model="filters.search" :routeName="routeName" placeholder="Buscar rol por nombre..." />
        
        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiPlus">
            {{ $page.props.flash.success }}
        </NotificationBar>
        
        <CardBox v-if="roles.data.length < 1">
            <CardBoxComponentEmpty />
        </CardBox>

        <CardBox class="mb-6" has-table>
            <table>
                <thead>
                    <tr>
                        
                        <th>Nombre</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles.data" :key="role.id">
                        <td>{{ role.name }}</td>
                        <td>
                            <span v-for="perm in role.permissions" :key="perm.id"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-1">
                                {{ perm.name }}
                            </span>
                        </td>
                        <td>
                            <BaseButtons  type="justify-start lg:justify-end" no-wrap>
                                <BaseButton :href="route(`${props.routeName}edit`, role.id)" color="info" label="Editar"
                                    small :icon="mdiTagEdit" />
                                <BaseButton @click="destroy(role.id)" color="danger" label="Eliminar" small
                                    :icon="mdiDelete" />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :currentPage="roles.current_page" :links="roles.links" :total="roles.last_page" />
        </CardBox>
    </LayoutMain>
</template>
