<x-app-layout :pageTitle="'Proveedores'">

    @if(session('success'))
        <div id="success-alert" class="alert alert-success" style="margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; transition:opacity 0.3s ease">
            <div style="display:flex; align-items:center; gap:10px">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').style.opacity='0'; setTimeout(()=>document.getElementById('success-alert').remove(), 300)" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; display:flex; align-items:center; opacity:0.7">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const alertEl = document.getElementById('success-alert');
                if (alertEl) {
                    alertEl.style.opacity = '0';
                    setTimeout(() => alertEl.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    <div class="page-header">
        <div class="page-header-left">
            <h1>Proveedores</h1>
            <p>{{ $proveedores->count() }} proveedor(es) registrado(s)</p>
        </div>
        @if(auth()->user()->hasPermission('proveedores.crear'))
        <div style="display:flex; gap:8px">
            <form action="{{ route('proveedores.import') }}" method="POST" enctype="multipart/form-data" id="form-import" style="display:none">
                @csrf
                <input type="file" name="archivo_csv" id="archivo_csv" accept=".csv" onchange="document.getElementById('form-import').submit()">
            </form>
            <button class="btn btn-secondary" onclick="document.getElementById('archivo_csv').click()" title="Formato esperado: Nombre, RUC/NIF, Contacto, Email, Teléfono, Dirección, Notas, Estado">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Importar CSV
            </button>
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo Proveedor
            </a>
        </div>
        @endif
    </div>

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
                    <tr style="cursor:pointer" onclick="if(!event.target.closest('.btn') && !event.target.closest('form')) window.location='{{ route('proveedores.show', $prov) }}'">
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
