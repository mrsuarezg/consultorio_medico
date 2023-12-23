<script setup>
import { reactive, ref, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
        default: false,
    },
    title: {
        type: String,
        required: true,
    },
    formComponent: {
        type: [Object, String],
        required: true,
    },
    formProps: {
        type: Object,
        required: false,
        default: {},
    },
    maxWidth: {
        type: [String, Number],
        required: false,
        default: '400'
    },
    mainButtonText: {
        type: String,
        required: false,
        default: 'Aceptar',
    },
    closeButtonText: {
        type: String,
        required: false,
        default: 'Cerrar',
    },
    includeMainButton: {
        type: Boolean,
        required: false,
        default: true,
    },
});

const emit = defineEmits([
    'update:modelValue',
    'close-dialog',
    'done',
]);

const form = ref(null);
const snackbar = reactive({
    isVisible: false,
    message: '',
    color: 'primary',
});
const isFormLoading = ref(false);
const areButtonsDisabled = ref(false);

const isDialogVisible = computed(() => {
    return props.modelValue;
});

const disableCloseButton = computed(() => {
    return isFormLoading.value || areButtonsDisabled.value;
});

const closeButtonText = computed(() => {
    return props.includeMainButton ? props.closeButtonText : 'Cerrar';
});

const secondButtonColor = computed(() => {
    return props.includeMainButton ? 'secondary' : 'primary';
});

function executeChildAction() {
    try {
        if (form.value == null) {
            console.warn('Child form not found!');
            return;
        }
        if (typeof form.value.executeMainAction !== 'function') {
            console.warn('Function "executeMainAction" was not found on the child!.');
            return;
        }
        form.value.executeMainAction();
    } catch (error) {
        console.error(error);
    }
}

function done(data) {
    emit('done', data);
}

function closeDialog() {
    emit('close-dialog', true);
    emit('update:modelValue', false);
}

function setLoading(status) {
    isFormLoading.value = status;
}

function disabledButton(status) {
    areButtonsDisabled.value = status;
}

function showSnackbar(message, color = 'error') {
    Object.assign(snackbar, {
        isVisible: true,
        message: message,
        color: color
    });
}
</script>

<template>
    <VDialog v-model="isDialogVisible" :max-width="maxWidth" scrollable persistent scroll-strategy="none">
        <VCard :title="title">
            <VCardText class="pt-4">
                <component :is="formComponent" v-bind="formProps" ref="form" @disabled-button="disabledButton"
                    @is-loading="setLoading" @show-snackbar="showSnackbar" @done="done" @close-dialog="closeDialog()" />
            </VCardText>
            <VCardActions class="pt-3">
                <VSpacer />
                <VBtn @click="closeDialog()" :disabled="disableCloseButton" :color="secondButtonColor">{{ closeButtonText }}
                </VBtn>
                <VBtn v-if="includeMainButton" @click="executeChildAction()" :loading="isFormLoading"
                    :disabled="areButtonsDisabled" color="primary" variant="tonal">{{ mainButtonText }}</VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
    <div>
        <VSnackbar v-model="snackbar.isVisible" location="bottom" :color="snackbar.color" z-index="2999">
            {{ snackbar.message }}
        </VSnackbar>
    </div>
</template>