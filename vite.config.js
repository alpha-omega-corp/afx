import { defineConfig } from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';

const appPath = './resources/js/app.js';

export default defineConfig({
    plugins: [
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
                `,
            },
        },
    },
});
