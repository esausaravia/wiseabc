import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/admin.classroom.cforcurso.js',
                'resources/js/admin.js',
                'resources/js/admin.pagos.js',
                'resources/js/app-profile-form.js',
                'resources/js/app.js',
                'resources/js/fontawesome.js',
                'resources/js/students.js',
            ],
            refresh: true,
        }),
    ],
});
