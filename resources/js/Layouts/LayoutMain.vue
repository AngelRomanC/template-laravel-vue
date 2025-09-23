<script setup>

import SectionMain from "@/Components/SectionMain.vue";
import LayoutDashboard from "@/Layouts/LayoutDashboard.vue";


</script>

<template>
    <LayoutDashboard>

        <SectionMain>
            <slot />

        </SectionMain>
    </LayoutDashboard>
</template>
