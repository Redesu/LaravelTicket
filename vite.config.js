import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/datatables.js',
                'resources/js/chamados.js',
                'resources/js/chamado.js',
                'resources/js/categorias.js',
                'resources/js/departamentos.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
        {
            name: 'blade',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.blade.php')) {
                    server.ws.send({
                        type: 'full-reload',
                        path: '*',
                    });
                }
            },
        }
    ],
    resolve: {
        alias: {
            '$': 'jquery',
            'jquery': 'jquery'
        }
    },
});
