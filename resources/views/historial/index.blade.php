<x-app-layout :pageTitle="'Historial de Actividad'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Historial de Actividad</h1>
            <p>Registro de todas las acciones realizadas en el sistema</p>
        </div>
        <button class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exportar CSV
        </button>
    </div>

    {{-- Filtros Dinámicos --}}
    <form method="GET" action="{{ route('historial.index') }}" id="form-filters" style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por usuario, IP o detalle..." onblur="document.getElementById('form-filters').submit()" onkeydown="if(event.key === 'Enter') document.getElementById('form-filters').submit()">
        </div>
        <select name="modulo" class="form-select" style="width:auto; padding:7px 11px" onchange="document.getElementById('form-filters').submit()">
            <option>Todos los módulos</option>
            <option {{ request('modulo')=='Productos'?'selected':'' }}>Productos</option>
            <option {{ request('modulo')=='Ventas'?'selected':'' }}>Ventas</option>
            <option {{ request('modulo')=='Proveedores'?'selected':'' }}>Proveedores</option>
            <option {{ request('modulo')=='Sistema'?'selected':'' }}>Sistema</option>
        </select>
        <select name="accion" class="form-select" style="width:auto; padding:7px 11px" onchange="document.getElementById('form-filters').submit()">
            <option>Todas las acciones</option>
            <option {{ request('accion')=='Creación'?'selected':'' }}>Creación</option>
            <option {{ request('accion')=='Actualización'?'selected':'' }}>Actualización</option>
            <option {{ request('accion')=='Eliminación'?'selected':'' }}>Eliminación</option>
            <option {{ request('accion')=='Venta'?'selected':'' }}>Venta</option>
            <option {{ request('accion')=='Login'?'selected':'' }}>Login</option>
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="form-input" style="width:auto; padding:7px 11px" onchange="document.getElementById('form-filters').submit()">
        
        @if(request()->anyFilled(['search', 'modulo', 'accion', 'date']))
            <a href="{{ route('historial.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
        @endif
    </form>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>Detalle</th>
                    <th>IP</th>
                    <th>Fecha / Hora</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $l)
                <tr class="hoverable" style="cursor:pointer" onclick="window.location='{{ route('historial.show', $l) }}'">
                    <td style="color:var(--color-text-muted); font-family:monospace; font-size:11px">#{{ str_pad($l->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:7px">
                            <div style="width:24px; height:24px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0">
                                {{ $l->user ? strtoupper(substr($l->user->name, 0, 1)) : 'S' }}
                            </div>
                            <span style="font-weight:500">{{ $l->user ? $l->user->name : 'Sistema/Invitado' }}</span>
                        </div>
                    </td>
                    <td><span class="badge {{ $l->badgeClass }}">{{ $l->accion }}</span></td>
                    <td>{{ $l->modulo }}</td>
                    <td style="color:var(--color-text-muted); font-size:12.5px">{{ $l->detalle }}</td>
                    <td style="font-family:monospace; font-size:11px; color:var(--color-text-muted)">{{ $l->ip_address ?? '—' }}</td>
                    <td style="color:var(--color-text-muted); font-size:12px; white-space:nowrap">{{ $l->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--color-text-muted)">
                        No hay ninguna actividad registrada aún o ninguna coincide con los filtros.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($logs) && $logs->hasPages())
    <div class="pagination" style="margin-top: 20px">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">
            Mostrando {{ $logs->firstItem() }} - {{ $logs->lastItem() }} de {{ $logs->total() }} registros
        </span>
        
        @if($logs->onFirstPage())
            <button class="pg-btn" disabled>‹</button>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="pg-btn">‹</a>
        @endif
        
        <button class="pg-btn active">{{ $logs->currentPage() }}</button>

        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="pg-btn">›</a>
        @else
            <button class="pg-btn" disabled>›</button>
        @endif
    </div>
    @endif

</x-app-layout>
