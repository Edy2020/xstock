<x-app-layout :pageTitle="'Detalle de Producto'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>{{ $producto->nombre ?? 'Monitor LG 24"' }}</h1>
            <p style="font-family:monospace">{{ $producto->sku ?? 'PRD-001' }}</p>
        </div>
        <div style="display:flex; gap:8px">
            <a href="{{ route('productos.edit', $producto->id ?? 1) }}" class="btn btn-primary">Editar</a>
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
                    <span class="badge badge-green">En stock</span>
                </div>
                <div style="display:flex; flex-direction:column; gap:12px; font-size:13px">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Nombre</span>
                        <span style="font-weight:500">Monitor LG 24"</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">SKU</span>
                        <span style="font-family:monospace">PRD-001</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Categoría</span>
                        <span>Electrónica</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Proveedor</span>
                        <span>TechParts S.A.</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Precio de costo</span>
                        <span>$250.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted)">Precio de venta</span>
                        <span style="font-weight:600; color:var(--color-primary)">$320.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="color:var(--color-text-muted)">Margen</span>
                        <span style="color:var(--color-success); font-weight:500">+28% ($70.00)</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Descripción</div>
                <p style="font-size:13px; color:var(--color-text-muted); margin:0; line-height:1.6">
                    Monitor Full HD de 24 pulgadas con panel IPS, resolución 1920×1080, tiempo de respuesta 5ms. Ideal para oficina y diseño.
                </p>
            </div>
        </div>

        {{-- Stock y ventas --}}
        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Inventario</div>
                <div class="grid-2" style="gap:10px; margin-bottom:14px">
                    <div class="stat-card" style="padding:14px">
                        <div class="stat-label">Stock actual</div>
                        <div class="stat-value" style="font-size:28px">15</div>
                    </div>
                    <div class="stat-card" style="padding:14px">
                        <div class="stat-label">Stock mínimo</div>
                        <div class="stat-value" style="font-size:28px; color:var(--color-text-muted)">5</div>
                    </div>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:75%"></div>
                </div>
                <div style="font-size:11.5px; color:var(--color-text-muted); margin-top:6px">75% de capacidad óptima</div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Últimas ventas de este producto</div>
                <div class="table-wrapper" style="border:none; margin:0 -20px">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cant.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-family:monospace; font-size:12px; color:var(--color-text-muted)">#1042</td>
                                <td>18/03/2026</td>
                                <td>1</td>
                                <td>$320.00</td>
                            </tr>
                            <tr>
                                <td style="font-family:monospace; font-size:12px; color:var(--color-text-muted)">#1031</td>
                                <td>15/03/2026</td>
                                <td>2</td>
                                <td>$640.00</td>
                            </tr>
                            <tr>
                                <td style="font-family:monospace; font-size:12px; color:var(--color-text-muted)">#1018</td>
                                <td>10/03/2026</td>
                                <td>1</td>
                                <td>$320.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
