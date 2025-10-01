<script setup>

import { useForm, Head, Link } from '@inertiajs/vue3'
import { mdiAccount, mdiAsterisk,mdiAccountCircleOutline,mdiKey } from '@mdi/js'
import LayoutGuest from '@/Layouts/LayoutGuest.vue'
import SectionFullScreen from '@/Components/SectionFullScreen.vue'
import CardBox from '@/Components/CardBox.vue'
import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue'
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import FormValidationErrors from '@/Components/FormValidationErrors.vue'
import NotificationBarInCard from '@/Components/NotificationBarInCard.vue'
import BaseLevel from '@/Components/BaseLevel.vue'

/* defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
}; */
const props = defineProps({
  canResetPassword: Boolean,
  status: {
    type: String,
    default: null
  }
})

const form = useForm({
  email: '',
  password: '',
  remember: []
})

const submit = () => {
  form
    .transform(data => ({
      ... data,
      remember: form.remember && form.remember.length ? 'on' : ''
    }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
    })
}
</script>

<template>
<LayoutGuest>
    <Head title="Login" />

    <SectionFullScreen
      v-slot="{ cardClass }"
      bg="purplePink"

    >
      <CardBox
        :class="cardClass"
        is-form
        @submit.prevent="submit"

      >
        <FormValidationErrors />

        <NotificationBarInCard 
          v-if="status"
          color="info"
        >
          {{ status }}
        </NotificationBarInCard>
       
        <img src="/storage/imagenes/pry.png" style="width: 200px; height: auto;display: block; margin: 0 auto;">   

      
      
    
        <FormField
        label="Usuario:     "
        label-for="email"
        help="Porfavor introduce tu email"
        className="text-center"
        
      >
          <FormControl
            v-model="form.email"
            :icon="mdiAccountCircleOutline"
            id="email"
            autocomplete="email"
            type="email"
            required
          />
        </FormField>

        <FormField
          label="Contraseña:"
          label-for="Contraseña"
          help="Porfavor introduce tu contraseña"
          className="text-center"
        >
          <FormControl
            v-model="form.password"
            :icon="mdiKey"
            type="password"
            id="password"
            autocomplete="current-password"
            required
          />
        </FormField>

      

        <BaseDivider />

        <BaseLevel>
          <div class="flex justify-center">
            <BaseButtons>
                <BaseButton
                    type="submit"
                    class="bg-gray-600 hover:bg-gray-400 py-2 px-4 rounded"
                    label="Iniciar sesión"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                />
                <BaseButton
                    
                    route-name="password.request"
                    class="bg-red-600 hover:bg-red-600 py-2 px-4 rounded"
                    outline
                    label="Olvidaste tu contraseña?"
                />
            </BaseButtons>
        </div>
        
          
          
        </BaseLevel>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
   
</template>
