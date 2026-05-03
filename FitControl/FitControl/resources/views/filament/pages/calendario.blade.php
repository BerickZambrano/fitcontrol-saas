<x-filament::page>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <style>
        .dark .fc {
            --fc-page-bg-color: #111827;
            --fc-neutral-bg-color: #1f2937;
            --fc-neutral-text-color: #f3f4f6;
            --fc-border-color: #374151;
            --fc-button-text-color: #ffffff;
            --fc-button-bg-color: #3b82f6;
            --fc-button-border-color: #3b82f6;
            --fc-button-hover-bg-color: #2563eb;
            --fc-button-hover-border-color: #2563eb;
            --fc-button-active-bg-color: #1d4ed8;
            --fc-button-active-border-color: #1d4ed8;
            --fc-today-bg-color: rgba(59, 130, 246, 0.15);
            color: #f3f4f6;
        }

        .dark .fc a {
            color: #f3f4f6;
            text-decoration: none;
        }

        .dark .fc-col-header-cell-cushion:hover,
        .dark .fc-daygrid-day-number:hover {
            color: #93c5fd;
        }
    </style>

    <!-- 🔥 CLAVE: wire:ignore -->
    <div
        wire:ignore
        x-data="calendarComponent()"
        x-init="init()"
        class="bg-white dark:bg-gray-900 dark:ring-1 dark:ring-white/10 rounded-xl shadow p-4"
    >
        <div id="calendar"></div>

        <!-- MODAL REAL -->
        <div
            x-show="open"
            x-cloak
            x-transition
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm"
        >
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 border dark:border-gray-700">
                <h2 class="text-xl font-bold mb-2 dark:text-white" x-text="title"></h2>

                <p class="text-gray-600 dark:text-gray-300 mb-4" x-text="description"></p>

                <div class="flex justify-end">
                    <button
                        @click="open = false"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script>
        function calendarComponent() {
            return {
                calendar: null,
                open: false,
                title: '',
                description: '',

                init() {
                    const el = document.getElementById('calendar')

                    this.calendar = new FullCalendar.Calendar(el, {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        selectable: true,

                        events: @json($this->getEvents()),

                        eventClick: (info) => {
                            info.jsEvent.preventDefault()

                            this.title = info.event.title
                            this.description =
                                info.event.extendedProps.description ?? 'Sin descripción'
                            this.open = true
                        },

                        dateClick: (info) => {
                            this.title = 'Fecha seleccionada'
                            this.description = info.dateStr
                            this.open = true
                        }
                    })

                    this.calendar.render()
                }
            }
        }
    </script>
</x-filament::page>