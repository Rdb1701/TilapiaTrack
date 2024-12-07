<x-filament-panels::page>
    <div id="calendar" style="width: 100%; height: 600px;"></div>
</x-filament-panels::page>

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6/main.min.css' rel='stylesheet'>
    <style>
        /* Modern Typography and Color Palette */
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --background-color: #f4f6f7;
            --text-color: #34495e;
        }

        /* Enhanced Calendar Styling */
        .fc {
            font-family: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text-color);
        }

        /* Toolbar Styling */
        .fc-toolbar {
            background-color: rgba(52, 152, 219, 0.05);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .fc-toolbar-title {
            font-size: 1.8em !important;
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Button Styling */
        .fc-button {
            background-color: var(--accent-color) !important;
            border: none !important;
            border-radius: 6px !important;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        .fc-button:hover {
            background-color: #2980b9 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .fc-button-active {
            background-color: #2980b9 !important;
        }

        /* Event Styling */
        .fc-event {
            border: none !important;
            border-radius: 10px !important;
            opacity: 0.9;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .fc-event:hover {
            opacity: 1;
            transform: scale(1.03);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
        }

        .fc-event-main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 8px;
            text-align: center;
            height: 100%;
        }

        .fc-event-title {
            font-weight: 700;
            font-size: 0.9em;
            margin-bottom: 4px;
            line-height: 1.2;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .fc-event-time {
            font-size: 0.7em;
            opacity: 0.8;
            font-weight: 500;
        }

        /* Background and Scrolling */
        .fc-scroller {
            background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Weekend and Day Styling */
        .fc-day-sat, .fc-day-sun {
            background-color: rgba(52, 152, 219, 0.03) !important;
        }

        .fc-day-today {
            background-color: rgba(52, 152, 219, 0.1) !important;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .fc-toolbar {
                flex-direction: column;
                align-items: center;
            }
            .fc-toolbar-chunk {
                margin-bottom: 10px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            
            var events = @json(app('App\Filament\App\Pages\Calendar')->getEvents());

            // Enhanced color generation with better contrast
            function generatePastelColor(title) {
                let hash = 0;
                for (let i = 0; i < title.length; i++) {
                    hash = title.charCodeAt(i) + ((hash << 5) - hash);
                }
                
                const hue = hash % 360;
                const saturation = 50 + (hash % 20);  // Vary saturation
                const lightness = 80 + (hash % 10);   // Vary lightness
                
                return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events,
                eventContent: function(arg) {
                    const bgColor = generatePastelColor(arg.event.title);
                    
                    var eventTime = arg.event.start ? 
                        new Date(arg.event.start).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 
                        '';
                    
                    return {
                        html: `
                            <div class="fc-event-main" style="background-color: ${bgColor}; color: #333;">
                                <div class="fc-event-title">${arg.event.title}</div>
                                <div class="fc-event-time">${eventTime}</div>
                            </div>
                        `
                    };
                },
                eventClick: function(info) {
                    var event = info.event;
                    var extendedProps = event.extendedProps;
                    
                    Swal.fire({
                        title: 'Feeding Schedule Details',
                        html: `
                            <div style="text-align: left; padding: 20px; background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%); border-radius: 12px;">
                                <p><strong>Program:</strong> ${event.title}</p>
                                <p><strong>Time:</strong> ${event.start ? event.start.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : 'N/A'}</p>
                                <p><strong>Fish Size:</strong> ${extendedProps.fish_size || 'N/A'}</p>
                                <p><strong>Protein Content:</strong> ${extendedProps.protein_content || 'N/A'}%</p>
                                <p><strong>Feed Type:</strong> ${extendedProps.feed_name || 'N/A'} </p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Close',
                        customClass: {
                            popup: 'my-custom-popup-class'
                        }
                    });
                }
            });
            calendar.render();
        });
    </script>
@endpush