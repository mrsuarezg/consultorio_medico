<script setup>
import LoadingMessage from '@components/LoadingMessage.vue';
import StatusMessage from '@components/StatusMessage.vue';
import { ref, computed } from 'vue';
import PdfEmbed from 'vue-pdf-embed';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    getBase64Function: {
        type: [Function, null],
        required: true,
    },
    fileName: {
        type: String,
        required: false,
        default: 'Archivo PDF',
    },
    title: {
        type: String,
        required: false,
        default: 'Visualizador PDF',
    },
});

const emit = defineEmits([
    'update:modelValue',
    'error',
    'closeDialog',
]);

const pdfViewer = ref(null);
const page = ref(1);
const pageCount = ref(1);
const isPreparingPdf = ref(true);
const anErrorHasOccurred = ref(false);
const pdf = ref(null);

const showDialog = computed(() => {
    if (props.modelValue) {
        anErrorHasOccurred.value = false;
        page.value = 1;
        pageCount.value = 1;
        getFile();
    }
    return props.modelValue;
});

const isReadyPdf = computed(() => {
    return (!isPreparingPdf.value) && (!anErrorHasOccurred.value);
});

async function getFile() {
    try {
        if (typeof props.getBase64Function !== 'function') {
            emit('error', 'Error al obtener el PDF');
            return;
        }
        pdf.value = null;
        isPreparingPdf.value = true;
        const file = await props.getBase64Function();
        pdf.value = `data:application/pdf;base64,${file}`;
    } catch (error) {
        console.error(error);
        isPreparingPdf.value = false;
        anErrorHasOccurred.value = true;
        emit('error', error?.response?.data?.message ?? 'Ha ocurrido un error al obtener el PDF');
    }
}

function printPDF() {
    pdfViewer.value.print(300, '', true);
}

function downloadPDF() {
    const pdfData = base64ToArrayBuffer(pdf.value.split(",")[1]);
    const blob = new Blob([pdfData], { type: "application/pdf" });
    const link = document.createElement("a");
    link.href = window.URL.createObjectURL(blob);
    link.download = props.fileName;
    link.click();
}

function base64ToArrayBuffer(base64) {
    const binaryString = window.atob(base64);
    const bytes = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
}

function handleDocumentRender() {
    isPreparingPdf.value = false;
    pageCount.value = pdfViewer.value.pageCount;
}

function renderFailed() {
    isPreparingPdf.value = false;
    anErrorHasOccurred.value = true;
    emit('error', 'Ha ocurrido un error al mostrar el PDF');
}

function loadedFailed() {
    isPreparingPdf.value = false;
    anErrorHasOccurred.value = true;
    emit('error', 'Ha ocurrido un error al cargar el PDF');
}

function closeDialog() {
    emit('closeDialog');
    emit('update:modelValue', false);
}

function setCustomClasses(classes) {
    return isReadyPdf.value ? classes : '';
}

function goToPage(input) {
    const value = Number(input.target.value);
    if (Number.isNaN(value)) {
        return;
    }
    if (value <= 1) {
        page.value = 1;
        return;
    }
    if (value >= pageCount.value) {
        page.value = pageCount.value;
        return;
    }
    page.value = Math.round(value);
}
</script>

<template>
    <VDialog v-model="showDialog" max-width="810" persistent scrollable>
        <VCard>
            <VToolbar color="surface" :class="`${setCustomClasses('c-card-title')}`">
                <VToolbarTitle>{{ title }}</VToolbarTitle>
                <VSpacer />
                <VToolbarItems>
                    <VBtn icon="mdi-close" color="primary" @click="closeDialog()" />
                </VToolbarItems>
            </VToolbar>
            <VCardText :class="`${setCustomClasses('c-card-text')} px-2 py-0`">
                <PdfEmbed v-if="(!!pdf) && !anErrorHasOccurred" ref="pdfViewer" :page="page" :source="pdf"
                    @rendered="handleDocumentRender" @rendering-failed="renderFailed" @loading-failed="loadedFailed" />
                <LoadingMessage v-else-if="isPreparingPdf" title="Obteniendo el archivo PDF" />
                <StatusMessage v-else title="Ha ocurrido un error con el archivo PDF" variant="error" />
            </VCardText>
            <VCardActions :class="`${setCustomClasses('c-card-actions')} pt-3 d-flex justify-space-between`">
                <div>
                    <VBtn icon="mdi-chevron-left" variant="plain" @click="page--" :disabled="page <= 1" />
                    <input :value="page" type="text" class="current-number" @focusout="goToPage" @keyup.enter="goToPage">
                    <span> / {{ pageCount }}</span>
                    <VBtn icon="mdi-chevron-right" variant="plain" @click="page++" :disabled="page >= pageCount" />
                </div>
                <div>
                    <VTooltip>
                        <template #activator="{ props }">
                            <VBtn variant="tonal" icon="mdi-file-download-outline" v-bind="props" @click="downloadPDF"
                                :disabled="!isReadyPdf" />
                        </template>
                        <span>Descargar</span>
                    </VTooltip>
                    <VTooltip>
                        <template #activator="{ props }">
                            <VBtn variant="tonal" icon="mdi-printer-outline" v-bind="props" @click="printPDF"
                                :disabled="!isReadyPdf" />
                        </template>
                        <span>Imprimir</span>
                    </VTooltip>
                </div>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<style scoped>
.c-card-title {
    border-bottom: 0.7rem solid rgb(var(--v-theme-grey-600));
}

.c-card-text {
    background-color: rgb(var(--v-theme-grey-600));
}

.c-card-actions {
    border-top: 0.7rem solid rgb(var(--v-theme-grey-600));
}

.current-number {
    width: 1.5rem;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    text-align: right;
}
</style>