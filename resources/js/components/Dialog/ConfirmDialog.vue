<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: null,
    },
    message: {
        type: String,
        required: true,
    },
});

const emit = defineEmits([
    'confirm',
    'cancel',
]);

const showDialog = ref(true);

function confirm() {
    emit('confirm');
    showDialog.value = false;
}

function cancel() {
    emit('cancel');
    showDialog.value = false;
}
</script>

<template>
    <VDialog v-model="showDialog" max-width="400" persistent>
        <VCard>
            <VCardText>
                <p class="text-subtitle-1 mb-0">{{ message }}</p>
            </VCardText>
            <VCardActions>
                <VSpacer />
                <VBtn color="secondary" @click="cancel">Cancelar</VBtn>
                <VBtn variant="tonal" @click="confirm">Aceptar</VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>