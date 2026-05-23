import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'

const appEl = document.getElementById('app')

if (appEl) {
    createInertiaApp({
        id: 'app',
        resolve: name => {
            const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
            return pages[`./Pages/${name}.jsx`]
        },
        setup({ el, App, props }) {
            createRoot(el).render(<App {...props} />)
        },
    })
}