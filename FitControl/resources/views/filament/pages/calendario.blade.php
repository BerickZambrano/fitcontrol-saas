<x-filament::page>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

    <!-- 🔥 CLAVE: wire:ignore -->
    <div
        wire:ignore
        x-data="calendarComponent()"
        x-init="init()"
        class="bg-white rounded-xl shadow p-4"
    >
        <div id="calendar"></div>

        <!-- MODAL REAL -->
        <div
            x-show="open"
            x-cloak
            x-transition
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50"
        >
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h2 class="text-xl font-bold mb-2" x-text="title"></h2>

                <p class="text-gray-600 mb-4" x-text="description"></p>

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