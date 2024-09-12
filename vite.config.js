import { defineConfig } from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';
import inject from "@rollup/plugin-inject";

const appPath = './resources/js/app.js';

export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
            jQuery: 'jquery',
            include: [appPath],
        }),
        laravel({
            input: [appPath],
            refresh: [...refreshPaths],
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `
                    @import "./resources/sass/bootstrap";
                `
            },
        },
    }
});
