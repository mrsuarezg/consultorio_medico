<script setup lang="ts">
import { ref, computed } from 'vue';
import { useDropZone } from '@vueuse/core';

const props = defineProps({
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
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits([
    'drop',
    'error',
]);

const dropZoneRef = ref<HTMLElement>();
const { isOverDropZone } = useDropZone(dropZoneRef, onDrop);

const acceptProp = computed(() => {
    return props.validMimeTypes.join(',');
});

function setFiles(value: any) {
    const fileList = value.target.files;
    emitFiles(fileList);
}

function onDrop(value: File[] | null) {
    emitFiles(value);
}

function emitFiles(fileList: File[] | null) {
    if (props.disabled || fileList == null) {
        return;
    }
    if (fileList.length > props.maximumFiles) {
        console.warn('File limit exceeded!');
        emit('error', `Sólo se puede agregar hasta ${props.maximumFiles} ${props.maximumFiles === 1 ? 'archivo' : 'archivos'}`);
        return;
    }
    const files: File[] = Array.from(fileList);

    const heavyFiles: Array<File> = files.filter(f => f.size > props.maxSize);
    if (heavyFiles.length > 0) {
        console.warn('File weight limit exceeded!');
        if (heavyFiles.length === 1) {
            emit('error', `El archivo: ${heavyFiles[0].name} pesa más de ${props.maxSize / 1000000}MB`);
        } else {
            const fileNames = heavyFiles.map(f => f.name).join(', ');
            emit('error', `Los archivos: ${fileNames} pesan más de ${props.maxSize / 1000000}MB`);
        }
        return;
    }

    if (!props.validMimeTypes.includes('*/*')) {
        const invalidFiles: Array<File> = files.filter(f => !props.validMimeTypes.includes(f.type));
        if (invalidFiles.length > 0) {
            console.warn('The files have an invalid mime type!');
            if (invalidFiles.length === 1) {
                emit('error', `El archivo: ${invalidFiles[0].name} tiene una extensión inválida`);
            } else {
                const fileNames = invalidFiles.map(f => f.name).join(', ');
                emit('error', `Los archivos: ${fileNames} tienen una extensión inválida`);
            }
            return;
        }
    }

    emit('drop', [...files]);
}
</script>

<template>
    <div ref="dropZoneRef"
        :class="`d-flex flex-column justify-center align-center w-100 drag-wrapper ${isOverDropZone ? 'over' : ''} ${disabled ? 'disabled' : ''}`">
        <VIcon icon="mdi-file-upload" :size="60" />
        <label for="upload-file">
            <span v-if="!isOverDropZone">
                Arrastra o <span>selecciona</span> un archivo
            </span>
            <span v-else>Suelta el archivo</span>
        </label>

        <input v-on:change="setFiles" type="file" style="display: none;" id="upload-file" :accept="acceptProp"
            :multiple="maximumFiles > 1">
    </div>
</template>

<style scoped>
.drag-wrapper {
    min-height: 10rem;
    border: 0.1rem dotted rgb(var(--v-theme-primary));
    border-radius: 0.5rem;
}

.over {
    border-style: solid;
    background-color: rgba(var(--v-theme-primary), 20%);
}

label {
    cursor: pointer;
}

label span span {
    color: rgb(var(--v-theme-primary));
}
</style>