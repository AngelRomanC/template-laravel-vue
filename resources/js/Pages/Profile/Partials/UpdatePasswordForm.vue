<script setup>
import {
    mdiAccount,
    mdiMail,
    mdiAsterisk,
    mdiFormTextboxPassword,
    mdiGithub,
    mdiLock,
} from "@mdi/js";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-slate-100">Actualizar contraseña</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">
                Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantenerse segura.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            
            <FormField label="Contraseña Antigüa" help="Requerido: Contraseña Antigüa">
                <FormControl v-model="form.current_password" :icon="mdiLock" type="password" name="current_password" required autocomplete="current_password" />
            </FormField>
            <InputError class="mt-2" :message="form.errors.current_password" />

            <FormField label="Contraseña Nueva" help="Requerido: Contraseña Nueva">
                <FormControl v-model="form.password" :icon="mdiLock " name="password" type="password" required autocomplete="password" />
            </FormField>
            <InputError class="mt-2" :message="form.errors.password" />

            <FormField label="Contraseña Confirmación" help="Requerido: Contraseña Confirmación">
                <FormControl v-model="form.password_confirmation" :icon="mdiLock " type="password" name="password_confirmation" required autocomplete="password_confirmation" />
            </FormField>
            <InputError class="mt-2" :message="form.errors.password_confirmation" />

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>