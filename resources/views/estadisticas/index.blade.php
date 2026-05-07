<x-app-layout :pageTitle="'Estadísticas'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Estadísticas</h1>
            <p>Análisis de rendimiento del inventario y ventas</p>
        </div>
        <div style="display:flex; gap:6px; align-items:center;">
            <button type="button" onclick="setPeriodData('dia')" class="btn btn-secondary btn-sm filter-btn-period" data-period="dia">Hoy</button>
            <button type="button" onclick="setPeriodData('semana')" class="btn btn-primary btn-sm filter-btn-period" data-period="semana">Semana</button>
            <button type="button" onclick="setPeriodData('mes')" class="btn btn-secondary btn-sm filter-btn-period" data-period="mes">Mes</button>
            <button type="button" onclick="setPeriodData('anio')" class="btn btn-secondary btn-sm filter-btn-period" data-period="anio">Año</button>
        </div>
    </div>

    <div id="stats-tabs-container" style="display:flex; gap:10px; margin-bottom:20px; border-bottom:1px solid var(--color-border); padding-bottom:12px; overflow-x:auto; -webkit-overflow-scrolling: touch;">
        <button onclick="switchStatsTab('general')" id="tab-btn-general" class="btn btn-primary" style="border-radius:20px; flex-shrink:0">General</button>
        <button onclick="switchStatsTab('ventas')" id="tab-btn-ventas" class="btn btn-secondary" style="border-radius:20px; flex-shrink:0">Ventas</button>
        <button onclick="switchStatsTab('inventario')" id="tab-btn-inventario" class="btn btn-secondary" style="border-radius:20px; flex-shrink:0">Inventario y Alertas</button>
    </div>

    <div id="tab-pane-general">
    <div style="display:grid; grid-template-columns: repeat(6, 1fr); gap:12px; margin-bottom:24px">
        <div class="stat-card" style="padding: 12px;">
            <div class="stat-icon" style="background:#eff6ff; width:32px; height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="stat-label" id="lbl-ventas" style="font-size:11px">{{ $periodData['semana']['labelVentas'] ?? 'Total ventas' }}</div>
            <div class="stat-value" id="val-ventas" style="font-size:20px">{{ $periodData['semana']['ventas'] ?? 0 }}</div>
        </div>
        <div class="stat-card" style="padding: 12px;">
            <div class="stat-icon" style="background:#f0fdf4; width:32px; height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-label" id="lbl-ingresos" style="font-size:11px">{{ $periodData['semana']['labelIngresos'] ?? 'Ingresos' }}</div>
            <div class="stat-value" id="val-ingresos" style="font-size:20px; color:var(--color-success)">{{ $periodData['semana']['ingresos'] ?? '$0' }}</div>
        </div>
        <div class="stat-card" style="padding: 12px;">
            <div class="stat-icon" style="background:#fef2f2; width:32px; height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-label" id="lbl-gastos" style="font-size:11px">{{ $periodData['semana']['labelGastos'] ?? 'Gastos' }}</div>
            <div class="stat-value" id="val-gastos" style="font-size:20px; color:var(--color-danger)">{{ $periodData['semana']['gastos'] ?? '$0' }}</div>
        </div>
        <div class="stat-card" style="padding: 12px;">
            <div class="stat-icon" id="icon-balance-bg" style="background:{{ ($periodData['semana']['balance_raw'] ?? 0) >= 0 ? '#f0fdf4' : '#fef2f2' }}; width:32px; height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ ($periodData['semana']['balance_raw'] ?? 0) >= 0 ? '#16a34a' : '#dc2626' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="icon-balance-svg"><path d="M3 3v18h18"/><path d="M18 17l-6-6-2 2-4-4"/></svg>
            </div>
            <div class="stat-label" id="lbl-balance" style="font-size:11px">{{ $periodData['semana']['labelBalance'] ?? 'Balance' }}</div>
            <div class="stat-value" id="val-balance" style="font-size:20px">{{ $periodData['semana']['balance'] ?? '$0' }}</div>
        </div>
        <div class="stat-card" style="padding: 12px;">
            <div class="stat-icon" style="background:#fef9c3; width:32px; height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-label" style="font-size:11px">Total productos</div>
            <div class="stat-value" style="font-size:20px">{{ number_format($totalProductos, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card" style="padding: 12px;">
            <div class="stat-icon" style="background:#fef2f2; width:32px; height:32px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
            </div>
            <div class="stat-label" style="font-size:11px">Stock crítico</div>
            <div class="stat-value" style="font-size:20px">{{ number_format($stockCritico, 0, ',', '.') }}</div>
        </div>
    </div>
    </div>

    <div id="tab-pane-ventas" style="display:none;">
    <div class="grid-2" style="margin-bottom:20px">

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Ventas por día — Esta semana</div>
                    <div class="card-subtitle">Comparativa con semana anterior</div>
                </div>
            </div>
            <div style="height:250px; position:relative;">
                <canvas id="chart-ventas"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Productos más vendidos</div>
            </div>
            <div style="display:flex; flex-direction:column; gap:12px">
                @if($top->isEmpty())
                    <div style="padding:20px; text-align:center; color:var(--color-text-muted)">Aún no hay ventas registradas.</div>
                @else
                    @foreach($top as $i => $t)
                    <div style="display:flex; align-items:center; gap:10px;">
                        @if($i == 0)
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        @elseif($i == 1)
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        @elseif($i == 2)
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        @else
                            <div style="width:20px; text-align:center; font-size:12px; font-weight:600; color:var(--color-text-muted)">{{ $i+1 }}</div>
                        @endif
                        <div style="flex:1">
                            <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:13px">
                                <span style="font-weight:500">{{ $t['nombre'] }}</span>
                                <span style="color:var(--color-text-muted)">{{ $t['ventas'] }} vendidos</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" style="width:{{ $t['pct'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
    </div>

    <div id="tab-pane-inventario" style="display:none;">
    <div class="grid-2">
        <div class="card">
            <div class="card-title" style="margin-bottom:14px">Inventario por Categoría</div>
            <div style="height:250px; position:relative;">
                <canvas id="chart-categorias"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:14px">Tendencia de Ventas — Últimas 4 semanas</div>
            <div style="height:250px; position:relative;">
                <canvas id="chart-tendencia"></canvas>
            </div>
        </div>
    </div>
    @if($stockCritico > 0)
    <div class="card" style="margin-top: 20px; border-left: 4px solid var(--color-danger);">
        <div class="card-title" style="margin-bottom:14px; color: var(--color-danger); display:flex; align-items:center; gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Productos con Stock Crítico ({{ $stockCritico }})
        </div>
        <div class="table-wrapper" style="border:none">
            <table class="data-table">
                <thead style="background: rgba(220, 38, 38, 0.05);">
                    <tr>
                        <th style="padding-left: 20px;">Nombre producto</th>
                        <th>Categoría</th>
                        <th>Precio Venta</th>
                        <th>Unidades restantes</th>
                        <th style="text-align: right; padding-right: 20px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productosCriticos as $prod)
                        <tr>
                            <td style="padding-left: 20px; font-weight: 500;">
                                {{ $prod->nombre }}
                            </td>
                            <td><span class="badge badge-gray" style="text-transform: capitalize;">{{ $prod->categoria }}</span></td>
                            <td>${{ number_format($prod->precio, 0, ',', '.') }}</td>
                            <td>
                                @if($prod->stock == 0)
                                    <span class="badge badge-red" style="font-weight:700">Agotado (0)</span>
                                @else
                                    <span class="badge badge-yellow" style="font-weight:700">{{ $prod->stock }} un.</span>
                                @endif
                            </td>
                            <td style="text-align: right; padding-right: 20px;">
                                <a href="{{ route('productos.edit', $prod->id) }}" class="btn btn-secondary btn-sm" style="font-size: 11.5px; padding: 4px 10px;">Reabastecer / Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    </div>

    <script>
        window.chartData = {
            periodData: @json($periodData ?? []),
            dias: @json($diasData ?? []),
            categorias: @json($inventarioCat ?? []),
            semanas: @json($semanasData ?? [])
        };
    </script>
    <x-estadisticas-scripts />
</x-app-layout>
