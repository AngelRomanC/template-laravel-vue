<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from "@mdi/js";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import menuAside from "@/menuAside.js";
import menuNavBar from "@/menuNavBar.js";
import { useMainStore } from "@/stores/main.js";
import { useStyleStore } from "@/stores/style.js";
import BaseIcon from "@/Components/BaseIcon.vue";
import FormControl from "@/Components/FormControl.vue";
import NavBar from "@/Components/NavBar.vue";
import NavBarItemPlain from "@/Components/NavBarItemPlain.vue";
import AsideMenu from "@/Components/AsideMenu.vue";
import FooterBar from "@/Components/FooterBar.vue";
import { usePage } from "@inertiajs/vue3";

const { auth } = usePage().props;
const roles = auth.user.roles; // Esto es un array de roles asignados

const menuNavBarFiltered = menuNavBar.map(item => {
  if (item.isSwitchRole) {
    // Mostrar solo si el usuario tiene 2 roles o más
    return roles.length >= 2 ? item : null;
  }
  return item;
}).filter(Boolean); // elimina los null

useMainStore().setUser({
  name: "John Doe",
  email: "john@example.com",
  avatar:
    "https://avatars.dicebear.com/api/avataaars/example.svg?options[top][]=shortHair&options[accessoriesChance]=93",
});

const layoutAsidePadding = "xl:pl-60";

const styleStore = useStyleStore();

/* const router = useRouter();
 */
const isAsideMobileExpanded = ref(false);
const isAsideLgActive = ref(false);

/* router.beforeEach(() => {
  isAsideMobileExpanded.value = false;
  isAsideLgActive.value = false;
}); */

const menuClick = (event, item) => {
  if (item.isToggleLightDark) {
    styleStore.setDarkMode();
  }

  if (item.isLogout) {
    router.post(route("logout"));
  }
};
</script>

<template>
  <div
    :class="{
      dark: styleStore.darkMode,
      'overflow-hidden lg:overflow-visible': isAsideMobileExpanded,
    }"
  >
    <div
      :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
      class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
    >
      <NavBar
        :menu="menuNavBarFiltered"
        :class="[
          layoutAsidePadding,
          { 'ml-60 lg:ml-0': isAsideMobileExpanded },
        ]"
        @menu-click="menuClick"
      >
        <NavBarItemPlain
          display="flex lg:hidden"
          @click.prevent="isAsideMobileExpanded = !isAsideMobileExpanded"
        >
          <BaseIcon
            :path="isAsideMobileExpanded ? mdiBackburger : mdiForwardburger"
            size="24"
          />
        </NavBarItemPlain>
        <NavBarItemPlain
          display="hidden lg:flex xl:hidden"
          @click.prevent="isAsideLgActive = true"
        >
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
      
      </NavBar>
      <AsideMenu
        :is-aside-mobile-expanded="isAsideMobileExpanded"
        :is-aside-lg-active="isAsideLgActive"
        :menu="menuAside"
        @menu-click="menuClick"
        @aside-lg-close-click="isAsideLgActive = false"
      />
      <slot />
    
    </div>
  </div>
</template>
