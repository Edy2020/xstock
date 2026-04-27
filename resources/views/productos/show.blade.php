<x-app-layout :pageTitle="'Detalle de Producto'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>{{ $producto->nombre }}</h1>
            <p style="font-family:monospace">ID-{{ str_pad($producto->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            @if(auth()->user()->hasPermission('productos.editar'))
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-primary">Editar</a>
            @endif
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver
            </a>
        </div>
    </div>

    <div class="split-layout">

        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Información general</div>
                    <span class="badge {{ $producto->badge_estado }}">{{ $producto->label_estado }}</span>
                </div>
                <div style="display:flex; flex-direction:column; gap:12px; font-size:13px">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Nombre</span>
                        <span style="font-weight:500; text-align:right; word-break:break-word; max-width:70%">{{ $producto->nombre }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Categoría</span>
                        <span style="text-align:right">{{ $producto->categoria ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Proveedor</span>
                        <span style="text-align:right">{{ $producto->proveedor->nombre ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Precio de venta</span>
                        <span style="font-weight:600; color:var(--color-primary); text-align:right">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                    </div>
                    @if($producto->descuento > 0)
                    <div style="display:flex; justify-content:space-between; padding-bottom:4px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Descuento predefinido</span>
                        <span style="font-weight:600; color:var(--color-danger); text-align:right">{{ $producto->descuento }}%</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($producto->descripcion)
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Descripción</div>
                <p style="font-size:13px; color:var(--color-text-muted); margin:0; line-height:1.6">
                    {!! nl2br(e($producto->descripcion)) !!}
                </p>
            </div>
            @endif

            <div class="card" style="min-width:0">
                <div class="card-title" style="margin-bottom:12px">Últimas ventas de este producto</div>
                <div class="table-wrapper" style="border:none;">
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

        <div style="display:flex; flex-direction:column; gap:16px; min-width:0;">
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
            @if($producto->imagen)
            <div class="card" style="padding:12px; display:flex; justify-content:center; align-items:center; background: var(--color-bg-alt, var(--color-surface));">
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                     style="max-width:100%; max-height:300px; object-fit:contain; border-radius:6px; display:block">
            </div>
            @endif
            <div class="card" style="display:flex; align-items:center; gap:12px; padding:12px;">
                @php
                    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(60)->generate($producto->id);
                    $qrCodeHighRes = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(400)->generate($producto->id);
                    $qrBase64 = base64_encode($qrCodeHighRes);
                @endphp
                <div style="background:white; padding:6px; border-radius:6px; border:1px solid var(--color-border); display:inline-flex; flex-shrink:0;">
                    {!! $qrCode !!}
                </div>
                <div style="flex:1">
                    <div style="font-size:11px; color:var(--color-text-muted); font-weight:600; margin-bottom:2px">Código de Identificación</div>
                    <div style="font-size:10px; color:var(--color-text-muted); margin-bottom:8px; line-height:1.2">Escanea para acceder desde dispositivos móviles.</div>
                    <a href="data:image/svg+xml;base64,{{ $qrBase64 }}" download="qr-producto-{{ $producto->id }}.svg" class="btn btn-secondary btn-sm" style="width:100%; justify-content:center; gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Descargar Etiqueta
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
