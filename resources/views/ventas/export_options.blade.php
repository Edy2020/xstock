<x-app-layout :pageTitle="'Exportar Ventas'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Exportar Ventas</h1>
            <p>Selecciona el período y el formato que deseas exportar</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="display:flex; flex-direction:column; gap:20px; max-width:680px; margin:0 auto">

        {{-- Period selector --}}
        <div class="card" style="padding:24px">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px">
                <div style="width:38px; height:38px; border-radius:10px; background:rgba(16,185,129,0.1); color:#10b981; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                    <div style="font-weight:600; font-size:15px">Período de exportación</div>
                    <div style="font-size:12px; color:var(--color-text-muted); margin-top:1px">Elige qué rango de fechas incluir en el reporte</div>
                </div>
            </div>

            <div id="period-selector" style="display:grid; grid-template-columns:1fr 1fr; gap:10px">
                @foreach([
                    ['value'=>'hoy',    'label'=>'Hoy',          'desc'=> now()->format('d/m/Y'),                              'icon'=>'☀️'],
                    ['value'=>'semana', 'label'=>'Esta semana',   'desc'=> now()->startOfWeek()->format('d/m') . ' – ' . now()->endOfWeek()->format('d/m/Y'), 'icon'=>'📅'],
                    ['value'=>'mes',    'label'=>'Este mes',      'desc'=> ucfirst(now()->locale('es')->translatedFormat('F Y')), 'icon'=>'🗓'],
                    ['value'=>'anno',   'label'=>'Este año',      'desc'=> now()->format('Y'),                                  'icon'=>'📆'],
                    ['value'=>'todo',   'label'=>'Todo el historial', 'desc'=> 'Sin filtro de fecha',                          'icon'=>'🗂'],
                ] as $opt)
                <label class="period-card" style="display:flex; align-items:flex-start; gap:12px; padding:14px 16px; border:2px solid var(--color-border); border-radius:10px; cursor:pointer; transition:border-color 0.15s, background 0.15s; user-select:none">
                    <input type="radio" name="periodo" value="{{ $opt['value'] }}" {{ $opt['value'] === 'todo' ? 'checked' : '' }} style="margin-top:2px; accent-color:#10b981">
                    <div>
                        <div style="font-size:14px; font-weight:600">{{ $opt['icon'] }} {{ $opt['label'] }}</div>
                        <div style="font-size:11.5px; color:var(--color-text-muted); margin-top:2px">{{ $opt['desc'] }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Export actions --}}
        <div class="card" style="padding:24px">
            <div style="font-weight:600; font-size:15px; margin-bottom:16px">Formato de exportación</div>
            <div style="display:flex; gap:12px">
                <a id="btn-excel" href="{{ route('ventas.export.excel') }}?periodo=todo" class="btn btn-secondary" style="flex:1; justify-content:center; padding:12px">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
                    Descargar Excel (CSV)
                </a>
                <a id="btn-pdf" href="{{ route('ventas.export.pdf') }}?periodo=todo" target="_blank" class="btn btn-secondary" style="flex:1; justify-content:center; padding:12px">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="9.01" y2="15"/></svg>
                    Abrir PDF
                </a>
            </div>
        </div>

    </div>

    <style>
        .period-card:has(input:checked) {
            border-color: #10b981;
            background: rgba(16,185,129,0.04);
        }
    </style>

    <script>
        const radios = document.querySelectorAll('input[name="periodo"]');
        const btnExcel = document.getElementById('btn-excel');
        const btnPdf   = document.getElementById('btn-pdf');
        const baseExcel = "{{ route('ventas.export.excel') }}";
        const basePdf   = "{{ route('ventas.export.pdf') }}";

        radios.forEach(r => {
            r.addEventListener('change', () => {
                btnExcel.href = baseExcel + '?periodo=' + r.value;
                btnPdf.href   = basePdf   + '?periodo=' + r.value;
            });
        });
    </script>

</x-app-layout>
