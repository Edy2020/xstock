<x-app-layout :pageTitle="'Productos'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Productos</h1>
            <p>Gestión del catálogo de productos e inventario</p>
        </div>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Producto
        </a>
    </div>

    {{-- Barra de búsqueda y filtros --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap" class="filters-row">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar producto...">
        </div>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todas las categorías</option>
            <option>Electrónica</option>
            <option>Accesorios</option>
            <option>Cables</option>
        </select>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todos los estados</option>
            <option>En stock</option>
            <option>Stock bajo</option>
            <option>Sin stock</option>
        </select>
    </div>

    {{-- Tabla de productos --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                $productos = [
                    ['sku'=>'PRD-001','nombre'=>'Monitor LG 24"','cat'=>'Electrónica','precio'=>'$320.00','stock'=>15,'estado'=>'En stock','clase'=>'badge-green'],
                    ['sku'=>'PRD-002','nombre'=>'Teclado Mecánico','cat'=>'Accesorios','precio'=>'$90.00','stock'=>8,'estado'=>'En stock','clase'=>'badge-green'],
                    ['sku'=>'PRD-003','nombre'=>'Mouse Inalámbrico','cat'=>'Accesorios','precio'=>'$30.00','stock'=>4,'estado'=>'Stock bajo','clase'=>'badge-yellow'],
                    ['sku'=>'PRD-004','nombre'=>'Cable HDMI 3m','cat'=>'Cables','precio'=>'$9.00','stock'=>2,'estado'=>'Stock bajo','clase'=>'badge-yellow'],
                    ['sku'=>'PRD-005','nombre'=>'Adaptador USB-C','cat'=>'Cables','precio'=>'$15.00','stock'=>0,'estado'=>'Sin stock','clase'=>'badge-red'],
                    ['sku'=>'PRD-006','nombre'=>'Hub USB 7P','cat'=>'Accesorios','precio'=>'$35.00','stock'=>22,'estado'=>'En stock','clase'=>'badge-green'],
                    ['sku'=>'PRD-007','nombre'=>'Audífonos BT','cat'=>'Electrónica','precio'=>'$75.00','stock'=>3,'estado'=>'Stock bajo','clase'=>'badge-yellow'],
                ];
                @endphp
                @foreach($productos as $p)
                <tr>
                    <td style="color:var(--color-text-muted); font-family:monospace; font-size:12px">{{ $p['sku'] }}</td>
                    <td style="font-weight:500">{{ $p['nombre'] }}</td>
                    <td>{{ $p['cat'] }}</td>
                    <td>{{ $p['precio'] }}</td>
                    <td>{{ $p['stock'] }}</td>
                    <td><span class="badge {{ $p['clase'] }}">{{ $p['estado'] }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px">
                            <a href="{{ route('productos.show', 1) }}" class="btn btn-secondary btn-sm">Ver</a>
                            <a href="{{ route('productos.edit', 1) }}" class="btn btn-secondary btn-sm">Editar</a>
                            <form method="POST" action="{{ route('productos.destroy', 1) }}" onsubmit="return confirm('¿Eliminar este producto?')">
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

    {{-- Paginación --}}
    <div class="pagination">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">Mostrando 7 de 248 productos</span>
        <button class="pg-btn">‹</button>
        <button class="pg-btn active">1</button>
        <button class="pg-btn">2</button>
        <button class="pg-btn">3</button>
        <button class="pg-btn">›</button>
    </div>

</x-app-layout>
