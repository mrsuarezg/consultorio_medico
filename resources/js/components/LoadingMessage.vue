<script setup>
const props = defineProps({
    title: {
        type: String,
        required: false,
        default: 'Cargando',
    },
});
</script>

<template>
    <div>
        <p>{{ title }}...</p>
        <VProgressLinear indeterminate color="primary" />
    </div>
</template>