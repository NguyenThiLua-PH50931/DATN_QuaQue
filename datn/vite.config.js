import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/header.css',
                 'resources/js/app.js',
                'resources/css/footer.css'],
            refresh: true,
        }),
    ],
});
