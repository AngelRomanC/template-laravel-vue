<script setup>
import { mdiLogout, mdiClose } from "@mdi/js";
import { computed } from "vue";
import { useStyleStore } from "@/stores/style.js";
import AsideMenuList from "@/Components/AsideMenuList.vue";
import AsideMenuItem from "@/Components/AsideMenuItem.vue";
import BaseIcon from "@/Components/BaseIcon.vue";

defineProps({
  menu: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["menu-click", "aside-lg-close-click"]);

const styleStore = useStyleStore();

const logoutItem = computed(() => ({
  label: "Cerrar sesión",
  icon: mdiLogout,
  color: "danger",
  isLogout: true,
}));

const menuClick = (event, item) => {
  emit("menu-click", event, item);
};

const asideLgCloseClick = (event) => {
  emit("aside-lg-close-click", event);
};
</script>

<template>
  <aside id="aside" class="lg:py-2 lg:pl-2 w-60 fixed flex z-40 top-0 h-screen transition-position overflow-hidden">
    <div :class="styleStore.asideStyle" class="lg:rounded-2xl flex-1 flex flex-col overflow-hidden dark:bg-slate-900">
      <!-- Contenedor de marca mejorado -->
      <div :class="styleStore.asideBrandStyle" class="flex flex-col items-center justify-center py-4 px-2 dark:bg-slate-900">
        <!-- Contenedor del logo + texto -->
        <div class="flex flex-col items-center space-y-2">
          <!-- Logo centrado con tamaño responsive -->
          <img src="/storage/imagenes/pry.png" alt="PRYSE" class="h-12 w-auto mx-auto">
          
          <!-- Texto con estilo profesional -->
          <span class="text-lg font-bold text-center text-gray-800 dark:text-white">
            DOCUMENTACIÓN <span class="text-blue-600">PRYSE</span>
          </span>
        </div>
      </div>

      <!-- Botón de cerrar (ajustado) -->
      <button class="absolute top-2 right-2 hidden lg:inline-block xl:hidden p-1" @click.prevent="asideLgCloseClick">
        <BaseIcon :path="mdiClose" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"/>
      </button>

      <!-- Menú -->
      <div :class="styleStore.darkMode ? 'aside-scrollbars-[slate]' : styleStore.asideScrollbarsStyle" 
           class="flex-1 overflow-y-auto overflow-x-hidden">
        <AsideMenuList :menu="menu" @menu-click="menuClick" />
      </div>
    </div>
  </aside>
</template>

