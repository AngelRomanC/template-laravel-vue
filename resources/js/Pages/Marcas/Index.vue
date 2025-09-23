<script setup>
import LayoutMain from '@/layouts/LayoutMain.vue'
import SectionTitleLineWithButton from '@/components/SectionTitleLineWithButton.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import CardBox from '@/components/CardBox.vue'
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import NotificationBar from '@/components/NotificationBar.vue'
import { mdiPlus, mdiTagEdit, mdiDelete, mdiLaptop   } from '@mdi/js'
import { router } from '@inertiajs/vue3'
import Swal from "sweetalert2";
import Pagination from '@/Shared/Pagination.vue';
import SearchBar from '@/components/SearchBar.vue'




const props = defineProps({
  marcas: Object,
  routeName: String,
  filters: Object,

})

const destroy2 = (id) => {
  if (confirm('¿Estás seguro de eliminar esta marca?')) {
    router.delete(route('marcas.destroy', id))
  }
}

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
  <LayoutMain title="Marcas">
    <SectionTitleLineWithButton title="Catálogo de Marcas" main :icon="mdiLaptop   ">
      <BaseButton :href="route(`${props.routeName}create`)" :icon="mdiPlus" color="warning" label="Nueva Marca" />
    </SectionTitleLineWithButton>

    <SearchBar v-model="filters.search" :routeName="routeName" placeholder="Buscar licitación por nombre..." />

    <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiPlus">
      {{ $page.props.flash.success }}
    </NotificationBar>

    <CardBox v-if="marcas.data.length < 1">
      <CardBoxComponentEmpty />

    </CardBox>

    <CardBox v-else class="mb-6" has-table>
      <table class="w-full border-collapse border text-sm">
        <thead>
          <tr >
            <th class="border px-4 py-2">#</th>
            <th class="border px-4 py-2">Nombre</th>
            <th class="border px-4 py-2">Tipos Asociados</th>
            <th class="border px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(marca, index) in props.marcas.data" :key="marca.id">
            <td data-label="#" class="border px-4 py-2">{{ index + 1 }}</td>
            <td data-label="Nombre" class="border px-4 py-2">{{ marca.nombre }}</td>
            <td data-label="Asociados" class="border px-4 py-2">
              <ul class="list-disc ml-4">
                <li v-for="tipo in marca.tipos" :key="tipo.id">
                  {{ tipo.tipo.replace('_', ' ') }}
                </li>
              </ul>
            </td>
            <td class="border px-4 py-2 whitespace-nowrap">
              <BaseButtons>
                <BaseButton :href="route(`${props.routeName}edit`, marca.id)" :icon="mdiTagEdit" small
                  color="warning" />
                <BaseButton @click="destroy(marca.id)" :icon="mdiDelete" small color="danger" />
              </BaseButtons>
            </td>
          </tr>
        </tbody>
      </table>
      <Pagination :currentPage="marcas.current_page" :links="marcas.links" :total="marcas.links.length - 2" />
    </CardBox>
  </LayoutMain>
</template>
