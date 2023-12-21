import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig(({ command }) =>({
    base: command === "serve" ? process.env.ASSET_URL || "" : `${process.env.ASSET_URL || ""}/dist/`,
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        fs: { allow: [`${process.cwd()}`] },
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173
        }
    },
    build: {
        manifest: true,
        rollupOptions: {
            input: 'resources/js/app.js',
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        const directories = id.split(path.sep);
                        const moduleIndex = directories.findIndex(directory => directory === 'node_modules');
                        const modulePath = directories.slice(moduleIndex + 1, directories.length).join(path.sep);
                        const moduleName = modulePath.split(path.sep)[0];

                        return `vendor/${moduleName}`;
                    }
                }
            }
        },
        outDir: 'public/dist',
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
        },
    },
    optimizeDeps: {
        // exclude: ['vuetify'],
        entries: [
            './resources/**/*.vue',
        ],
    },
}));
