<x-app-layout :pageTitle="'Usuarios'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Usuarios</h1>
            <p>Gestión de usuarios del sistema</p>
        </div>
        <button class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Usuario
        </button>
    </div>

    {{-- Búsqueda --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar usuario...">
        </div>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todos los roles</option>
            <option>Administrador</option>
            <option>Vendedor</option>
            <option>Almacén</option>
        </select>
        <select class="form-select" style="width:auto; padding:7px 11px">
            <option>Todos los estados</option>
            <option>Activo</option>
            <option>Inactivo</option>
        </select>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Último acceso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                $usuarios = [
                    ['nombre'=>'Administrador','email'=>'admin@xstock.com','rol'=>'Administrador','ultimo'=>'18/03/2026 09:00','estado'=>'Activo','clase'=>'badge-green'],
                    ['nombre'=>'Carlos Rodríguez','email'=>'carlos@xstock.com','rol'=>'Vendedor','ultimo'=>'18/03/2026 09:05','estado'=>'Activo','clase'=>'badge-green'],
                    ['nombre'=>'María Fernández','email'=>'maria@xstock.com','rol'=>'Almacén','ultimo'=>'17/03/2026 16:30','estado'=>'Activo','clase'=>'badge-green'],
                    ['nombre'=>'Luis Campos','email'=>'luis@xstock.com','rol'=>'Vendedor','ultimo'=>'15/03/2026 10:00','estado'=>'Inactivo','clase'=>'badge-gray'],
                ];
                @endphp
                @foreach($usuarios as $u)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px">
                            <div style="width:32px; height:32px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0">
                                {{ strtoupper(substr($u['nombre'],0,1)) }}
                            </div>
                            <span style="font-weight:500">{{ $u['nombre'] }}</span>
                        </div>
                    </td>
                    <td style="color:var(--color-text-muted)">{{ $u['email'] }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $u['rol'] }}</span>
                    </td>
                    <td style="color:var(--color-text-muted); font-size:12px">{{ $u['ultimo'] }}</td>
                    <td><span class="badge {{ $u['clase'] }}">{{ $u['estado'] }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px">
                            <button class="btn btn-secondary btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">Mostrando 4 de 4 usuarios</span>
        <button class="pg-btn">‹</button>
        <button class="pg-btn active">1</button>
        <button class="pg-btn">›</button>
    </div>

</x-app-layout>
