<script setup>
import { ref, defineProps, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import LayoutMain from '@/layouts/LayoutMain.vue';
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormControlCheckbox from "@/components/FormControlCheckbox.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import {
  mdiAccount,
  mdiAccountCircle,
  mdiAccountTie,
  mdiPhone,
  mdiMail,
  mdiLock,
  mdiOfficeBuilding
} from "@mdi/js";

const props = defineProps({
  titulo: String,
  routeName: String,
  roles: Array,
  departamentos: Array,
});

const safeRoles = computed(() => props.roles || []);

const form = useForm({
  name: '',
  apellido_paterno: '',
  apellido_materno: '',
  numero: '',
  email: '',
  password: '',
  departamento_id: null,
  roles: [],
});

const guardar = () => {
  form.post(route(`${props.routeName}store`));
};
</script>

<template>
  <LayoutMain :title="titulo">
    <SectionTitleLineWithButton :title="props.titulo" main />

    <NotificationBar 
      v-if="$page.props.flash.message" 
      color="success" 
      :icon="'mdi-information'" 
      :outline="false"
    >
      {{ $page.props.flash.message }}
    </NotificationBar>

    <CardBox form @submit.prevent="guardar">
      <FormField :error="form.errors.name" label="Nombre">
        <FormControl v-model="form.name" type="text" required :icon="mdiAccount" />
      </FormField>

      <FormField :error="form.errors.apellido_paterno" label="Apellido Paterno">
        <FormControl v-model="form.apellido_paterno" type="text" required :icon="mdiAccountCircle" />
      </FormField>

      <FormField :error="form.errors.apellido_materno" label="Apellido Materno">
        <FormControl v-model="form.apellido_materno" type="text" required :icon="mdiAccountTie" />
      </FormField>

      <FormField :error="form.errors.numero" label="Número Telefónico">
        <FormControl
          v-model="form.numero"
          type="tel"
          required
          placeholder="Teléfono"
          maxlength="10"
          pattern="^\d{10}$"
          title="El número debe contener exactamente 10 dígitos"
          :icon="mdiPhone"
        />
      </FormField>

      <FormField :error="form.errors.email" label="Correo Electrónico">
        <FormControl v-model="form.email" type="email" required :icon="mdiMail" />
      </FormField>
      
      <FormField :error="form.errors.password" label="Password">
        <FormControl
          v-model="form.password"
          type="password"
          required
          placeholder="Contraseña"
          :icon="mdiLock"
        />
      </FormField>
      <!-- Departamentos solo para Procesos -->
     <FormField v-if="props.departamentos" label="Departamento" :error="form.errors.departamento_id">
        <FormControl
            v-model="form.departamento_id"
            :options="departamentos"
            type="select"
            label-key="nombre"
            value-key="id"
            :icon="mdiOfficeBuilding"
            required
          />                            
     </FormField>


      <BaseDivider />

      <!-- Rol único -->
       <FormField label="Asignar Roles" :error="form.errors.roles">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
          <label v-for="role in safeRoles" :key="role.id" class="flex items-center space-x-2 cursor-pointer">
            <input
              type="checkbox"
              :value="role.id"
              v-model="form.roles"
              class="form-checkbox text-blue-600"
            />
            <span>{{ role.name }}</span>
          </label>
        </div>

        <!-- Mostrar roles seleccionados -->
        <div v-if="form.roles.length > 0" class="mt-4 p-3 bg-blue-50 rounded-lg">
          <p class="text-sm font-medium text-blue-800">Roles seleccionados:</p>
          <div class="flex flex-wrap gap-2 mt-2">
            <span
              v-for="roleId in form.roles"
              :key="roleId"
              class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"
            >
              {{ safeRoles.find(r => r.id === roleId)?.name }}
            </span>
          </div>
        </div>
      </FormField>


      <template #footer>
        <BaseButtons>
          <BaseButton @click="guardar" type="submit" color="info" outline label="Guardar" />
          <BaseButton :href="route(`${props.routeName}index`)" type="reset" color="danger" outline label="Cancelar" />
        </BaseButtons>
      </template>
    </CardBox>
  </LayoutMain>
</template>
