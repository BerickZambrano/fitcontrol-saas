import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('app')
    console.log('data-page:', el.getAttribute('data-page'))
    
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
})