<x-app-layout :pageTitle="'Detalle de Producto'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>{{ $producto->nombre }}</h1>
            <p style="font-family:monospace">ID-{{ str_pad($producto->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div style="display:flex; gap:8px">
            @if(auth()->user()->hasPermission('productos.editar'))
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-primary">Editar</a>
            @endif
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver
            </a>
        </div>
    </div>

    <div class="grid-2">

        {{-- Info principal --}}
        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Información general</div>
                    <span class="badge {{ $producto->badge_estado }}">{{ $producto->label_estado }}</span>
                </div>
                <div style="display:flex; flex-direction:column; gap:12px; font-size:13px">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Nombre</span>
                        <span style="font-weight:500">{{ $producto->nombre }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Categoría</span>
                        <span>{{ $producto->categoria ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Proveedor</span>
                        <span>{{ $producto->proveedor->nombre ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding-bottom:4px">
                        <span style="color:var(--color-text-muted)">Precio de venta</span>
                        <span style="font-weight:600; color:var(--color-primary)">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock y ventas --}}
        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Inventario</div>
                <div class="grid-2" style="gap:10px; margin-bottom:14px">
                    <div class="stat-card" style="padding:14px">
                        <div class="stat-label">Stock actual</div>
                        <div class="stat-value" style="font-size:28px">{{ $producto->stock }}</div>
                    </div>
                    <div class="stat-card" style="padding:14px; opacity:0.75">
                        <div class="stat-label">Stock mínimo (Sugerido)</div>
                        <div class="stat-value" style="font-size:28px; color:var(--color-text-muted)">5</div>
                    </div>
                </div>
                @php
                    $isRed = $producto->stock <= 5;
                    $min = 20; // Límite %
                    $porcentaje = min(($producto->stock / $min) * 100, 100);
                @endphp
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:{{ $porcentaje }}%; background:{{ $isRed ? 'var(--color-danger)' : 'var(--color-success)' }}"></div>
                </div>
                <div style="font-size:11.5px; color:var(--color-text-muted); margin-top:6px">
                    {{ $isRed ? 'Nivel de inventario Crítico o Agotado' : 'Capacidad Óptima' }}
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Últimas ventas de este producto</div>
                <div class="table-wrapper" style="border:none; margin:0 -20px">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th># Venta</th>
                                <th>Fecha</th>
                                <th>Cant. Vendida</th>
                                <th>Total Parcial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasVentas as $detalle)
                            <tr>
                                <td style="font-family:monospace; font-size:12px; color:var(--color-text-muted)">#{{ str_pad($detalle->venta_id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $detalle->created_at->format('d/m/Y') }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>${{ number_format($detalle->precio_unitario * $detalle->cantidad, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" style="text-align:center; padding:20px; color:var(--color-text-muted)">No hay ventas registradas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
