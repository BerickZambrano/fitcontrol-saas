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
        class="bg-white dark:bg-gray-900 dark:ring-1 dark:ring-white/10 rounded-xl shadow p-4"
    >
        <div id="calendar"></div>

        <!-- MODAL DETALLADO ESTILIZADO -->
        <div
            x-show="open"
            x-cloak
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 50; display: flex; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); padding: 1rem;"
        >
            <div 
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border dark:border-gray-700 transform transition-all duration-300"
                style="width: 100%; max-width: 28rem; padding: 1.5rem; margin-left: 1rem; margin-right: 1rem;"
            >
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-3 h-3 rounded-full" :style="'background-color: ' + eventColor"></span>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400" x-text="eventType"></span>
                </div>

                <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-tight mb-4" x-text="eventTitle"></h3>

                <div class="space-y-3 mb-6 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex items-center gap-3">
                        <svg class="text-gray-400" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span><strong>Hora:</strong> <span x-text="eventStart"></span></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="text-gray-400" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span><strong>Ubicación:</strong> <span x-text="eventLocation"></span></span>
                    </div>
                    <template x-if="eventExtra">
                        <div class="flex items-center gap-3">
                            <svg class="text-gray-400" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <span x-text="eventExtra"></span>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end">
                    <button
                        @click="open = false"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg transition-colors duration-200"
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
                eventTitle: '',
                eventType: '',
                eventStart: '',
                eventLocation: '',
                eventExtra: '',
                eventColor: '',

                init() {
                    const el = document.getElementById('calendar')

                    this.calendar = new FullCalendar.Calendar(el, {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        selectable: true,
                        displayEventTime: true,
                        eventTimeFormat: {
                            hour: 'numeric',
                            minute: '2-digit',
                            meridiem: 'short',
                            hour12: true
                        },

                        events: @json($this->getEvents()),

                        eventClick: (info) => {
                            info.jsEvent.preventDefault()

                            this.eventTitle = info.event.title;
                            this.eventType = info.event.extendedProps.type;
                            this.eventStart = info.event.extendedProps.time;
                            this.eventLocation = info.event.extendedProps.location;
                            this.eventExtra = info.event.extendedProps.extra;
                            this.eventColor = info.event.backgroundColor;
                            this.open = true;
                        }
                    })

                    this.calendar.render()
                }
            }
        }
    </script>
</x-filament::page>