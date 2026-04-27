<x-app-layout :pageTitle="'Detalle de Proveedor'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>{{ $proveedor->nombre }}</h1>
            <p style="font-family:monospace">{{ $proveedor->ruc ? 'RUT/RUC/ID: ' . $proveedor->ruc : 'Sin Identificador Registrado' }}</p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap">
            @if(auth()->user()->hasPermission('proveedores.editar'))
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-primary">Editar</a>
            @endif
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver
            </a>
        </div>
    </div>

    <div class="split-layout">

        <div style="display:flex; flex-direction:column; gap:16px">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Información del Proveedor</div>
                    <span class="badge {{ $proveedor->estado === 'activo' ? 'badge-green' : 'badge-red' }}">{{ ucfirst($proveedor->estado) }}</span>
                </div>
                <div style="display:flex; flex-direction:column; gap:12px; font-size:13px">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Razón Social</span>
                        <span style="font-weight:500; text-align:right; word-break:break-word; max-width:70%">{{ $proveedor->nombre }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Persona de Contacto</span>
                        <span style="text-align:right; word-break:break-word; max-width:70%">{{ $proveedor->contacto ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Teléfono / Móvil</span>
                        <span style="text-align:right">{{ $proveedor->telefono ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--color-border); padding-bottom:10px">
                        <span style="color:var(--color-text-muted); flex-shrink:0;">Correo Electrónico</span>
                        <span style="color:var(--color-primary); text-align:right; word-break:break-word; max-width:70%">{{ $proveedor->email ?? '—' }}</span>
                    </div>
                    <div style="display:flex; flex-direction:column; padding-bottom:4px">
                        <span style="color:var(--color-text-muted); margin-bottom:4px">Dirección Física</span>
                        <span style="line-height:1.4">{{ $proveedor->direccion ?? 'No especificada.' }}</span>
                    </div>
                </div>
            </div>

            @if($proveedor->notas)
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Anotaciones</div>
                <p style="font-size:13px; color:var(--color-text-muted); margin:0; line-height:1.6">
                    {{ $proveedor->notas }}
                </p>
            </div>
            @endif

            <div class="card" style="min-width:0">
                <div class="card-title" style="margin-bottom:12px">Catálogo Ligado a este Proveedor</div>
                <div class="table-wrapper" style="border:none; max-height: 400px; overflow-y: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Precio (PVP)</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $prod)
                            <tr style="cursor:pointer" onclick="window.location='{{ route('productos.show', $prod) }}'">
                                <td style="font-weight:500">{{ $prod->nombre }}</td>
                                <td>{{ $prod->categoria ?? '—' }}</td>
                                <td>{{ $prod->stock }}</td>
                                <td>${{ number_format($prod->precio, 0, ',', '.') }}</td>
                                <td><span class="badge {{ $prod->badge_estado }}">{{ $prod->label_estado }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center; padding:20px; color:var(--color-text-muted)">Este proveedor no suministra ningún producto actualmente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Logística / Productos --}}
        <div style="display:flex; flex-direction:column; gap:16px">
            
            <div class="grid-2" style="gap:10px">
                <div class="stat-card" style="padding:14px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:8px">
                    <div class="stat-label" style="font-size:12px; color:var(--color-text-muted); text-transform:uppercase; font-weight:600; letter-spacing:0.5px">Artículos Suministrados</div>
                    <div class="stat-value" style="font-size:28px; font-weight:700; margin-top:8px">{{ $productos->count() }}</div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
