import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/websockets.js',     // Compilar solo websockets.js
            ],
            refresh: true,
        }),
    ],
});
