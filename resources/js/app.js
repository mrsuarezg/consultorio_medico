import './bootstrap';
import '../css/app.css';

import layoutsPlugin from './plugins/layouts';
import { loadFonts } from './plugins/webfontloader';
import '@core/scss/template/index.scss';
import '@styles/styles.scss';
// import {} from './plugins/pusher';
// import { createPinia } from 'pinia';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy';
import LaravelPermissionToVueJS from './plugins/permissions/LaravelPermissions';
loadFonts();
// const pinia = createPinia();
import vuetify from './plugins/vuetify';
import AppLayout from './components/AppLayout/AppLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`,import.meta.glob('./Pages/**/*.vue'))
        if (name.startsWith('Auth/')) {
            page.default.layout ??= page.default.layout;
        } else {
            page.default.layout ??= AppLayout;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(layoutsPlugin)
            // .use(pinia) TODO: Review if this is necessary or not, because actually is not used
            .use(LaravelPermissionToVueJS)
            .use(ZiggyVue)
            .use(vuetify)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
