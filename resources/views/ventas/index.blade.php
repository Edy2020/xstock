<x-app-layout :pageTitle="'Ventas'">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:16px">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <ul style="margin:0; padding-left:16px">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <div class="page-header-left">
            <h1>Ventas</h1>
            <p>Registro y control de las ventas realizadas</p>
        </div>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva Venta
        </a>
    </div>

    {{-- Resumen Rápido (Opcional, estadísticas básicas) --}}
    <div class="grid-4" style="margin-bottom:20px; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))">
        <div class="card" style="padding:16px; display:flex; gap:14px; align-items:center">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(37,99,235,0.1); color:var(--color-primary); display:flex; align-items:center; justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div>
                <div style="font-size:12.5px; color:var(--color-text-muted); font-weight:500; margin-bottom:2px">Ventas Registradas</div>
                <div style="font-size:22px; font-weight:700">{{ $totalVentas ?? 0 }}</div>
            </div>
        </div>
        <div class="card" style="padding:16px; display:flex; gap:14px; align-items:center">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(16,185,129,0.1); color:#10b981; display:flex; align-items:center; justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
                <div style="font-size:12.5px; color:var(--color-text-muted); font-weight:500; margin-bottom:2px">Ingresos Hoy</div>
                <div style="font-size:22px; font-weight:700">${{ number_format($ingresosHoy ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Filtro Client-side --}}
    @if($ventas->isNotEmpty())
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap" class="filters-row">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="filter-search" placeholder="Buscar por ID o método de pago...">
        </div>
        <button id="btn-limpiar" class="btn btn-secondary btn-sm" style="display:none">Limpiar</button>
    </div>
    @endif

    @if($ventas->isEmpty())
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                <h3>No hay ventas registradas</h3>
                <p>Añade tu primera venta para visualizarla en el historial.</p>
                <a href="{{ route('ventas.create') }}" class="btn btn-primary" style="margin-top:8px">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Nueva Venta
                </a>
            </div>
        </div>
    @else
        <div class="table-wrapper" id="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:100px">ID Venta</th>
                        <th>Fecha y Hora</th>
                        <th>Método Pago</th>
                        <th>Items</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ventas-tbody">
                    @foreach($ventas as $venta)
                    <tr class="venta-row" data-id="{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}" data-pago="{{ strtolower($venta->metodo_pago) }}">
                        <td style="font-weight:600; font-family:monospace; color:var(--color-primary)">
                            #{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>
                            {{ $venta->created_at->format('d/m/Y') }} 
                            <span style="color:var(--color-text-muted); font-size:12.5px">{{ $venta->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <span class="badge badge-gray" style="text-transform:capitalize">{{ $venta->metodo_pago }}</span>
                        </td>
                        <td>{{ $venta->detalles->sum('cantidad') }} productos</td>
                        <td style="color:var(--color-danger)">
                            {{ $venta->descuento_total > 0 ? '- $'.number_format($venta->descuento_total, 0, ',', '.') : '—' }}
                        </td>
                        <td style="font-weight:700">
                            ${{ number_format($venta->total, 0, ',', '.') }}
                        </td>
                        <td>
                            <div style="display:flex; gap:6px">
                                <!-- Podrímos añadir una vista Show próximamente -->
                                <form method="POST" action="{{ route('ventas.destroy', $venta->id) }}"
                                      onsubmit="return confirm('¿Anular esta venta permanentemente y reponer el stock?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Anular / Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card" id="no-results-state" style="display:none">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <h3>No se encontraron resultados</h3>
                <p>Ninguna venta coincide con el término de búsqueda.</p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top:8px" onclick="document.getElementById('btn-limpiar').click()">Limpiar filtros</button>
            </div>
        </div>
    @endif

    {{-- Script de filtrado en cliente --}}
    @if($ventas->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputSearch = document.getElementById('filter-search');
            const btnLimpiar = document.getElementById('btn-limpiar');
            const rowElements = document.querySelectorAll('.venta-row');
            const tableContainer = document.getElementById('table-container');
            const noResultsState = document.getElementById('no-results-state');

            function filterTable() {
                const term = inputSearch.value.toLowerCase().trim();
                let hasVisibleRows = false;
                const isFiltered = term !== '';

                btnLimpiar.style.display = isFiltered ? 'inline-flex' : 'none';

                rowElements.forEach(row => {
                    const rowId = row.dataset.id;
                    const rowPago = row.dataset.pago;

                    if (term === '' || rowId.includes(term) || rowPago.includes(term)) {
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

            inputSearch.addEventListener('input', filterTable);

            btnLimpiar.addEventListener('click', (e) => {
                e.preventDefault();
                inputSearch.value = '';
                filterTable();
            });
        });
    </script>
    @endif

</x-app-layout>
