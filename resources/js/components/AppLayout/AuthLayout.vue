<script setup>
import { defineAsyncComponent } from 'vue'
import { useSkins } from '@core/composable/useSkins'

const DefaultLayoutWithVerticalNav = defineAsyncComponent(() => import('./DefaultLayoutWithVerticalNav.vue'));

const { layoutAttrs, injectSkinClasses } = useSkins()

injectSkinClasses();
</script>

<template>
    <DefaultLayoutWithVerticalNav v-bind="layoutAttrs">
        <slot />
    </DefaultLayoutWithVerticalNav>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
