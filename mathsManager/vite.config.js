import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/app.scss',
                'resources/js/app.tsx', // React entry point
                'resources/js/form.js',
                'resources/js/katex.js',
            ],
            refresh: true,
            //for set manifest true 
            manifest: true,
        }),
        react(), // React plugin
    ],
    resolve: {
        alias: {
            '@': '/resources/js', // Alias for imports
        },
    },
});
