<template>
  <div v-if="pages.length > 1" class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <BaseLevel>
      <BaseButtons>
        <BaseButton
          v-for="(link, index) in pages"
          :key="index"
          :active="link.active"
          :label="link.label"
          :href="link.url"
          @click="handleClick(link, index)"
          :color="link.active ? 'lightDark' : 'whiteDark'"
          small
        />
      </BaseButtons>
      <small>Página {{ currentPage }} de {{ total }}</small>
    </BaseLevel>
  </div>

  <div class="vl-parent">
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="false" />
  </div>
</template>

<script>
import BaseLevel from "@/Components/BaseLevel.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import { ref, computed } from "vue";

export default {
  props: {
    links: Array,
    total: Number,
    currentPage: Number,
  },
  components: {
    BaseLevel,
    BaseButtons,
    BaseButton,
    Loading,
  },
  setup(props) {
    const isLoading = ref(false);

    // Filtramos los enlaces numéricos válidos
    const pages = computed(() => {
      return props.links.filter((link) => Number.isInteger(parseInt(link.label)));
    });

    const handleClick = (link, index) => {
      if (!link.url || link.active) return;
      isLoading.value = true;
    };

    return {
      isLoading,
      pages,
      handleClick,
    };
  },
};
</script>
