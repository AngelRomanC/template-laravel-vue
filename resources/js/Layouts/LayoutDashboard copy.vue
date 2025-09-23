<script setup>
import BaseButton from '@/Components/BaseButton.vue';
import FooterBar from '@/Components/FooterBar.vue';
import { Link, Head, router } from "@inertiajs/vue3";
import { computed, onMounted, reactive, ref, watch } from "vue";
import NavBar from "@/components/NavBar.vue";
import NavBarItemPlain from "@/components/NavBarItemPlain.vue";

import { usePage } from '@inertiajs/inertia-vue3';


const submenuVisible = ref(true);

const showSubmenu = () => {
    submenuVisible.value = true;
};

const hideSubmenu = () => {
    submenuVisible.value = false;
};
const toggleSubmenu = () => {
    submenuVisible.value = !submenuVisible.value;
};
const logout = () => {
    router.post('logout');
}
// console.log(submenu1.value)
const goToMateriaIndex = () => {
    router.push({ name: 'materia.index' });
};
</script>


<template>
    
   
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl mx-auto p-4">
            <a :href="route('dashboard')" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="/img/logo.png" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">UPEMOR
                    Recursamientos</span>
            </a>

            <div class="flex items-center md:order-2 space-x-1 md:space-x-2 rtl:space-x-reverse">
                <BaseButton label="Cerrar sesión" color="danger" @click="logout()" />
                <button data-collapse-toggle="mega-menu-icons" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="mega-menu-icons" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div id="mega-menu-icons" class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
                <ul class="flex flex-col mt-4 font-medium md:flex-row md:mt-0 md:space-x-8 rtl:space-x-reverse">
                    
                    <li v-if="user && user.isAdmin">
                        <a :href="route('usuarios.index')"
                            class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-blue-500 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            aria-current="page">Usuarios</a>
                    </li>
                    <li v-if="user && user.isAdmin">
                        <a :href="route('grupo.index')"
                            class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-blue-500 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            aria-current="page">Grupo</a>
                    </li>
                    <li>
                        <a :href="route('recursamiento.index')"
                        class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-blue-500 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                        aria-current="page">Recursamientos</a>
                        
                    </li>
                    
                    <li class="relative" ref="catalogsMenu">
                        <button @click="toggleSubmenu" class="flex items-center justify-between w-full py-2 px-3 font-medium text-gray-900 border-b border-gray-100 md:w-auto hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">
                            Catálogos
                            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <div>
                            
                            <div @mouseover="showSubmenu"  @mouseleave="hideSubmenu" v-show="submenuVisible" @click="goToMateriaIndex">
                                <a :href="route('materia.index')"
                            class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-blue-500 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            aria-current="page">Materias</a>
                            </div>
                           
                            <div @mouseover="showSubmenu"  @mouseleave="hideSubmenu" v-show="submenuVisible" @click="goToMateriaIndex">
                                <a :href="route('periodo.index')"
                            class="block py-2 px-3 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-blue-500 md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            aria-current="page">Periodo</a>
                            </div>
                            
                        </div>
                    </li>
                      
                </ul>
            </div>
        </div>
    </nav>

    <slot />
    
</template>