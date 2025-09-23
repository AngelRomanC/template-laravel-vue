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
          @click.prevent="handleClick(link)"
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
import { router } from '@inertiajs/vue3'
import BaseLevel from "@/components/BaseLevel.vue"
import BaseButtons from "@/components/BaseButtons.vue"
import BaseButton from "@/components/BaseButton.vue"
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css'
import { ref, computed } from "vue"

export default {
  props: {
    links: Array,
    total: Number,
    currentPage: Number,
    pageParam: {
      type: String,
      default: 'page'
    },
    routeName: {
      type: String,
      default: 'dashboard'
    }
  },
  components: {
    BaseLevel,
    BaseButtons,
    BaseButton,
    Loading
  },
  setup(props) {
    const isLoading = ref(false)

    // Filtramos solo los enlaces numéricos como en el componente que funciona
    const pages = computed(() => {
      return props.links.filter(link => {
        // Excluimos los enlaces de "Anterior" y "Siguiente"
        const label = link.label?.toString().trim()
        return label && !isNaN(label) && link.url
      })
    })

    const handleClick = (link) => {
      if (!link.url || link.active) return
      
      isLoading.value = true
      
      // Obtenemos los parámetros actuales de la URL
      const currentParams = Object.fromEntries(new URLSearchParams(window.location.search).entries())
      
      // Obtenemos el número de página del enlace clickeado
      const urlParams = new URLSearchParams(new URL(link.url).search)
      const selectedPage = urlParams.get(props.pageParam)
      
      // Actualizamos el parámetro de página correspondiente
      currentParams[props.pageParam] = selectedPage

      router.get(
        route(props.routeName),
        currentParams,
        {
          preserveState: true,
          preserveScroll: true,
          onFinish: () => {
            isLoading.value = false
          }
        }
      )
    }

    return { isLoading, pages, handleClick }
  }
}
</script>