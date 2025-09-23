<script setup>
import { router } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import Swal from "sweetalert2";
import { mdiTagEdit, mdiDeleteOutline, mdiInformation, mdiFilePdfBox, mdiPlus, mdiClipboardList } from "@mdi/js";
import Pagination from '@/Shared/Pagination.vue';
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import CardBox from "@/components/CardBox.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import SearchBar from '@/components/SearchBar.vue'
import { ref } from 'vue'

import { Link } from '@inertiajs/vue3'


const props = defineProps({
  inventarios: Object,
  titulo: String,
  routeName: String,
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
      router.delete(route(`${props.routeName}destroy`, id));
    }
  });
};
// Función para descargar PDF
const openPDF = (id) => {
  const iframe = document.createElement('iframe');
  iframe.src = route(`${props.routeName}responsiva`, id);
  iframe.style.display = 'none';
  document.body.appendChild(iframe);
  setTimeout(() => document.body.removeChild(iframe), 1000);
};

</script>

<template>
  <LayoutMain>
    <SectionTitleLineWithButton :title="props.titulo" main :icon="mdiClipboardList">
      <BaseButton :href="route('inventario.form')" color="danger" label="Cargar Datos" />
      <BaseButton :href="route('inventario.export', { search: filters.search })" color="success"
        label="Exporta excel" />
      <BaseButton :href="route(`${props.routeName}create`)" color="warning" label="Registrar Equipo" :icon="mdiPlus" />
    </SectionTitleLineWithButton>

    <SearchBar v-model="filters.search" :routeName="routeName"
      placeholder="Buscar por persona, área, tipo de pc, marca equipo, S.O. , procesador, tipo disco, capacidad disco, capacidad RAM..." />

    <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
      {{ $page.props.flash.success }}
    </NotificationBar>

    <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
      {{ $page.props.flash.error }}
    </NotificationBar>

    <CardBox v-if="inventarios.data.length < 1">
      <CardBoxComponentEmpty />
    </CardBox>

    <CardBox v-else class="mb-6" has-table>
      <table class="w-full border-collapse border mt-4 text-xs md:text-sm">
        <thead>
          <tr>
            <th class="border p-2">Nombre</th>
            <th class="border p-2">Departamento</th>
            <th class="border p-2">Tipo PC S.O.</th>
            <th class="border p-2">Procesador</th>
            <th class="border p-2">RAM</th>
            <th class="border p-2">Disco</th>
            <th class="border p-2">Fecha</th>
            <th class="border p-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in inventarios.data" :key="item.id">
            <td data-label="Nombre">{{ item.nombre_persona }}</td>
            <td data-label="Departamento">{{ item.departamento.nombre }}</td>
            <td data-label="PC S.O.">{{ item.tipo_pc }} {{ item.marca_equipo }} {{ item.sistema_operativo }}</td>
            <td data-label="Procesador">{{ item.procesador }}</td>
            <td data-label="RAM">{{ item.capacidad_ram }} {{ item.tipo_ram }}</td>
            <td data-label="Disco">{{ item.capacidad_disco }} {{ item.tipo_disco }}</td>
            <td data-label="Fecha">{{ item.fecha_registro }}</td>
            <td class="border p-2 whitespace-nowrap">
              <BaseButtons type="justify-start lg:justify-end" no-wrap>
                <BaseButton color="success" :icon="mdiFilePdfBox" small @click="openPDF(item.id)"
                  title="Descargar PDF" />
                <BaseButton color="info" :icon="mdiTagEdit" small :href="route(`${props.routeName}edit`, item.id)" />
                <BaseButton color="danger" :icon="mdiDeleteOutline" small @click="destroy(item.id)" />
              </BaseButtons>
            </td>
          </tr>
        </tbody>
      </table>

      <Pagination :currentPage="inventarios.current_page" :links="inventarios.links" :total="inventarios.last_page" />

    </CardBox>
  </LayoutMain>
</template>
