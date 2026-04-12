import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx', 'resources/css/filament/admin/theme.css', 'resources/css/filament/jugador/theme.css'],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    server: {
        host: '192.168.20.50',
        port: 5173,
        strictPort: true,
        cors: {
            origin: ['http://192.168.20.50:8000', 'http://localhost:8000', 'http://0.0.0.0:8000'],
            credentials: true,
        },
        hmr: {
            host: '192.168.20.50',
            protocol: 'ws',
        },
    },
})