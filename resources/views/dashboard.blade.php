<x-app-layout :pageTitle="'Dashboard'">

    {{-- Bienvenida + accesos rápidos --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1>Bienvenido, {{ Auth::user()->name ?? 'Usuario' }}</h1>
            <p>Panel de control — visión general del sistema de inventario</p>
        </div>
        <div style="display:flex; gap:8px">
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="grid-stats" style="margin-bottom:24px">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-label">Total Productos</div>
            <div class="stat-value">248</div>
            <div class="stat-change">
                <span class="badge badge-green" style="font-size:10.5px">+12 este mes</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="stat-label">Ventas Hoy</div>
            <div class="stat-value">34</div>
            <div class="stat-change">
                <span class="badge badge-green" style="font-size:10.5px">↑ 8% vs ayer</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a16207" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="stat-label">Stock Crítico</div>
            <div class="stat-value">7</div>
            <div class="stat-change">
                <span class="badge badge-yellow" style="font-size:10.5px">Requiere atención</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div class="stat-label">Proveedores</div>
            <div class="stat-value">18</div>
            <div class="stat-change">
                <span class="badge badge-gray" style="font-size:10.5px">3 activos hoy</span>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div style="margin-bottom:8px; font-size:13px; font-weight:600; color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.05em">Acceso Rápido</div>
    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:12px; margin-bottom:28px" class="quick-actions-grid">
        <a href="{{ route('productos.create') }}" class="quick-action">
            <div class="qa-icon" style="background:#eff6ff; color:#2563eb">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <div class="qa-title">Añadir Producto</div>
            <div class="qa-desc">Registrar nuevo artículo</div>
        </a>
        <a href="{{ route('ventas.create') }}" class="quick-action">
            <div class="qa-icon" style="background:#f0fdf4; color:#16a34a">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="qa-title">Nueva Venta</div>
            <div class="qa-desc">Registrar venta al cliente</div>
        </a>
        <a href="{{ route('proveedores.create') }}" class="quick-action">
            <div class="qa-icon" style="background:#fef9c3; color:#a16207">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div class="qa-title">Añadir Proveedor</div>
            <div class="qa-desc">Registrar nuevo proveedor</div>
        </a>
        <a href="{{ route('estadisticas.index') }}" class="quick-action">
            <div class="qa-icon" style="background:#f5f3ff; color:#7c3aed">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div class="qa-title">Ver Estadísticas</div>
            <div class="qa-desc">Análisis de rendimiento</div>
        </a>
        <a href="{{ route('historial.index') }}" class="quick-action">
            <div class="qa-icon" style="background:#f4f4f5; color:#52525b">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="qa-title">Historial</div>
            <div class="qa-desc">Registro de actividad</div>
        </a>
        <a href="{{ route('roles.index') }}" class="quick-action">
            <div class="qa-icon" style="background:#fff1f2; color:#be123c">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <div class="qa-title">Roles y Permisos</div>
            <div class="qa-desc">Gestión de accesos</div>
        </a>
    </div>

    {{-- Dos columnas: últimas ventas + stock crítico --}}
    <div class="grid-2">

        {{-- Últimas Ventas --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Últimas Ventas</div>
                    <div class="card-subtitle">Hoy, {{ now()->format('d/m/Y') }}</div>
                </div>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-sm">Ver todo</a>
            </div>
            <div class="table-wrapper" style="border:none">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="color:var(--color-text-muted)">#1042</td>
                            <td>Monitor LG 24"</td>
                            <td>1</td>
                            <td>$320.00</td>
                            <td><span class="badge badge-green">Completada</span></td>
                        </tr>
                        <tr>
                            <td style="color:var(--color-text-muted)">#1041</td>
                            <td>Teclado Mecánico</td>
                            <td>2</td>
                            <td>$180.00</td>
                            <td><span class="badge badge-green">Completada</span></td>
                        </tr>
                        <tr>
                            <td style="color:var(--color-text-muted)">#1040</td>
                            <td>Mouse Inalámbrico</td>
                            <td>3</td>
                            <td>$90.00</td>
                            <td><span class="badge badge-blue">En proceso</span></td>
                        </tr>
                        <tr>
                            <td style="color:var(--color-text-muted)">#1039</td>
                            <td>Cable HDMI 3m</td>
                            <td>5</td>
                            <td>$45.00</td>
                            <td><span class="badge badge-green">Completada</span></td>
                        </tr>
                        <tr>
                            <td style="color:var(--color-text-muted)">#1038</td>
                            <td>USB Hub 7 puertos</td>
                            <td>1</td>
                            <td>$35.00</td>
                            <td><span class="badge badge-yellow">Pendiente</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stock Crítico --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Stock Crítico</div>
                    <div class="card-subtitle">Productos con stock bajo o agotado</div>
                </div>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">Ver todo</a>
            </div>
            <div style="display:flex; flex-direction:column; gap:10px">
                @php
                $stockCritico = [
                    ['nombre'=>'Cable HDMI 3m','stock'=>2,'min'=>10,'color'=>'badge-red','label'=>'Agotándose'],
                    ['nombre'=>'Adaptador USB-C','stock'=>0,'min'=>5,'color'=>'badge-red','label'=>'Sin stock'],
                    ['nombre'=>'Mouse Inalámbrico','stock'=>4,'min'=>15,'color'=>'badge-yellow','label'=>'Bajo'],
                    ['nombre'=>'Audífonos Bluetooth','stock'=>3,'min'=>10,'color'=>'badge-yellow','label'=>'Bajo'],
                    ['nombre'=>'Soporte para Monitor','stock'=>1,'min'=>5,'color'=>'badge-red','label'=>'Crítico'],
                    ['nombre'=>'Pad para Mouse XL','stock'=>5,'min'=>20,'color'=>'badge-yellow','label'=>'Bajo'],
                    ['nombre'=>'Hub USB 4P','stock'=>0,'min'=>8,'color'=>'badge-red','label'=>'Sin stock'],
                ];
                @endphp
                @foreach($stockCritico as $item)
                <div style="display:flex; align-items:center; gap:10px">
                    <div style="flex:1; min-width:0">
                        <div style="font-size:13px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">{{ $item['nombre'] }}</div>
                        <div style="font-size:11.5px; color:var(--color-text-muted)">Stock: {{ $item['stock'] }} / mín: {{ $item['min'] }}</div>
                        <div class="progress-bar-wrap" style="margin-top:4px">
                            <div class="progress-bar-fill" style="width:{{ min(($item['stock']/$item['min'])*100,100) }}%; background:{{ $item['color']==='badge-red'?'#dc2626':'#d97706' }}"></div>
                        </div>
                    </div>
                    <span class="badge {{ $item['color'] }}" style="flex-shrink:0">{{ $item['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</x-app-layout>
