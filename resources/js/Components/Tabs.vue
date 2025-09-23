<template>
    <div>
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li v-for="(tab, index) in tabTitle" :key="index" class="mr-2">
                    <button @click="selectedTitle = tab" :class="{ 'border-b-2 border-b-gray-500 bg-gray-300': selectedTitle === tab }"
                        class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="dashboard-tab" data-tabs-target="#dashboard" type="button" aria-selected="false">{{ tab
                        }}</button>
                </li>
            </ul>
        </div>
        <div>
            <slot />
        </div>
    </div>
</template>

<script>
import { provide, ref } from 'vue';
export default {
    setup(props, { slots }) {
        const tabTitle = ref(slots.default().map((tab) => { if (!tab.props.criteria) { return tab.props.title }  }))
        const selectedTitle = ref(tabTitle.value[0])

        provide("selectedTitle", selectedTitle)
        return {
            tabTitle,
            selectedTitle
        }
    }
}
</script>
