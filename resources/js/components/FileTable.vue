<script setup>
import { ref, computed, watchEffect } from 'vue';
import DropFile from './DropFile.vue';
import { isRequired } from '@utils/rules';
import { useBase64 } from '@vueuse/core';

const props = defineProps({
    modelValue: {
        type: Array,
        required: false,
        default: []
    },
    previousFiles: {
        type: Array,
        required: false,
        default: []
    },
    maximumFiles: {
        type: Number,
        required: false,
        default: 1,
    },
    maxSize: {
        type: Number,
        required: false,
        default: 2000000, // DEFAULT 2MB
    },
    validMimeTypes: {
        type: Array,
        required: false,
        default: ['*/*'],
    },
    returnOriginalFiles: {
        type: Boolean,
        required: false,
        default: false,
    },
    deletable: {
        type: Boolean,
        required: false,
        default: false,
    },
    loading: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits([
    'update:modelValue',
    'error',
    'deletePreviousFile',
]);

const files = ref([]);

const currentFiles = computed(() => {
    return props.previousFiles.length + files.value.length;
});

const isAllowedSelectFiles = computed(() => {
    return currentFiles.value < props.maximumFiles;
});

const allowedFiles = computed(() => {
    return props.maximumFiles - currentFiles.value;
});

watchEffect(() => {
    files.value = props.modelValue;
});

watchEffect(() => {
    emit('update:modelValue', files.value);
});

watchEffect(() => {
    files.value = props.modelValue;
});

function droppedFile(newFiles) {
    if (props.returnOriginalFiles) {
        newFiles.forEach(f => {
            files.value.push({ name: null, fileName: f.name, data: f });
        });
        return;
    }
    newFiles.forEach(async (f) => {
        files.value.push({ name: null, fileName: f.name, data: await getBase64File(f) });
    });
}

function error(...params) {
    emit('error', ...params);
}

function deleteFile(index) {
    files.value.splice(index, 1);
}

function deletePreviousFile(id) {
    if (confirm('¿Estás seguro/a de eliminar el archivo?')) {
        emit('deletePreviousFile', id);
    }
}

async function getBase64File(file) {
    try {
        const { base64: fileBase64, execute } = useBase64(file);
        await execute();

        let finalBase64File = null;
        if (fileBase64.value.startsWith('data:')) {
            finalBase64File = fileBase64.value.split(',')[1];
        } else {
            // Already base64, no need to convert it
            finalBase64File = fileBase64.value;
        }
        return finalBase64File;
    } catch (error) {
        console.error(error);
        return '';
    }
}

</script>

<template>
    <div v-if="isAllowedSelectFiles">
        <DropFile :valid-mime-types="validMimeTypes" :max-size="maxSize" :maximum-files="allowedFiles" :disabled="loading"
            @drop="droppedFile" @error="error" />
        <div class="d-flex justify-end pt-2">
            <p>Archivos: {{ currentFiles }} de {{ maximumFiles }}</p>
        </div>
    </div>

    <!-- TODO: IMRPOVE RESPONSIVE MODE -->
    <div style="overflow-x: auto;">
        <VTable v-if="currentFiles > 0" class="custom-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(file, i) in files" :key="`tr-file-${i}`">
                    <td>
                        <VTextField label="Nombre *" v-model="file.name" class="my-4" :rules="[isRequired]" />
                    </td>
                    <td>{{ file.fileName }}</td>
                    <td>
                        <VTooltip v-if="deletable">
                            <template #activator="{ props }">
                                <VBtn icon="mdi-delete" color="error" variant="plain" @click="deleteFile(i)"
                                    v-bind="props" />
                            </template>
                            <span>Eliminar archivo</span>
                        </VTooltip>
                    </td>
                </tr>
                <tr v-for="file in previousFiles" :key="`tr-previous-file-${file.id}`">
                    <td>{{ file.name }}</td>
                    <td>{{ file.fileName }}</td>
                    <td>
                        <div class="d-flex">
                            <VTooltip v-if="deletable">
                                <template #activator="{ props }">
                                    <VBtn icon="mdi-delete" color="error" variant="plain" :disabled="loading" v-bind="props"
                                        @click="deletePreviousFile(file.id)" />
                                </template>
                                <span>Eliminar archivo</span>
                            </VTooltip>
                            <VTooltip>
                                <template #activator="{ props }">
                                    <VBtn icon="mdi-file-eye" variant="plain" :href="file.url" target="_blank"
                                        v-bind="props" />
                                </template>
                                <span>Ver archivo</span>
                            </VTooltip>
                        </div>
                    </td>
                </tr>
            </tbody>
        </VTable>
    </div>
    <VProgressLinear v-if="loading" indeterminate color="primary" />
</template>

<style scoped>
.custom-table {
    min-width: 40rem;
}

.custom-table td:nth-child(1) {
    width: 30%;
}

.custom-table td:nth-child(2) {
    width: 60%;
}

.custom-table td:nth-child(3) {
    width: 10%;
}
</style>
