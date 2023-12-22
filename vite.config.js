import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import { fileURLToPath, URL } from "node:url";
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import DefineOptions from 'unplugin-vue-define-options/vite';
import { defineConfig } from 'vite';
import vuetify from 'vite-plugin-vuetify';
import laravel from 'laravel-vite-plugin';
import svgLoader from "vite-svg-loader";
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
        vueJsx(),
        svgLoader(),
        vuetify({
            styles: {
                configFile: 'resources/scss/styles/variables/_vuetify.scss',
            }
        }),
        Components({
            dirs: [
                'resources/js/@core/components',
            ],
            dts: true,
            extensions: [
                'vue'
            ],
            directoryAsNamespace: true,
            deep: true,
            resolvers: [
                (name) => {
                    if (name === 'Head') {
                        return {
                            importName: 'Head',
                            path: '@inertiajs/vue3'
                        }
                    }

                    if (name === 'Link') {
                        return {
                            importName: 'Link',
                            path: '@inertiajs/vue3'
                        }
                    }
                }
            ]
        }),
        AutoImport({
            // eslintrc: {
            //     enabled: true,
            //     // filepath: './.eslintrc-auto-import.json',
            // },
            imports: ['vue', '@vueuse/core', '@vueuse/math', {
                '@inertiajs/vue3': [
                    'router',
                    'usePage',
                    'useForm'
                ]
            }],
            vueTemplate: true,
        }),
        DefineOptions(),
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
            '@themeConfig': fileURLToPath(new URL('./themeConfig.js', import.meta.url)),
            '@navigation': fileURLToPath(new URL('./resources/js/navigation', import.meta.url)),
            '@components': fileURLToPath(new URL('./resources/js/components', import.meta.url)),
            '@core': fileURLToPath(new URL('./resources/js/@core', import.meta.url)),
            '@plugins': fileURLToPath(new URL('./resources/js/plugins', import.meta.url)),
            '@layouts': fileURLToPath(new URL('./resources/js/@layouts', import.meta.url)),
            '@images': fileURLToPath(new URL('./resources/images/', import.meta.url)),
            '@styles': fileURLToPath(new URL('./resources/scss/styles/', import.meta.url)),
            '@axios': fileURLToPath(new URL('./resources/js/plugins/axios', import.meta.url)),
            '@configured-variables': fileURLToPath(new URL('./resources/scss/styles/variables/_template.scss', import.meta.url)),
            '@validators': fileURLToPath(new URL('./resources/js/utils/Validators', import.meta.url)),
            '@views': fileURLToPath(new URL('./resources/js/Views', import.meta.url)),
            '@utils': fileURLToPath(new URL('./resources/js/utils', import.meta.url)),
            '@data': fileURLToPath(new URL('./resources/js/utils/data', import.meta.url)),
            '@modules': fileURLToPath(new URL('./resources/js/modules', import.meta.url)),
            '@services': fileURLToPath(new URL('./resources/js/services', import.meta.url)),
            '@composables': fileURLToPath(new URL('./resources/js/utils/composables/index.ts', import.meta.url)),
            '@models': fileURLToPath(new URL('./resources/js/utils/models/index.ts', import.meta.url)),
            "@interfaces": fileURLToPath(new URL('./resources/js/utils/interfaces/index.ts', import.meta.url)),
            '@controllers': fileURLToPath(new URL('./resources/js/utils/controllers/index.ts', import.meta.url)),
        },
    },
    optimizeDeps: {
        exclude: ['vuetify'],
        entries: [
            './resources/**/*.vue',
        ],
    },
}));
