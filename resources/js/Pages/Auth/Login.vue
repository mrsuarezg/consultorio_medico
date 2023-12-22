<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { VNodeRenderer } from '@layouts/components/VNodeRenderer';
import { themeConfig } from '@themeConfig';
import { emailValidator, requiredValidator } from '@validators/validators.js';
import { VForm } from 'vuetify/components';

defineProps({
    canResetPassword: Boolean,
    status: String,
    errors: Object,
});

const isPasswordVisible = ref(false);
const refVForm = ref();
const form = useForm({
    email: '',
    password: '',
    remember: false
});

defineOptions({ layout: null });

function login() {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

async function onSubmit() {
    const { valid } = await refVForm.value?.validate();
    if (valid) {
        login();
    }
};
</script>

<template>
    <Head title="Iniciar sesi&oacute;n" />

    <div class="d-flex align-center justify-center h-screen bg-login">
        <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
            <VCardText>
                <div class="d-flex align-start justify-center gap-x-3">
                    <h1 class="font-weight-medium leading-normal text-2xl text-uppercase">
                        {{ themeConfig.app.title }}
                    </h1>

                    <VNodeRenderer :nodes="themeConfig.app.logo" />
                </div>

                <h5 class="text-h5 font-weight-medium mb-1" title="Sistema de seguros y fianzas">
                    Bienvenido
                </h5>
                <p class="mb-0">
                    Inicia con tus credenciales para continuar
                </p>
            </VCardText>
            <VCardText>
                <VForm ref="refVForm" @submit.prevent="onSubmit">
                    <VRow>
                        <!-- email -->
                        <VCol cols="12">
                            <VTextField id="email" v-model="form.email" label="Correo electr&oacute;nico" type="email"
                                :rules="[requiredValidator, emailValidator]" :error-messages="errors.email"
                                :disabled="form.processing" />
                        </VCol>

                        <!-- password -->
                        <VCol cols="12">
                            <VTextField id="password" v-model="form.password" label="Contraseña"
                                :rules="[requiredValidator]" :type="isPasswordVisible ? 'text' : 'password'"
                                :error-messages="errors.password"
                                :append-inner-icon="isPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                                @click:append-inner="isPasswordVisible = !isPasswordVisible" :disabled="form.processing" />

                            <div class="d-flex align-center flex-wrap justify-space-between mt-1 mb-4">
                                <VCheckbox v-model="form.remember" label="Recordar sesi&oacute;n"
                                    :disabled="form.processing" />
                                <Link v-if="canResetPassword" class="text-primary ms-2 mb-1 disabled"
                                    :href="route('password.request')">
                                ¿Olvidaste tu contraseña?
                                </Link>
                            </div>

                            <VBtn class="w-100" type="submit" color="primary" :loading="form.processing">
                                Iniciar sesi&oacute;n
                                <template #loader>
                                    <VProgressCircular indeterminate color="dark-blue" />
                                </template>
                            </VBtn>
                        </VCol>
                    </VRow>
                </VForm>
            </VCardText>
        </VCard>
    </div>
</template>
<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
</style>
