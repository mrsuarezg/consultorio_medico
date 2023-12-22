<script setup>
import { useAttrs, computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        required: false,
        default: '',
    },
});

const attr = useAttrs();

const localClass = computed(() => {
    return !!props.class ? 'custom-fieldset' + props.class : 'custom-fieldset';
});
</script>

<template>
    <fieldset :class="localClass" v-bind="attr">
        <legend>
            <slot name="label">
                {{ label }}
            </slot>
        </legend>
        <slot></slot>
    </fieldset>
</template>

<style lang="css" scoped>
.custom-fieldset {
    padding: 1rem;
    border-radius: 0.4rem;
    border-color: rgba(0, 0, 0, 0.43);
}
</style>