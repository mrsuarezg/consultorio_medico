<script setup>
import { computed } from 'vue';
import { capitalizeText } from '@utils/formatters';

const props = defineProps({
    itemName: {
        type: String,
        required: false,
        default: 'Opciones',
    },
    typed: {
        type: String,
        required: false,
        default: null,
    },
    makesRequests: {
        type: Boolean,
        required: false,
        default: false,
    },
    isGettingData: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const text = computed(() => {
    if(!props.makesRequests){
        return `${capitalizeText(props.itemName)} no disponibles`;
    }
    return !!props.typed ? `No se encontraron coincidencias con: ${props.typed}` : `Empieza a escribir para mostrar las coincidencias`;
});
</script>

<template>
    <div v-if="isGettingData" class="pa-3">
        <span class="pb-1">Obteniendo {{ itemName.toLowerCase() }}</span>
        <VProgressLinear indeterminate color="primary" />
    </div>
    <div v-else>
        <span class="px-3">{{ text }}</span>
    </div>
</template>