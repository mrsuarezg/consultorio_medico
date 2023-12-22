<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: null,
    },
    addButtonText: {
        type: String,
        required: false,
        default: 'Agregar',
    },
    disableAddButton: {
        type: Boolean,
        required: false,
        default: false,
    },
    showAddButton: {
        type: Boolean,
        required: false,
        default: true,
    },
    tooltipText: {
        type: String,
        required: false,
        default: null,
    },
    removeMarginTop: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits([
    'addItem',
]);

function addItem() {
    emit('addItem', true);
}

const addMarginTop = computed(() => {
    return props.removeMarginTop ? '' : 'mt-4';
});
</script>

<template>
    <div>
        <VCard :class="addMarginTop">
            <VToolbar color="surface">
                <VToolbarTitle>
                    <slot name="title">
                        {{ title }}
                    </slot>
                </VToolbarTitle>
                <template #append>
                    <VTooltip v-if="showAddButton" :disabled="!tooltipText">
                        <template v-slot:activator="{ props }">
                            <div v-bind="props">
                                <VBtn prepend-icon="mdi-plus" variant="tonal" @click="addItem()"
                                    :disabled="disableAddButton">
                                    {{ addButtonText }}
                                </VBtn>
                            </div>
                        </template>
                        <span>{{ tooltipText }}</span>
                    </VTooltip>
                </template>
            </VToolbar>
            <VCardText>
                <slot></slot>
            </VCardText>
        </VCard>
    </div>
</template>