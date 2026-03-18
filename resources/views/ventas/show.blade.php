<x-app-layout :pageTitle="'Detalle de la Venta'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Venta #{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p>Emitida el {{ $venta->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
        </div>
        <div style="display:flex; gap:8px">
            <button class="btn btn-primary" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimir Recibo
            </button>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Agregar unos estilos temporales de impresion para que no salga todo el UI -->
    <style>
        @media print {
            .sidebar, .topbar, .btn { display: none !important; }
            .content-area { padding: 0 !important; margin: 0 !important; width: 100% !important; background: white !important;}
            body { background: white; }
            .card { border: none !important; box-shadow: none !important; }
            .page-header { margin-top: 0; padding-top:0; border-bottom:1px solid #000; padding-bottom:10px; }
        }
    </style>

    <div class="grid-2">
        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Resumen de la Orden</div>
                </div>
                <div style="display:flex; flex-direction:column; gap:12px; font-size:13px">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">ID Transacción</span>
                        <span style="font-weight:600; font-family:monospace">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Fecha y Hora</span>
                        <span>{{ $venta->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Método de Pago</span>
                        <span style="text-transform:capitalize; color:var(--color-primary); font-weight:500">{{ $venta->metodo_pago }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Vendedor</span>
                        <span style="font-weight:500">{{ $venta->vendedor->name ?? 'No registrado' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding-bottom:4px">
                        <span style="color:var(--color-text-muted)">Unidades Totales</span>
                        <span>{{ $venta->detalles->sum('cantidad') }} artículos</span>
                    </div>
                </div>
            </div>

            @if($venta->notas)
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Anotaciones de la Venta</div>
                <p style="font-size:13px; color:var(--color-text-muted); line-height:1.5; margin:0">
                    {{ $venta->notas }}
                </p>
            </div>
            @endif
        </div>

        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Facturación</div>
                <div style="display:flex; flex-direction:column; gap:10px; font-size:14px; background:var(--color-bg); padding:16px; border-radius:8px">
                    <div style="display:flex; justify-content:space-between">
                        <span style="color:var(--color-text-muted)">Subtotal de los productos</span>
                        <span style="font-weight:500">${{ number_format($venta->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="color:var(--color-text-muted)">Descuentos aplicados</span>
                        <span style="font-weight:500; color:var(--color-danger)">- ${{ number_format($venta->descuento_total, 0, ',', '.') }}</span>
                    </div>
                    <div style="border-top:1px dashed var(--color-border); margin:4px 0"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:18px">
                        <span style="font-weight:600">Total Pagado</span>
                        <span style="font-weight:700; color:var(--color-success)">${{ number_format($venta->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Productos Facturados</div>
                <div class="table-wrapper" style="border:none; margin:0 -20px; overflow-y:auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Artículo</th>
                                <th>Precio Unit.</th>
                                <th>Cant.</th>
                                <th>Desc.</th>
                                <th>Subtotal Parcial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                            <tr @if($detalle->producto_id) style="cursor:pointer" onclick="window.location='{{ route('productos.show', $detalle->producto_id) }}'" @endif>
                                <td style="font-weight:500">{{ $detalle->producto_nombre }}</td>
                                <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>
                                    @if($detalle->descuento_porcentaje)
                                    <span class="badge" style="background:var(--color-danger); color:white">{{ $detalle->descuento_porcentaje }}% OFF</span>
                                    @else
                                    <span style="color:var(--color-text-muted)">—</span>
                                    @endif
                                </td>
                                <td style="font-weight:600">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
