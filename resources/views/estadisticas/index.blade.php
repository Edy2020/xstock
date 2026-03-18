<x-app-layout :pageTitle="'Estadísticas'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Estadísticas</h1>
            <p>Análisis de rendimiento del inventario y ventas</p>
        </div>
        {{-- Selector de periodo --}}
        <div style="display:flex; gap:6px">
            <a href="?periodo=dia" class="btn btn-secondary btn-sm {{ request('periodo','semana')==='dia' ? 'active' : '' }}">Hoy</a>
            <a href="?periodo=semana" class="btn {{ request('periodo','semana')==='semana' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Semana</a>
            <a href="?periodo=mes" class="btn btn-secondary btn-sm {{ request('periodo','semana')==='mes' ? 'active' : '' }}">Mes</a>
            <a href="?periodo=anio" class="btn btn-secondary btn-sm {{ request('periodo','semana')==='anio' ? 'active' : '' }}">Año</a>
        </div>
    </div>

    {{-- KPIs principales --}}
    <div class="grid-4" style="margin-bottom:24px">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="stat-label">Total ventas (semana)</div>
            <div class="stat-value">218</div>
            <div class="stat-change"><span class="badge badge-green" style="font-size:10.5px">↑ 14% vs semana ant.</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-label">Ingresos (semana)</div>
            <div class="stat-value">$9,840</div>
            <div class="stat-change"><span class="badge badge-green" style="font-size:10.5px">↑ 8% vs semana ant.</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-label">Total productos</div>
            <div class="stat-value">248</div>
            <div class="stat-change"><span class="badge badge-gray" style="font-size:10.5px">+12 este mes</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
            </div>
            <div class="stat-label">Stock crítico</div>
            <div class="stat-value">7</div>
            <div class="stat-change"><span class="badge badge-red" style="font-size:10.5px">Requiere acción</span></div>
        </div>
    </div>

    <div class="grid-2" style="margin-bottom:20px">

        {{-- Ventas por día (tabla de muestra) --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Ventas por día — Esta semana</div>
                    <div class="card-subtitle">Comparativa con semana anterior</div>
                </div>
            </div>
            <div class="table-wrapper" style="border:none; margin:0 -20px">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Ventas</th>
                            <th>Ingresos</th>
                            <th>vs ant.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $dias = [
                            ['dia'=>'Lunes','ventas'=>28,'ing'=>'$1,240','vs'=>'+5%','col'=>'badge-green'],
                            ['dia'=>'Martes','ventas'=>34,'ing'=>'$1,580','vs'=>'+12%','col'=>'badge-green'],
                            ['dia'=>'Miércoles','ventas'=>22,'ing'=>'$980','vs'=>'-3%','col'=>'badge-yellow'],
                            ['dia'=>'Jueves','ventas'=>41,'ing'=>'$2,100','vs'=>'+18%','col'=>'badge-green'],
                            ['dia'=>'Viernes','ventas'=>55,'ing'=>'$2,640','vs'=>'+22%','col'=>'badge-green'],
                            ['dia'=>'Sábado','ventas'=>23,'ing'=>'$900','vs'=>'-8%','col'=>'badge-yellow'],
                            ['dia'=>'Domingo (hoy)','ventas'=>15,'ing'=>'$400','vs'=>'+2%','col'=>'badge-green'],
                        ];
                        @endphp
                        @foreach($dias as $d)
                        <tr>
                            <td>{{ $d['dia'] }}</td>
                            <td>{{ $d['ventas'] }}</td>
                            <td style="font-weight:500">{{ $d['ing'] }}</td>
                            <td><span class="badge {{ $d['col'] }}" style="font-size:10.5px">{{ $d['vs'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Productos más vendidos --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Productos más vendidos</div>
            </div>
            @php
            $top = [
                ['nombre'=>'Teclado Mecánico','ventas'=>82,'pct'=>82],
                ['nombre'=>'Mouse Inalámbrico','ventas'=>71,'pct'=>71],
                ['nombre'=>'Cable HDMI 3m','ventas'=>65,'pct'=>65],
                ['nombre'=>'Monitor LG 24"','ventas'=>48,'pct'=>48],
                ['nombre'=>'Hub USB 7P','ventas'=>31,'pct'=>31],
            ];
            @endphp
            <div style="display:flex; flex-direction:column; gap:12px">
                @foreach($top as $i => $t)
                <div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:13px">
                        <span style="font-weight:500">{{ $i+1 }}. {{ $t['nombre'] }}</span>
                        <span style="color:var(--color-text-muted)">{{ $t['ventas'] }} ventas</span>
                    </div>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-fill" style="width:{{ $t['pct'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Stock por categoría y tendencia --}}
    <div class="grid-2">
        <div class="card">
            <div class="card-title" style="margin-bottom:14px">Inventario por Categoría</div>
            <div class="table-wrapper" style="border:none; margin:0 -20px">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Productos</th>
                            <th>Stock total</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Electrónica</td><td>45</td><td>320</td><td>$48,000</td></tr>
                        <tr><td>Accesorios</td><td>89</td><td>1,240</td><td>$18,600</td></tr>
                        <tr><td>Cables</td><td>62</td><td>890</td><td>$5,340</td></tr>
                        <tr><td>Periféricos</td><td>52</td><td>420</td><td>$21,000</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:14px">Tendencia de Ventas — Últimas 4 semanas</div>
            @php
            $semanas = [
                ['sem'=>'Sem 1 (25 Feb — 3 Mar)','ventas'=>184,'ing'=>'$7,200','trend'=>'—'],
                ['sem'=>'Sem 2 (4 — 10 Mar)','ventas'=>196,'ing'=>'$8,100','trend'=>'+6.5%'],
                ['sem'=>'Sem 3 (11 — 17 Mar)','ventas'=>209,'ing'=>'$9,240','trend'=>'+14.1%'],
                ['sem'=>'Sem 4 (18 — hoy)','ventas'=>218,'ing'=>'$9,840','trend'=>'+6.5%'],
            ];
            @endphp
            <div style="display:flex; flex-direction:column; gap:10px">
                @foreach($semanas as $s)
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px; padding:8px 0; border-bottom:1px solid var(--color-border)">
                    <span style="color:var(--color-text-muted)">{{ $s['sem'] }}</span>
                    <div style="display:flex; gap:16px; align-items:center">
                        <span>{{ $s['ventas'] }} ventas</span>
                        <span style="font-weight:600">{{ $s['ing'] }}</span>
                        @if($s['trend'] !== '—')
                            <span class="badge badge-green" style="font-size:10.5px">{{ $s['trend'] }}</span>
                        @else
                            <span style="color:var(--color-text-muted)">—</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>
