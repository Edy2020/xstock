<x-app-layout :pageTitle="'Proveedores'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Proveedores</h1>
            <p>Gestión de proveedores del sistema</p>
        </div>
        <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Proveedor
        </a>
    </div>

    {{-- Búsqueda --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar proveedor...">
        </div>
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
                    <th>Nombre</th>
                    <th>RUC / NIF</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                $proveedores = [
                    ['nombre'=>'TechParts S.A.','ruc'=>'20501234567','contacto'=>'Carlos Gómez','tel'=>'+51 998 112 233','email'=>'cgomez@techparts.com','prods'=>12,'estado'=>'Activo','clase'=>'badge-green'],
                    ['nombre'=>'Electronica Global','ruc'=>'20509876543','contacto'=>'Ana Ruiz','tel'=>'+51 987 654 321','email'=>'aruiz@eglobal.net','prods'=>8,'estado'=>'Activo','clase'=>'badge-green'],
                    ['nombre'=>'CableMaster Perú','ruc'=>'20512345678','contacto'=>'Luis Torres','tel'=>'+51 944 222 333','email'=>'ltorres@cablemaster.pe','prods'=>5,'estado'=>'Activo','clase'=>'badge-green'],
                    ['nombre'=>'ImportTech EIRL','ruc'=>'20567890123','contacto'=>'María Soto','tel'=>'+51 974 111 555','email'=>'msoto@importtech.pe','prods'=>3,'estado'=>'Inactivo','clase'=>'badge-gray'],
                ];
                @endphp
                @foreach($proveedores as $p)
                <tr>
                    <td style="font-weight:600">{{ $p['nombre'] }}</td>
                    <td style="font-family:monospace; font-size:12px">{{ $p['ruc'] }}</td>
                    <td>{{ $p['contacto'] }}</td>
                    <td style="color:var(--color-text-muted)">{{ $p['tel'] }}</td>
                    <td style="color:var(--color-text-muted)">{{ $p['email'] }}</td>
                    <td>{{ $p['prods'] }}</td>
                    <td><span class="badge {{ $p['clase'] }}">{{ $p['estado'] }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px">
                            <a href="{{ route('proveedores.edit', 1) }}" class="btn btn-secondary btn-sm">Editar</a>
                            <form method="POST" action="{{ route('proveedores.destroy', 1) }}" onsubmit="return confirm('¿Eliminar proveedor?')">
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

    <div class="pagination">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">Mostrando 4 de 18 proveedores</span>
        <button class="pg-btn">‹</button>
        <button class="pg-btn active">1</button>
        <button class="pg-btn">2</button>
        <button class="pg-btn">›</button>
    </div>

</x-app-layout>
