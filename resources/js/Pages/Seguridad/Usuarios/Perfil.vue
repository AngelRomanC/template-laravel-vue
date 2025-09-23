<script setup>
import { ref, defineProps, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import Swal from 'sweetalert2';

const props = defineProps(['titulo', 'usuario','alumno','profesor', 'routeName']);

const form = useForm({
            ...props.usuario,
         ...props,
      
          
        });

const guardar = () => {
    form.post(route("usuarios.update-perfil"));
};

</script>

<template>
    <LayoutMain >

        <SectionTitleLineWithButton  :title="titulo" main> </SectionTitleLineWithButton>
          
        

        <CardBox form @submit.prevent="guardar">
            <FormField>
                <FormControl v-model="form.name" placeholder="nombre" />
                <FormControl v-model="form.apellido_paterno" placeholder="apellido_paterno" />
                <FormControl v-model="form.apellido_materno" placeholder="apellido_materno" />
                <FormControl v-model="form.numero" placeholder="numero" />
                <FormControl v-model="form.email" placeholder="email" />
            </FormField>
            
                <div v-if="profesor">
                    <FormField :error="form.errors.grado_academico">
                        <select v-model="form.profesor.grado_academico"  class="w-full">
                            <option disabled value="">Selecciona tu grado academico</option>
                            <option>Educación superior</option> <option>Maestria</option><option>Doctorado</option> 
                        </select>
                              
                    </FormField>
                    <FormField :error="form.errors.area">
                        <select v-model="form.profesor.area"  class="w-full">
                            <option disabled value="">Selecciona tu area </option>
                            <option>Programación y Desarrollo de Software</option> <option>Redes de Computadoras</option><option>Sistemas Operativos</option> 
                            <option>Bases de Datos</option> <option>Ciberseguridad</option><option>Desarrollo Web</option> 
                            <option>Inteligencia Artificial y Aprendizaje Automático</option> <option>Gestión de Proyectos de TI</option><option>Tecnologías Emergentes</option> 
        
                        </select>    
                    </FormField>  
                </div>
            
            <FormField >
                <div v-if="alumno">
                    <FormField :error="form.errors.cuatrimestre">
                        <select v-model="form.alumno.cuatrimestre">
                            <option disabled value="">Selecciona el cuatrimestre</option>
                            <option>1</option> <option>2</option><option>3</option> <option>4</option> <option>5</option><option>6</option>
                            <option>7</option> <option>8</option><option>9</option><option>10</option>
                        </select>
                        <FormField :error="form.errors.matricula">
                      
                            <FormControl v-model="form.alumno.matricula" placeholder="matricula" />
                            
                        </FormField>
                    </FormField>
                </div>
            </FormField>

            <template #footer>
                <BaseButtons>
                    <BaseButton @click="guardar" type="submit" color="info" label="Actualizar" />
                </BaseButtons>
            </template> 
        </CardBox>
        
    </LayoutMain>
</template>

