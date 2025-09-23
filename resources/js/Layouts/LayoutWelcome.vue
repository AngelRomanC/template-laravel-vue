<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from "@mdi/js";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import menuAside from "@/menuAside.js";
import menuNavBarGuest from "@/menuNavBarGuest";
import { useMainStore } from "@/stores/main.js";
import { useStyleStore } from "@/stores/style.js";
import BaseIcon from "@/components/BaseIcon.vue";
import FormControl from "@/components/FormControl.vue";
import NavBar from "@/components/NavBar.vue";
import NavBarItemPlain from "@/components/NavBarItemPlain.vue";
import AsideMenu from "@/components/AsideMenu.vue";
import FooterBar from "@/components/FooterBar.vue";

useMainStore().setUser({
  name: "Invitado",
  email: "john@example.com",
  avatar:
    "https://avatars.dicebear.com/api/avataaars/example.svg?options[top][]=shortHair&options[accessoriesChance]=93",
});

const layoutAsidePadding = "xl:pl-16";

const styleStore = useStyleStore();


const isAsideMobileExpanded = ref(false);
const isAsideLgActive = ref(false);

const menuClick = (event, item) => {
  if (item.isToggleLightDark) {
    styleStore.setDarkMode();
  }

  if (item.isLogin) {
    router.get(route("login"));
  }

  if (item.isRegister) {
    router.get(route("register"));
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
      :class="{ 'ml-60 lg:ml-0': isAsideMobileExpanded }"
      class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
    >
      <NavBar
        :class="layoutAsidePadding"
        :menu="menuNavBarGuest"
        @menu-click="menuClick"
      >
      
        <NavBarItemPlain
          display="hidden lg:flex xl:hidden"
          @click.prevent="isAsideLgActive = true"
        >
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
        
        <NavBarItemPlain use-margin>
          <!-- <FormControl
            placeholder="Buscar (ctrl+k)"
            ctrl-k-focus
            transparent
            borderless
          /> -->
        </NavBarItemPlain>
      </NavBar>
      <slot />
    
    </div>
  </div>
</template>
