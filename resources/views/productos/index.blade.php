<x-app-layout :pageTitle="'Productos'">

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
            <h1>Productos</h1>
            <p>Gestiona el inventario, precios y stock</p>
        </div>
        @if(auth()->user()->hasPermission('productos.crear'))
        <div style="display:flex; gap:8px">
            <form action="{{ route('productos.import') }}" method="POST" enctype="multipart/form-data" id="form-import" style="display:none">
                @csrf
                <input type="file" name="archivo_csv" id="archivo_csv" accept=".csv" onchange="document.getElementById('form-import').submit()">
            </form>
            <button class="btn btn-secondary" onclick="document.getElementById('archivo_csv').click()" title="Formato esperado: Nombre, Descripción, Categoría, Proveedor, Precio, Stock, Estado">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Importar CSV
            </button>
            <a href="{{ route('productos.export.excel') }}" class="btn btn-secondary" title="Descargar catálogo completo en formato Excel">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
                Exportar Excel
            </a>
            <a href="{{ route('productos.export.pdf') }}" class="btn btn-secondary" target="_blank" title="Abrir catálogo completo en formato PDF">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="9.01" y2="15"/></svg>
                Exportar PDF
            </a>
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo Producto
            </a>
        </div>
        @endif
    </div>

    {{-- Filtros (Client-side JS) --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap" class="filters-row">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="filter-buscar" placeholder="Buscar producto...">
        </div>
        
        <select id="filter-categoria" class="form-select" style="width:auto; padding:7px 11px">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
            @endforeach
        </select>
        
        <select id="filter-proveedor" class="form-select" style="width:auto; padding:7px 11px">
            <option value="">Todos los proveedores</option>
            @foreach($proveedores as $prov)
                <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
            @endforeach
        </select>
        
        <select id="filter-estado" class="form-select" style="width:auto; padding:7px 11px">
            <option value="">Todos los estados</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
        
        <button id="btn-limpiar" class="btn btn-secondary btn-sm" style="display:none">Limpiar</button>
    </div>

    {{-- Vista cuando no hay ningún producto en la tabla (Base de datos vacía) --}}
    @if($productos->isEmpty())
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                <h3>No hay productos</h3>
                <p>Añade tu primer producto para comenzar.</p>
                @if(auth()->user()->hasPermission('productos.crear'))
                <a href="{{ route('productos.create') }}" class="btn btn-primary" style="margin-top:8px">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Añadir Producto
                </a>
                @endif
            </div>
        </div>
    @else
        <div class="table-wrapper" id="table-container">
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
                <tbody id="productos-tbody">
                    @foreach($productos as $producto)
                    <tr class="producto-row" style="cursor:pointer"
                        onclick="if(!event.target.closest('.btn') && !event.target.closest('form')) window.location='{{ route('productos.show', $producto) }}'"
                        data-nombre="{{ strtolower($producto->nombre) }}"
                        data-categoria="{{ strtolower($producto->categoria ?? '') }}"
                        data-proveedor="{{ $producto->proveedor_id ?? '' }}"
                        data-estado="{{ $producto->estado }}">
                        <td style="font-weight:500">{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria ?? '—' }}</td>
                        <td style="color:var(--color-text-muted)">{{ $producto->proveedor?->nombre ?? '—' }}</td>
                        <td>$ {{ number_format($producto->precio, 0, ',', '.') }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td><span class="badge {{ $producto->badge_estado }}">{{ $producto->label_estado }}</span></td>
                        <td>
                            <div style="display:flex; gap:6px">
                                @if(auth()->user()->hasPermission('productos.editar'))
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-secondary btn-sm">Editar</a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('productos.eliminar'))
                                <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                      onsubmit="return confirm('¿Eliminar el producto «{{ $producto->nombre }}»?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                                @endif
                                
                                @if(!auth()->user()->hasPermission('productos.editar') && !auth()->user()->hasPermission('productos.eliminar'))
                                <span style="color:var(--color-text-muted); font-size:12px; font-style:italic">Sin permisos</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Vista cuando los filtros restan todos los resultados --}}
        <div class="card" id="no-results-state" style="display:none">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <h3>No se encontraron resultados</h3>
                <p>Ningún producto coincide con los filtros de búsqueda aplicados.</p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top:8px" onclick="document.getElementById('btn-limpiar').click()">Limpiar filtros</button>
            </div>
        </div>
    @endif

    {{-- Script de filtrado en cliente --}}
    @if($productos->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputBuscar = document.getElementById('filter-buscar');
            const selectCategoria = document.getElementById('filter-categoria');
            const selectProveedor = document.getElementById('filter-proveedor');
            const selectEstado = document.getElementById('filter-estado');
            const btnLimpiar = document.getElementById('btn-limpiar');
            
            const rowElements = document.querySelectorAll('.producto-row');
            const tableContainer = document.getElementById('table-container');
            const noResultsState = document.getElementById('no-results-state');

            function filterTable() {
                const term = inputBuscar.value.toLowerCase().trim();
                const cat = selectCategoria.value.toLowerCase();
                const prov = selectProveedor.value;
                const est = selectEstado.value;

                let hasVisibleRows = false;
                const isFiltered = term !== '' || cat !== '' || prov !== '' || est !== '';

                btnLimpiar.style.display = isFiltered ? 'inline-flex' : 'none';

                rowElements.forEach(row => {
                    const rowName = row.dataset.nombre;
                    const rowCat = row.dataset.categoria;
                    const rowProv = row.dataset.proveedor;
                    const rowEst = row.dataset.estado;

                    const matchesTerm = term === '' || rowName.includes(term);
                    const matchesCat = cat === '' || rowCat === cat;
                    const matchesProv = prov === '' || rowProv === prov;
                    const matchesEst = est === '' || rowEst === est;

                    if (matchesTerm && matchesCat && matchesProv && matchesEst) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (tableContainer && noResultsState) {
                    tableContainer.style.display = hasVisibleRows ? '' : 'none';
                    noResultsState.style.display = hasVisibleRows ? 'none' : 'block';
                }
            }

            inputBuscar.addEventListener('input', filterTable);
            selectCategoria.addEventListener('change', filterTable);
            selectProveedor.addEventListener('change', filterTable);
            selectEstado.addEventListener('change', filterTable);

            btnLimpiar.addEventListener('click', (e) => {
                e.preventDefault();
                inputBuscar.value = '';
                selectCategoria.value = '';
                selectProveedor.value = '';
                selectEstado.value = '';
                filterTable();
            });
        });
    </script>
    @endif

</x-app-layout>
