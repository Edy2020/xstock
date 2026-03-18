<x-app-layout :pageTitle="'Proveedores'">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:16px">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div class="page-header-left">
            <h1>Proveedores</h1>
            <p>{{ $proveedores->count() }} proveedor(es) registrado(s)</p>
        </div>
        @if(auth()->user()->hasPermission('proveedores.crear'))
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Proveedor
        </a>
        @endif
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('proveedores.index') }}">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap" class="filters-row">
            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="buscar" placeholder="Buscar proveedor..." value="{{ request('buscar') }}">
            </div>
            <select name="estado" class="form-select" style="width:auto; padding:7px 11px" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @if(request()->hasAny(['buscar','estado']))
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
            @endif
        </div>
    </form>

    @if($proveedores->isEmpty())
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <h3>No hay proveedores</h3>
                <p>{{ request()->hasAny(['buscar','estado']) ? 'Ningún proveedor coincide con los filtros.' : 'Añade tu primer proveedor para comenzar.' }}</p>
                @if(!request()->hasAny(['buscar','estado']) && auth()->user()->hasPermission('proveedores.crear'))
                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary" style="margin-top:8px">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Añadir Proveedor
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="col-hide-mobile">RUC / NIF</th>
                        <th class="col-hide-mobile">Contacto</th>
                        <th>Email</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proveedores as $prov)
                    <tr>
                        <td style="font-weight:500">{{ $prov->nombre }}</td>
                        <td class="col-hide-mobile" style="color:var(--color-text-muted)">{{ $prov->ruc ?? '—' }}</td>
                        <td class="col-hide-mobile" style="color:var(--color-text-muted)">{{ $prov->contacto ?? '—' }}</td>
                        <td style="color:var(--color-text-muted)">{{ $prov->email ?? '—' }}</td>
                        <td>
                            <span class="badge badge-blue">{{ $prov->productos_count }}</span>
                        </td>
                        <td><span class="badge {{ $prov->badge_estado }}">{{ $prov->label_estado }}</span></td>
                        <td>
                            <div style="display:flex; gap:6px">
                                @if(auth()->user()->hasPermission('proveedores.editar'))
                                <a href="{{ route('proveedores.edit', $prov) }}" class="btn btn-secondary btn-sm">Editar</a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('proveedores.eliminar'))
                                <form method="POST" action="{{ route('proveedores.destroy', $prov) }}"
                                      onsubmit="return confirm('¿Eliminar el proveedor «{{ $prov->nombre }}»? Sus productos quedarán sin proveedor asignado.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                                @endif
                                
                                @if(!auth()->user()->hasPermission('proveedores.editar') && !auth()->user()->hasPermission('proveedores.eliminar'))
                                <span style="color:var(--color-text-muted); font-size:12px; font-style:italic">Sin permisos</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
