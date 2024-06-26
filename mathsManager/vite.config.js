import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/form.js',
                'resources/js/katex.js',
            ],
            refresh: true,
            //for set manifest true 
            manifest: true,
        }),
    ],
});
