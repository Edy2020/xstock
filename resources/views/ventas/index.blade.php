<x-app-layout :pageTitle="'Ventas'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Ventas</h1>
            <p>Historial y gestión de ventas registradas</p>
        </div>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nueva Venta
        </a>
    </div>

    {{-- Resumen rápido --}}
    <div class="grid-3" style="margin-bottom:20px">
        <div class="stat-card">
            <div class="stat-label">Ventas hoy</div>
            <div class="stat-value">34</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total hoy</div>
            <div class="stat-value">$1,820</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ticket promedio</div>
            <div class="stat-value">$53.5</div>
        </div>
    </div>

    {{-- Filtros --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar por # o producto...">
        </div>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todos los estados</option>
            <option>Completada</option>
            <option>En proceso</option>
            <option>Pendiente</option>
            <option>Anulada</option>
        </select>
        <input type="date" class="form-input" style="width:auto; padding:7px 11px">
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#Venta</th>
                    <th>Producto(s)</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                $ventas = [
                    ['id'=>'#1042','prod'=>'Monitor LG 24"','cant'=>1,'sub'=>'$320.00','total'=>'$320.00','fecha'=>'18/03/2026','estado'=>'Completada','clase'=>'badge-green'],
                    ['id'=>'#1041','prod'=>'Teclado Mecánico','cant'=>2,'sub'=>'$180.00','total'=>'$180.00','fecha'=>'18/03/2026','estado'=>'Completada','clase'=>'badge-green'],
                    ['id'=>'#1040','prod'=>'Mouse Inalámbrico','cant'=>3,'sub'=>'$90.00','total'=>'$90.00','fecha'=>'18/03/2026','estado'=>'En proceso','clase'=>'badge-blue'],
                    ['id'=>'#1039','prod'=>'Cable HDMI 3m','cant'=>5,'sub'=>'$45.00','total'=>'$45.00','fecha'=>'18/03/2026','estado'=>'Completada','clase'=>'badge-green'],
                    ['id'=>'#1038','prod'=>'Hub USB 7P','cant'=>1,'sub'=>'$35.00','total'=>'$35.00','fecha'=>'17/03/2026','estado'=>'Pendiente','clase'=>'badge-yellow'],
                    ['id'=>'#1037','prod'=>'Audífonos BT','cant'=>2,'sub'=>'$150.00','total'=>'$150.00','fecha'=>'17/03/2026','estado'=>'Anulada','clase'=>'badge-gray'],
                ];
                @endphp
                @foreach($ventas as $v)
                <tr>
                    <td style="font-family:monospace; font-size:12px; color:var(--color-text-muted)">{{ $v['id'] }}</td>
                    <td style="font-weight:500">{{ $v['prod'] }}</td>
                    <td>{{ $v['cant'] }}</td>
                    <td>{{ $v['sub'] }}</td>
                    <td style="font-weight:600">{{ $v['total'] }}</td>
                    <td style="color:var(--color-text-muted)">{{ $v['fecha'] }}</td>
                    <td><span class="badge {{ $v['clase'] }}">{{ $v['estado'] }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px">
                            <a href="#" class="btn btn-secondary btn-sm">Ver</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">Mostrando 6 de 1,042 ventas</span>
        <button class="pg-btn">‹</button>
        <button class="pg-btn active">1</button>
        <button class="pg-btn">2</button>
        <button class="pg-btn">3</button>
        <button class="pg-btn">›</button>
    </div>

</x-app-layout>
