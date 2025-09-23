<script setup>
import {
    mdiAccount,
    mdiMail,
    mdiAsterisk,
    mdiFormTextboxPassword,
    mdiGithub,
} from "@mdi/js";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-slate-100">Información personal</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">
                Actualice la información del perfil y la dirección de correo electrónico de su cuenta.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <FormField label="Nombre" help="Requerido: Tu nombre">
                <FormControl v-model="form.name" :icon="mdiAccount" name="username" required autocomplete="username" />
            </FormField>
            <InputError class="mt-2" :message="form.errors.name" />

            <FormField label="Correo Electrónico" help="Requerido: Tu email">
                <FormControl v-model="form.email" :icon="mdiMail" name="useremail" required autocomplete="useremail" />
            </FormField>
            <InputError class="mt-2" :message="form.errors.email" />

            <!-- <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Su dirección de correo electrónico no está verificada.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Haga clic aquí para volver a enviar el correo electrónico de verificación.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                </div>
            </div> -->

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>