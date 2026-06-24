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
            @click="open = false"
            style="position: fixed !important; top: 0 !important; right: 0 !important; bottom: 0 !important; left: 0 !important; z-index: 9999 !important; display: flex !important; align-items: center !important; justify-content: center !important; background-color: rgba(0, 0, 0, 0.75) !important; backdrop-filter: blur(8px) !important; -webkit-backdrop-filter: blur(8px) !important; padding: 1.5rem !important;"
        >
            <div 
                @click.stop
                style="position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; background-color: #1f2937 !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; border-radius: 1.25rem !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important; width: calc(100% - 3rem) !important; max-width: 28rem !important; padding: 2rem !important; display: flex !important; flex-direction: column !important; gap: 1.25rem !important; color: #ffffff !important; font-family: system-ui, -apple-system, sans-serif !important;"
            >
                <div style="display: flex !important; align-items: center !important; gap: 0.75rem !important;">
                    <span style="width: 0.75rem !important; height: 0.75rem !important; border-radius: 50% !important; display: inline-block !important; flex-shrink: 0 !important;" :style="'background-color: ' + eventColor"></span>
                    <span style="font-size: 0.75rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; color: #9ca3af !important;" x-text="eventType"></span>
                </div>

                <h3 style="font-size: 1.5rem !important; font-weight: 800 !important; line-height: 1.25 !important; margin: 0 !important; color: #ffffff !important;" x-text="eventTitle"></h3>

                <div style="display: flex !important; flex-direction: column !important; gap: 1rem !important;">
                    <div style="display: flex !important; align-items: center !important; gap: 0.75rem !important; font-size: 0.95rem !important; color: #d1d5db !important;">
                        <svg style="color: #9ca3af !important; width: 1.25rem !important; height: 1.25rem !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span><strong style="color: #ffffff !important; font-weight: 600 !important;">Hora:</strong> <span x-text="eventStart"></span></span>
                    </div>
                    <div style="display: flex !important; align-items: center !important; gap: 0.75rem !important; font-size: 0.95rem !important; color: #d1d5db !important;">
                        <svg style="color: #9ca3af !important; width: 1.25rem !important; height: 1.25rem !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span><strong style="color: #ffffff !important; font-weight: 600 !important;">Ubicación:</strong> <span x-text="eventLocation"></span></span>
                    </div>
                    <template x-if="eventExtra">
                        <div style="display: flex !important; align-items: center !important; gap: 0.75rem !important; font-size: 0.95rem !important; color: #d1d5db !important;">
                            <svg style="color: #9ca3af !important; width: 1.25rem !important; height: 1.25rem !important; flex-shrink: 0 !important;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <span x-text="eventExtra"></span>
                        </div>
                    </template>
                </div>

                <div style="display: flex !important; justify-content: flex-end !important; border-top: 1px solid rgba(255, 255, 255, 0.1) !important; padding-top: 1.25rem !important; margin-top: 0.5rem !important;">
                    <button
                        @click="open = false"
                        onmouseover="this.style.backgroundColor='#059669'"
                        onmouseout="this.style.backgroundColor='#10b981'"
                        style="background-color: #10b981 !important; color: #ffffff !important; font-weight: 700 !important; font-size: 0.875rem !important; padding: 0.625rem 1.25rem !important; border-radius: 0.75rem !important; border: none !important; cursor: pointer !important; transition: all 0.2s ease-in-out !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;"
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
                        timeZone: 'UTC', // Evita desfases por zona horaria del navegador
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