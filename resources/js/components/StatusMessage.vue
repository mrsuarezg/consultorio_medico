<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: '',
    },
    description: {
        type: String,
        required: false,
        default: '',
    },
    variant: {
        type: String,
        required: false,
        default: 'info',
    },
});

const icon = computed(() => {
    const config = {
        show: true,
        icon: 'mdi-information',
        color: 'info',
    };
    switch (props.variant) {
        case 'error':
            config.icon = 'mdi-alert-circle';
            config.color = 'error';
            break;

        case 'warning':
            config.icon = 'mdi-alert';
            config.color = 'warning';
            break;

        case 'plain':
            config.show = false;
            break;

        case 'question':
            config.icon = 'mdi-help-circle';
            break;

        case 'info':
            // It is the default icon configuration
            break;

        default:
            console.warn(props.variant, 'is not a valid variant. Valid variants are: info | error | warning | question | plain.');
            break;
    }
    return config;
})
</script>

<template>
    <div class="d-flex">
        <VIcon v-if="icon.show" :icon="icon.icon" size="70" :color="icon.color" class="mr-4" />
        <div class="align-self-center">
            <h2>{{ title }}</h2>
            <slot name="description">
                <p class="ma-0">{{ description }}</p>
            </slot>
        </div>
    </div>
</template>