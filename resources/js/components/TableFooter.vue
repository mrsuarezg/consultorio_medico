<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false,
    },
    perPage: {
        type: [Number, String],
        required: false,
        default: -1,
    },
    totalPages: {
        type: Number,
        required: true,
    },
    totalItems: {
        type: [Number, String],
        required: true,
    },
    currentItems: {
        type: [Number, String],
        required: true,
    },
    perPageOptions: {
        type: Array,
        required: false,
        default: [
            { value: 15, title: '15' },
            { value: 20, title: '20' },
            { value: 25, title: '25' },
            { value: 50, title: '50' },
        ],
    },
});

const emit = defineEmits([
    'update:modelValue',
    'changedPage',
    'changedPerPageOption',
]);

const currentPage = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit('update:modelValue', value);
        emit('changedPage');
    }
});

function changeItemQuantity(params) {
    emit('update:modelValue', 1);
    emit('changedPerPageOption', params.target.value);
}

</script>

<template>
    <div class="text-center pt-2">
        <VRow>
            <VCol cols="3" align="end">
                <div class="d-flex align-end pa-4">
                    <p class="ma-0 pa-0 pr-1 text-start">Listar: </p>
                    <select v-if="perPage === -1" class="custom-select" @input="changeItemQuantity">
                        <option v-for="(option, i) in perPageOptions" :value="option.value" :key="`option-${i}`">
                            {{ option.title }}
                        </option>
                    </select>
                    <span v-else>{{ perPage }}</span>
                </div>
            </VCol>
            <VCol cols="6">
                <VPagination v-model="currentPage" :disabled="disabled" :length="totalPages" class="w-100" />
            </VCol>
            <VCol cols="3" align="end">
                <p class="ma-0 pa-4 text-end">Mostrando: {{ currentItems }} de {{ totalItems }}</p>
            </VCol>
        </VRow>
    </div>
</template>

<style scoped>
.custom-select {
    -moz-appearance: listbox;
    -webkit-appearance: listbox;
    color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
    background-color: rgba(var(--v-theme-surface));
    padding: 0;
}

.custom-select option {
    background-color: rgba(var(--v-theme-surface));
    margin: 0;
}
</style>