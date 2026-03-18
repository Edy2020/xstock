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

    {{-- Filtros --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar por usuario o acción...">
        </div>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todos los módulos</option>
            <option>Productos</option>
            <option>Ventas</option>
            <option>Proveedores</option>
            <option>Usuarios</option>
            <option>Roles</option>
        </select>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todas las acciones</option>
            <option>Creación</option>
            <option>Actualización</option>
            <option>Eliminación</option>
            <option>Login</option>
            <option>Logout</option>
        </select>
        <input type="date" class="form-input" style="width:auto; padding:7px 11px">
    </div>

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
                @php
                $logs = [
                    ['id'=>1,'user'=>'Admin','accion'=>'Creación','modulo'=>'Productos','detalle'=>'Creó producto "Monitor LG 24"','ip'=>'192.168.1.10','fecha'=>'18/03/2026 13:22:05','clase'=>'badge-blue'],
                    ['id'=>2,'user'=>'Admin','accion'=>'Venta','modulo'=>'Ventas','detalle'=>'Registró venta #1042 por $320.00','ip'=>'192.168.1.10','fecha'=>'18/03/2026 13:10:14','clase'=>'badge-green'],
                    ['id'=>3,'user'=>'Carlos R.','accion'=>'Actualización','modulo'=>'Productos','detalle'=>'Actualizó stock de "Teclado Mecánico"','ip'=>'192.168.1.22','fecha'=>'18/03/2026 11:45:30','clase'=>'badge-yellow'],
                    ['id'=>4,'user'=>'Admin','accion'=>'Login','modulo'=>'Sistema','detalle'=>'Inicio de sesión exitoso','ip'=>'192.168.1.10','fecha'=>'18/03/2026 09:00:01','clase'=>'badge-gray'],
                    ['id'=>5,'user'=>'Carlos R.','accion'=>'Login','modulo'=>'Sistema','detalle'=>'Inicio de sesión exitoso','ip'=>'192.168.1.22','fecha'=>'18/03/2026 09:05:12','clase'=>'badge-gray'],
                    ['id'=>6,'user'=>'Admin','accion'=>'Eliminación','modulo'=>'Proveedores','detalle'=>'Eliminó proveedor "CableFast S.A."','ip'=>'192.168.1.10','fecha'=>'17/03/2026 17:30:44','clase'=>'badge-red'],
                    ['id'=>7,'user'=>'Admin','accion'=>'Creación','modulo'=>'Usuarios','detalle'=>'Creó usuario "Carlos Rodríguez"','ip'=>'192.168.1.10','fecha'=>'17/03/2026 10:15:22','clase'=>'badge-blue'],
                    ['id'=>8,'user'=>'Admin','accion'=>'Actualización','modulo'=>'Roles','detalle'=>'Modificó permisos del rol "Vendedor"','ip'=>'192.168.1.10','fecha'=>'17/03/2026 09:40:00','clase'=>'badge-yellow'],
                ];
                @endphp
                @foreach($logs as $l)
                <tr>
                    <td style="color:var(--color-text-muted); font-family:monospace; font-size:11px">#{{ $l['id'] }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:7px">
                            <div style="width:24px; height:24px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0">
                                {{ strtoupper(substr($l['user'],0,1)) }}
                            </div>
                            <span style="font-weight:500">{{ $l['user'] }}</span>
                        </div>
                    </td>
                    <td><span class="badge {{ $l['clase'] }}">{{ $l['accion'] }}</span></td>
                    <td>{{ $l['modulo'] }}</td>
                    <td style="color:var(--color-text-muted); font-size:12.5px">{{ $l['detalle'] }}</td>
                    <td style="font-family:monospace; font-size:11px; color:var(--color-text-muted)">{{ $l['ip'] }}</td>
                    <td style="color:var(--color-text-muted); font-size:12px; white-space:nowrap">{{ $l['fecha'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">Mostrando 8 de 3,842 registros</span>
        <button class="pg-btn">‹</button>
        <button class="pg-btn active">1</button>
        <button class="pg-btn">2</button>
        <button class="pg-btn">3</button>
        <button class="pg-btn">...</button>
        <button class="pg-btn">384</button>
        <button class="pg-btn">›</button>
    </div>

</x-app-layout>
