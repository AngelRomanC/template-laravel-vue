<script setup>
import Swal from "sweetalert2";
import Pagination from '@/Shared/Pagination.vue';
import { router } from '@inertiajs/vue3'
import LayoutMain from '@/layouts/LayoutMain.vue';
import { mdiTagEdit, mdiDeleteOutline, mdiInformation, mdiPlus, mdiAccountCogOutline } from "@mdi/js";
import CardBox from "@/components/CardBox.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import SearchBar from '@/components/SearchBar.vue'
import { ref } from 'vue'

const props = defineProps({
    users: Object,
    titulo: String,
    routeName: String,
    filters: Object,
})
const filters = ref({
    search: props.filters?.search ?? '',
})
const eliminarAdmin = (id) => {
    console.log(id)
    Swal.fire({
        title: "¿Esta seguro?",
        text: "Esta acción no se puede revertir",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "#d33",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si!, eliminar registro!",
    }).then((res) => {
        if (res.isConfirmed) {
            router.delete(route(`${props.routeName}destroy`, id))
        }
    });
};

</script>

<template>
    <LayoutMain>
        <SectionTitleLineWithButton :title="titulo" main :icon="mdiAccountCogOutline">
            <BaseButton :href="route(`${props.routeName}create`)" color="warning" label="Crear" :icon="mdiPlus" />
        </SectionTitleLineWithButton>

        <SearchBar v-model="filters.search" :routeName="routeName" placeholder="Buscar admin por nombre..." />

        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.success }}
        </NotificationBar>

        <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.error }}
        </NotificationBar>

        <CardBox v-if="users.data.length < 1">
            <CardBoxComponentEmpty />
        </CardBox>

        <CardBox v-else class="mb-6" has-table>
            <table>
                <thead>

                    <tr>
                        <th />
                        <th>Nombre</th>
                        <th>Apellido paterno</th>
                        <th>Apellido materno</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th></th>
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <!-- Sección para users -->
                    <tr v-for="item in users.data" :key="item.id">
                        <td class="align-items-center"></td>
                        <td data-label="Nombre">{{ item.name }}</td>
                        <td data-label="Apellido paterno">{{ item.apellido_paterno }}</td>
                        <td data-label="Apellido materno">{{ item.apellido_materno }}</td>
                        <td data-label="Número">{{ item.numero }}</td>
                        <td data-label="Email">{{ item.email }}</td>
                        <td data-label="Rol">
                            <div class="flex flex-wrap gap-1">
                                <span v-for="role in item.roles" :key="role.id"
                                    class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full relative group">
                                    {{ role.name }}
                                    <span
                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 w-max p-1 text-xs bg-gray-700 text-white rounded opacity-0 group-hover:opacity-100 transition">
                                        {{ role.description }}
                                    </span>
                                </span>

                            </div>
                        </td>
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                <BaseButton color="info" :icon="mdiTagEdit" small
                                    :href="route(`${props.routeName}edit`, item.id)" />

                                <BaseButton color="danger" :icon="mdiDeleteOutline" small
                                    @click="eliminarAdmin(item.id)" />
                            </BaseButtons>
                        </td>

                    </tr>
                </tbody>
            </table>
            <Pagination :currentPage="users.current_page" :links="users.links" :total="users.links.length - 2" />
        </CardBox>

    </LayoutMain>
</template>
