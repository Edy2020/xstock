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
            <div class="stat-label">Total ventas ({{ $periodo }})</div>
            <div class="stat-value">{{ number_format($ventasPeriodo, 0, ',', '.') }}</div>
            <div class="stat-change"><span class="badge badge-green" style="font-size:10.5px">Actualizado recientemente</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-label">Ingresos ({{ $periodo }})</div>
            <div class="stat-value">${{ number_format($ingresosPeriodo, 0, ',', '.') }}</div>
            <div class="stat-change"><span class="badge badge-green" style="font-size:10.5px">Dinero bruto ingresado</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-label">Total productos</div>
            <div class="stat-value">{{ number_format($totalProductos, 0, ',', '.') }}</div>
            <div class="stat-change"><span class="badge badge-gray" style="font-size:10.5px">+{{ $nuevoEsteMes }} este mes</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
            </div>
            <div class="stat-label">Stock crítico (<=5)</div>
            <div class="stat-value">{{ number_format($stockCritico, 0, ',', '.') }}</div>
            @if($stockCritico > 0)
                <div class="stat-change"><span class="badge badge-red" style="font-size:10.5px">Requiere acción</span></div>
            @else
                <div class="stat-change"><span class="badge badge-green" style="font-size:10.5px">Inventario sano</span></div>
            @endif
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
                        @foreach($diasData as $d)
                        <tr>
                            <td style="text-transform:capitalize">{{ $d['dia'] }}</td>
                            <td>{{ $d['ventas'] }}</td>
                            <td style="font-weight:500">{{ $d['ing'] }}</td>
                            <td><span class="badge badge-gray" style="font-size:10.5px">—</span></td>
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
            <div style="display:flex; flex-direction:column; gap:12px">
                @if($top->isEmpty())
                    <div style="padding:20px; text-align:center; color:var(--color-text-muted)">Aún no hay ventas registradas.</div>
                @else
                    @foreach($top as $i => $t)
                    <div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:13px">
                            <span style="font-weight:500">{{ $i+1 }}. {{ $t['nombre'] }}</span>
                            <span style="color:var(--color-text-muted)">{{ $t['ventas'] }} vendidos</span>
                        </div>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-fill" style="width:{{ $t['pct'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @endif
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
                        @forelse($inventarioCat as $cat)
                            <tr>
                                <td style="text-transform:capitalize">{{ $cat->categoria }}</td>
                                <td>{{ $cat->cantidad_productos }}</td>
                                <td>{{ number_format($cat->stock_total, 0, ',', '.') }}</td>
                                <td>${{ number_format($cat->valor_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center; padding:20px; color:var(--color-text-muted)">No hay categorías registradas</td></tr>
                        @endforelse
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:14px">Tendencia de Ventas — Últimas 4 semanas</div>
            <div style="display:flex; flex-direction:column; gap:10px">
                @foreach($semanasData as $s)
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px; padding:8px 0; border-bottom:1px solid var(--color-border)">
                    <span style="color:var(--color-text-muted)">{{ $s['sem'] }}</span>
                    <div style="display:flex; gap:16px; align-items:center">
                        <span>{{ $s['ventas'] }} ventas</span>
                        <span style="font-weight:600">{{ $s['ing'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Productos en Stock Crítico --}}
    @if($stockCritico > 0)
    <div class="card" style="margin-top: 20px; border-left: 4px solid var(--color-danger);">
        <div class="card-title" style="margin-bottom:14px; color: var(--color-danger); display:flex; align-items:center; gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Productos con Stock Crítico ({{ $stockCritico }})
        </div>
        <div class="table-wrapper" style="border:none; margin:0 -20px">
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

</x-app-layout>
