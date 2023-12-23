<script setup>
import { onMounted, computed, reactive, toRefs } from 'vue';
import LoadingMessage from '@components/LoadingMessage.vue';

const props = defineProps({
    isDialogVisible: {
        type: Boolean,
        required: true,
        default: false,
    },
    formComponent: {
        type: [Object, String],
        required: true,
        default: 'div',
    },
    formProps: {
        type: Object,
        required: true,
        default: {},
    },
    dialogTitle: {
        type: String,
        required: true,
        default: 'Dialog',
    },
    includeCancelButton: {
        type: Boolean,
        required: false,
        default: true,
    },
    includeDoActionButton: {
        type: Boolean,
        required: false,
        default: true,
    },
    inithialStatusButton: {
        type: Object,
        required: false,
        default: { buttonOption: DO_ACTION_BUTTON, disabled: true }
    },
    mainButtonText: {
        type: String,
        required: false,
        default: 'Guardar',
    },
    addSecondButton: {
        type: Boolean,
        required: false,
        default: false,
    },
    secondButtonText: {
        type: String,
        required: false,
        default: '<Secondary button>',
    },
});
const { isDialogVisible, inithialStatusButton } = toRefs(props);

const emits = defineEmits([
    'close-dialog',
]);

const form = ref(null);
const isButtonDisabled = reactive({
    closeTitle: false,
    closeAction: false,
    doAction: false
});
const isButtonDisplayed = reactive({
    closeTitle: true,
    closeAction: true,
    doAction: true
});
// TODO: Research how export this constant to children components
const CLOSE_TITLE_BUTTON = 0;
const CLOSE_ACTION_BUTTON = 1;
const DO_ACTION_BUTTON = 2;
const CLOSE_BUTTONS = 3;
const ALL_BUTTONS = 4;
const isFormLoading = ref(false);
const snackbar = reactive({
    isVisible: false,
    message: '',
    color: 'primary',
});

onMounted(() => {
    try {
        const keys = Object.keys(inithialStatusButton.value);
        if (keys.includes('buttonOption') && keys.includes('disabled')) {
            switchButtonDisable(inithialStatusButton.value.buttonOption, inithialStatusButton.value.disabled);
        }
    } catch (error) {
        console.error(error);
    }
});

const displayCloseButton = computed(() => {
    return props.includeCancelButton === true && isButtonDisplayed.closeAction;
});

const displayMainActionButton = computed(() => {
    return props.includeDoActionButton === true && isButtonDisplayed.doAction;
});

const closeButtonText = computed(() => {
    return displayMainActionButton.value && (!props.addSecondButton) ? 'Cancelar' : 'Cerrar';
});

function executeChildAction() {
    try {
        if (form.value == null) {
            console.warn('Child form not found!');
            return;
        }
        if (typeof form.value.makeRequest !== 'function') {
            console.warn('Function "makeRequest" was not found on the child!.');
            return;
        }
        form.value.makeRequest();
    } catch (error) {
        console.error(error);
    }
}

function executeSecondAction() {
    try {
        if (form.value == null) {
            console.warn('Child form not found!');
            return;
        }
        if (typeof form.value.executeSecondAction !== 'function') {
            console.warn('Function "executeSecondAction" was not found on the child!.');
            return;
        }
        form.value.executeSecondAction();
    } catch (error) {
        console.error(error);
    }
}

function switchButtonDisable(buttonOption = ALL_BUTTONS, disabled = true) {
    switch (buttonOption) {
        case CLOSE_TITLE_BUTTON:
            isButtonDisabled.closeTitle = disabled;
            isButtonDisabled.closeAction = !disabled;
            isButtonDisabled.doAction = !disabled;
            break;
        case CLOSE_ACTION_BUTTON:
            isButtonDisabled.closeTitle = !disabled;
            isButtonDisabled.closeAction = disabled;
            isButtonDisabled.doAction = !disabled;
            break;
        case DO_ACTION_BUTTON:
            isButtonDisabled.closeTitle = !disabled;
            isButtonDisabled.closeAction = !disabled;
            isButtonDisabled.doAction = disabled;
            break;
        case CLOSE_BUTTONS:
            isButtonDisabled.closeTitle = disabled;
            isButtonDisabled.closeAction = disabled;
            isButtonDisabled.doAction = !disabled;
            break;
        case CLOSE_BUTTONS:
            isButtonDisabled.closeTitle = disabled;
            isButtonDisabled.closeAction = disabled;
            isButtonDisabled.doAction = !disabled;
            break;
        case ALL_BUTTONS:
        default:
            isButtonDisabled.closeTitle = disabled;
            isButtonDisabled.closeAction = disabled;
            isButtonDisabled.doAction = disabled;
            break;
    }
}

function setLoading(status) {
    isFormLoading.value = status;
    if (status) {
        switchButtonDisable(CLOSE_BUTTONS, true);
    } else {
        switchButtonDisable(ALL_BUTTONS, false);
    }
}

function hideButton(buttonOption, hide = true) {
    switch (buttonOption) {
        case CLOSE_TITLE_BUTTON:
            isButtonDisplayed.closeAction = hide;
            isButtonDisplayed.doAction = hide;
            break;
        case CLOSE_ACTION_BUTTON:
            isButtonDisplayed.closeAction = !hide;
            isButtonDisplayed.doAction = hide;
            break;
        case DO_ACTION_BUTTON:
            isButtonDisplayed.closeAction = hide;
            isButtonDisplayed.doAction = !hide;
            break;
        case CLOSE_BUTTONS:
            isButtonDisplayed.closeAction = !hide;
            isButtonDisplayed.doAction = hide;
            break;
        case ALL_BUTTONS:
        default:
            isButtonDisplayed.closeAction = true;
            isButtonDisplayed.doAction = true;
            break;
    }
}

function closeDialog() {
    emits('close-dialog');
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
    <div>
        <VDialog v-model="isDialogVisible" fullscreen scrollable persistent transition="dialog-bottom-transition"
            scroll-strategy="none">
            <VCard class="bg-background">
                <VToolbar color="primary">
                    <VToolbarTitle>{{ dialogTitle }}</VToolbarTitle>
                    <VSpacer />
                    <VToolbarItems>
                        <VBtn icon="mdi-close" @click="closeDialog()" :disabled="isButtonDisabled.closeTitle" />
                    </VToolbarItems>
                </VToolbar>

                <Suspense>
                    <component :is="formComponent" @disabled-button="switchButtonDisable" @is-loading="setLoading"
                        @show-snackbar="showSnackbar" v-bind="formProps" ref="form" @hide-button="hideButton" />
                        <template #fallback>
                            <VCardText>
                                <LoadingMessage title="Cargando formulario" />
                            </VCardText>
                        </template>
                </Suspense>

                <VCardActions class="pt-3">
                    <VSpacer />
                    <VBtn v-if="displayCloseButton" color="secondary" @click="closeDialog()"
                        :disabled="isButtonDisabled.closeAction">{{ closeButtonText }}</VBtn>
                    <VBtn v-if="addSecondButton" @click="executeSecondAction()" color="primary" variant="outlined"
                        :loading="isFormLoading" :disabled="isButtonDisabled.doAction">
                        {{ secondButtonText }}</VBtn>
                    <VBtn v-if="displayMainActionButton" @click="executeChildAction()" color="primary" variant="tonal"
                        :loading="isFormLoading" :disabled="isButtonDisabled.doAction">
                        {{ mainButtonText }}</VBtn>
                </VCardActions>
            </VCard>
        </VDialog>

        <VSnackbar v-model="snackbar.isVisible" location="bottom" :color="snackbar.color" z-index="2999">
            {{ snackbar.message }}
        </VSnackbar>
    </div>
</template>

<style scoped lang="scss">
.v-tabs .v-tab--selected {
    background-color: rgba(var(--v-theme-surface), $alpha: 25%);
    color: var(--v-theme-surface) !important;
}
</style>
