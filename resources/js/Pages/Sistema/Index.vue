<script setup>
import { router } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import Swal from "sweetalert2";
import { mdiTagEdit, mdiDeleteOutline, mdiInformation, mdiMagnify, mdiFilterVariant, mdiPlus, mdiApps } from "@mdi/js";
import Pagination from '@/Shared/Pagination.vue';
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import CardBox from "@/components/CardBox.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import moment from "moment";
import { ref, watch } from 'vue';
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import SearchBar from '@/components/SearchBar.vue'

const props = defineProps({
    titulo: String,
    sistemas: Object,
    routeName: String,
    filters: Object,
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
const filters = ref({ ...props.filters })
</script>

<template>
    <LayoutMain>
        <SectionTitleLineWithButton :title="props.titulo" main :icon="mdiApps">
            <BaseButton :href="route('sistema.form')" color="danger" label="Cargar Datos" />
            <BaseButton :href="route('sistemas.export', { search: filters.search })" color="success"
                label="Exportar excel" />
            <BaseButton :href="route(`${props.routeName}create`)" color="warning" label="Registrar nuevo sistema"
                :icon="mdiPlus" />
        </SectionTitleLineWithButton>

        <SearchBar v-model="filters.search" :routeName="routeName"
            placeholder="Buscar sistema por nombre o departamento..." />

        <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.success }}
        </NotificationBar>

        <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
            {{ $page.props.flash.error }}
        </NotificationBar>

        <CardBox v-if="sistemas.data.length < 1">
            <CardBoxComponentEmpty />
        </CardBox>

        <CardBox v-else class="mb-6" has-table>
            <table class="w-full border-collapse border mt-4">
                <thead>
                    <tr>
                        <th />
                        <th class="border p-2">Nombre de Sistema</th>
                        <th class="border p-2">Departamento</th>
                        <th class="border p-2">URL</th>
                        <th class="border p-2">Estatus</th>
                        <th class="border p-2">Developer</th>
                        <th class="border p-2">Acciones</th>
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="documento in sistemas.data" :key="documento.id">
                        <td class="align-items-center"></td>
                        <td data-label="Nombre de Sistema" class="border p-2">{{ documento.nombre }}</td>
                        <td data-label="Departamento" class="border p-2">{{ documento.departamento.nombre }}</td>
                        <td data-label="URL">
                            <div class="max-w-[200px] sm:max-w-[300px] lg:max-w-full">
                                <a :href="documento.url" target="_blank"
                                    class="text-blue-500 hover:underline truncate block" :title="documento.url">
                                    {{ documento.url }}
                                </a>
                            </div>
                        </td>

                        <td data-label="Estatus del sistema " class="border p-2">{{ documento.estatus }} </td>
                        <td data-label="Developer" class="border p-2">{{ documento.usuario.name }}</td>
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                <BaseButton color="info" :icon="mdiTagEdit" small
                                    :href="route(`${props.routeName}edit`, documento.id)" />
                                <BaseButton color="danger" :icon="mdiDeleteOutline" small
                                    @click="destroy(documento.id)" />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :currentPage="sistemas.current_page" :links="sistemas.links" :total="sistemas.last_page" />
        </CardBox>
    </LayoutMain>
</template>