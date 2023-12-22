<script setup>
import { useThemeConfig } from '@core/composable/useThemeConfig';
import { hexToRgb } from '@layouts/utils';
import { useTheme } from 'vuetify';
import AuthLayout from './AuthLayout.vue';
defineProps({
    title: {
        type: String,
        required: true,
    },
});

const {
    syncInitialLoaderTheme,
    syncVuetifyThemeWithTheme: syncConfigThemeWithVuetifyTheme,
    // isAppRtl,
} = useThemeConfig();

const { global } = useTheme();

// // ℹ️ Sync current theme with initial loader theme
syncInitialLoaderTheme();
syncConfigThemeWithVuetifyTheme();
</script>

<template>
    <VLocaleProvider>
        <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
        <VApp
            :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}; --v-global-theme-navbar: ${hexToRgb(global.current.value.colors['navbar-primary'])}`">
            <AuthLayout>
                <Head :title="title" />
                <slot />
            </AuthLayout>
        </VApp>
    </VLocaleProvider>
</template>
