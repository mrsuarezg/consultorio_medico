<script setup>
import navItems from '@navigation/vertical'
import { useThemeConfig } from '@core/composable/useThemeConfig'

// Components
import Footer from './Footer.vue'
import NavBarNotifications from './NavBarNotifications.vue'
import NavbarShortcuts from './NavbarShortcuts.vue'
import NavbarThemeSwitcher from './NavbarThemeSwitcher.vue'
import NavSearchBar from './NavSearchBar.vue'
import UserProfile from './UserProfile.vue'

// @layouts plugin
import { VerticalNavLayout } from '@layouts'

const { appRouteTransition, isLessThanOverlayNavBreakpoint } = useThemeConfig()
const { width: windowWidth } = useWindowSize();
</script>
<template>
    <VerticalNavLayout :nav-items="navItems">
        <!-- 👉 navbar -->
        <template #navbar="{ toggleVerticalOverlayNavActive }">
            <div class="d-flex h-100 align-center">
                <VBtn v-if="isLessThanOverlayNavBreakpoint(windowWidth)" icon variant="text" color="default" class="ms-n3"
                    size="small" @click="toggleVerticalOverlayNavActive(true)">
                    <VIcon color="red" icon="mdi-menu" size="24" />
                </VBtn>

                <NavSearchBar class="ms-lg-n3" />

                <VSpacer />

                <!-- <NavBarI18n /> -->
                <NavbarThemeSwitcher />
                <!-- <NavbarShortcuts /> -->
                <NavBarNotifications class="me-2" />
                <UserProfile />
            </div>
        </template>

        <!-- 👉 Pages -->
        <!-- <Transition :name="appRouteTransition" mode="out-in"> -->
        <slot />
        <!-- </Transition> -->

        <!-- 👉 Footer -->
        <template #footer>
            <Footer />
        </template>

        <!-- 👉 Customizer -->
        <TheCustomizer />
    </VerticalNavLayout>
</template>
