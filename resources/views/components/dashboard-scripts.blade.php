<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    
    <script>
        function switchDashTab(tabName) {
            document.getElementById('tab-pane-resumen').style.display = 'none';
            document.getElementById('tab-pane-operaciones').style.display = 'none';
            document.getElementById('tab-pane-calendario').style.display = 'none';

            document.getElementById('tab-btn-resumen').className = 'btn btn-secondary';
            document.getElementById('tab-btn-operaciones').className = 'btn btn-secondary';
            document.getElementById('tab-btn-calendario').className = 'btn btn-secondary';

            document.getElementById('tab-pane-' + tabName).style.display = 'block';
            document.getElementById('tab-btn-' + tabName).className = 'btn btn-primary';

            if (tabName === 'calendario' && calendar) {
                setTimeout(() => calendar.render(), 50);
            }
        }

        let calendar;
        const modal = document.getElementById('reminder-modal');
        const form = document.getElementById('reminder-form');

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana'
                },
                editable: true,
                selectable: true,
                events: "{{ route('recordatorios.index') }}",
                
                select: function(info) {
                    openReminderModal(null, info.startStr);
                },

                eventClick: function(info) {
                    openReminderModal(info.event);
                },

                eventDrop: function(info) {
                    fetch(`/recordatorios/${info.event.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            fecha: info.event.startStr
                        })
                    }).then(res => res.json()).then(data => {
                        if(!data.success) {
                            alert('Error al mover el recordatorio.');
                            info.revert();
                        }
                    }).catch(() => info.revert());
                }
            });
            
            calendar.render();

            function loadUpcomingReminders() {
                fetch("{{ route('recordatorios.index') }}")
                  .then(res => res.json())
                  .then(events => {
                      const today = new Date();
                      today.setHours(0,0,0,0);
                      const upcoming = events.filter(e => new Date(e.start) >= today)
                                             .sort((a,b) => new Date(a.start) - new Date(b.start))
                                             .slice(0, 10);
                                             
                      const listEl = document.getElementById('upcoming-list');
                      if(!listEl) return;
                      if (upcoming.length === 0) {
                          listEl.innerHTML = '<div style="text-align:center; padding:20px; color:var(--color-text-muted); font-size:12px">No hay eventos próximos.</div>';
                          return;
                      }

                      listEl.innerHTML = upcoming.map(e => {
                          const dateObj = new Date(e.start);
                          const day = String(dateObj.getDate()).padStart(2, '0');
                          const month = String(dateObj.getMonth()+1).padStart(2, '0');
                          const time = dateObj.toTimeString().slice(0,5);
                          return `
                          <div style="display:flex; gap:10px; align-items:flex-start; padding:10px; border:1px solid var(--color-border); border-radius:6px; cursor:pointer; background:var(--color-bg)" onclick='openReminderModal(${JSON.stringify(e).replace(/'/g, "&#39;")})'>
                              <div style="width:10px; height:10px; border-radius:50%; background:${e.backgroundColor}; flex-shrink:0; margin-top:3px"></div>
                              <div style="flex:1; min-width:0">
                                  <div style="font-size:12.5px; font-weight:600; color:var(--color-text); line-height:1.2; word-break:break-word">${e.title}</div>
                                  <div style="font-size:11px; color:var(--color-text-muted); margin-top:4px">${day}/${month} · ${time}</div>
                              </div>
                          </div>`;
                      }).join('');
                  });
            }

            loadUpcomingReminders();

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = document.getElementById('rm-id').value;
                const url = id ? `/recordatorios/${id}` : "{{ route('recordatorios.store') }}";
                const method = id ? 'PUT' : 'POST';
                const btnText = document.getElementById('btn-save-rm').innerText;
                
                document.getElementById('btn-save-rm').innerText = 'Guardando...';

                const colorRadios = document.getElementsByName('rm-color');
                let colorSeleccionado = '#3b82f6';
                for(let r of colorRadios) {
                    if(r.checked) colorSeleccionado = r.value;
                }

                const payload = {
                    titulo: document.getElementById('rm-titulo').value,
                    fecha: document.getElementById('rm-fecha').value,
                    descripcion: document.getElementById('rm-descripcion').value,
                    color: colorSeleccionado
                };

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        closeReminderModal();
                        calendar.refetchEvents();
                        loadUpcomingReminders();
                    } else {
                        alert(data.message || 'Error al guardar');
                    }
                })
                .finally(() => {
                    document.getElementById('btn-save-rm').innerText = btnText;
                });
            });

            document.getElementById('btn-delete-rm').addEventListener('click', function() {
                if(!confirm('¿Estás seguro de eliminar este recordatorio?')) return;
                
                const id = document.getElementById('rm-id').value;
                document.getElementById('btn-delete-rm').innerText = '...';

                fetch(`/recordatorios/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        closeReminderModal();
                        calendar.refetchEvents();
                        loadUpcomingReminders();
                    } else {
                        alert('Error al eliminar');
                    }
                })
                .finally(() => {
                    document.getElementById('btn-delete-rm').innerText = 'Eliminar Evento';
                });
            });

            const mobFabBtn = document.getElementById('mob-fab-btn');
            if(mobFabBtn) {
                mobFabBtn.addEventListener('click', function() {
                    document.getElementById('mob-fab-menu').classList.toggle('show');
                    this.classList.toggle('active');
                });
            }
        });

        function formatDateForInput(dateObj) {
            const tzoffset = (new Date()).getTimezoneOffset() * 60000;
            const localISOTime = (new Date(dateObj - tzoffset)).toISOString().slice(0, 16);
            return localISOTime;
        }

        function openReminderModal(eventData = null, initialDateStr = null) {
            form.reset();
            modal.style.display = 'flex';
            const btnDelete = document.getElementById('btn-delete-rm');

            if(eventData) {
                document.getElementById('rm-title').innerText = 'Editar Recordatorio';
                document.getElementById('rm-id').value = eventData.id;
                document.getElementById('rm-titulo').value = eventData.title;
                
                if(eventData.start) {
                    document.getElementById('rm-fecha').value = formatDateForInput(eventData.start);
                }

                if(eventData.extendedProps) {
                    document.getElementById('rm-descripcion').value = eventData.extendedProps.descripcion || '';
                }
                const colorRadios = document.getElementsByName('rm-color');
                for(let r of colorRadios) {
                    r.checked = (r.value === eventData.backgroundColor);
                }

                btnDelete.style.display = 'block';
            } else {
                document.getElementById('rm-title').innerText = 'Nuevo Recordatorio';
                document.getElementById('rm-id').value = '';
                btnDelete.style.display = 'none';

                if(initialDateStr) {
                    if(initialDateStr.length === 10) {
                        document.getElementById('rm-fecha').value = initialDateStr + 'T12:00';
                    } else {
                        document.getElementById('rm-fecha').value = formatDateForInput(new Date(initialDateStr));
                    }
                } else {
                    document.getElementById('rm-fecha').value = formatDateForInput(new Date());
                }
            }
        }

        function closeReminderModal() {
            modal.style.display = 'none';
        }
    </script>

    <style>
        .fc { 
            font-family: inherit; font-size: 13px; 
            --fc-today-bg-color: rgba(0, 0, 0, 0.03);
            --fc-border-color: var(--color-border);
            --fc-neutral-bg-color: var(--color-bg);
            --fc-page-bg-color: var(--color-surface);
        }
        [data-theme="dark"] .fc {
            --fc-today-bg-color: rgba(255, 255, 255, 0.05);
        }
        
        .fc-toolbar-title { font-size: 16px !important; font-weight: 600; color: var(--color-text); text-transform: capitalize; }
        
        .fc .fc-button-primary { 
            background-color: var(--color-surface) !important; 
            color: var(--color-text) !important; 
            border: 1px solid var(--color-border) !important; 
            box-shadow: 0 1px 2px rgba(0,0,0,0.05); 
            font-weight: 500; 
        }
        .fc .fc-button-primary:hover { background-color: var(--color-bg) !important; }
        .fc .fc-button-primary:disabled { opacity: 0.5; color: var(--color-text-muted) !important; }
        
        .fc .fc-button-active { 
            background-color: var(--color-primary) !important; 
            color: #ffffff !important; 
            border-color: var(--color-primary) !important; 
            box-shadow: none !important; 
        }

        .fc-daygrid-day-number { color: var(--color-text-muted); font-weight: 500; text-decoration: none !important; }
        .fc-col-header-cell-cushion { color: var(--color-text-muted); text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; font-weight: 600; text-decoration: none !important; }
        
        .fc-theme-standard td, .fc-theme-standard th { border-color: var(--color-border); }
        
        .fc-event { border: none !important; border-radius: 4px; padding: 2px 4px; font-size: 11.5px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); cursor: pointer; }

        @media (min-width: 769px) {
            #dash-tabs-container { display: none !important; }
            #tab-pane-resumen, #tab-pane-operaciones, #tab-pane-calendario { display: block !important; }
        }
    </style>
