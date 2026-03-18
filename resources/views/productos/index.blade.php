<x-app-layout :pageTitle="'Productos'">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:16px">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div class="page-header-left">
            <h1>Productos</h1>
            <p>{{ $productos->count() }} producto(s) registrado(s)</p>
        </div>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Producto
        </a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('productos.index') }}">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap" class="filters-row">
            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}">
            </div>
            <select name="categoria" class="form-select" style="width:auto; padding:7px 11px" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ request('categoria') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="proveedor_id" class="form-select" style="width:auto; padding:7px 11px" onchange="this.form.submit()">
                <option value="">Todos los proveedores</option>
                @foreach($proveedores as $prov)
                    <option value="{{ $prov->id }}" {{ request('proveedor_id') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
                @endforeach
            </select>
            <select name="estado" class="form-select" style="width:auto; padding:7px 11px" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @if(request()->hasAny(['buscar','categoria','estado','proveedor_id']))
                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
            @endif
        </div>
    </form>

    @if($productos->isEmpty())
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                <h3>No hay productos</h3>
                <p>{{ request()->hasAny(['buscar','categoria','estado','proveedor_id']) ? 'Ningún producto coincide con los filtros.' : 'Añade tu primer producto para comenzar.' }}</p>
                @if(!request()->hasAny(['buscar','categoria','estado','proveedor_id']))
                    <a href="{{ route('productos.create') }}" class="btn btn-primary" style="margin-top:8px">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Añadir Producto
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
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td style="font-weight:500">{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria ?? '—' }}</td>
                        <td style="color:var(--color-text-muted)">{{ $producto->proveedor?->nombre ?? '—' }}</td>
                        <td>$ {{ number_format($producto->precio, 0, ',', '.') }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td><span class="badge {{ $producto->badge_estado }}">{{ $producto->label_estado }}</span></td>
                        <td>
                            <div style="display:flex; gap:6px">
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-secondary btn-sm">Editar</a>
                                <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                      onsubmit="return confirm('¿Eliminar el producto «{{ $producto->nombre }}»?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
