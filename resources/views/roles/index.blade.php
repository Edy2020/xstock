<x-app-layout :pageTitle="'Roles y Permisos'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Roles y Permisos</h1>
            <p>Define qué puede hacer cada rol en el sistema</p>
        </div>
        <button class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Rol
        </button>
    </div>

    {{-- Tarjetas de roles --}}
    <div class="grid-3" style="margin-bottom:28px">
        @php
        $roles = [
            ['nombre'=>'Administrador','desc'=>'Acceso total al sistema. Puede gestionar usuarios, roles y configuración.','usuarios'=>1,'color'=>'#2563eb','bg'=>'#eff6ff','permisos'=>12],
            ['nombre'=>'Vendedor','desc'=>'Puede registrar ventas, ver productos y consultar clientes.','usuarios'=>2,'color'=>'#16a34a','bg'=>'#f0fdf4','permisos'=>5],
            ['nombre'=>'Almacén','desc'=>'Gestiona el inventario: añadir, editar y ver productos y proveedores.','usuarios'=>1,'color'=>'#d97706','bg'=>'#fffbeb','permisos'=>7],
        ];
        @endphp
        @foreach($roles as $r)
        <div class="card" style="display:flex; flex-direction:column; gap:10px">
            <div style="display:flex; align-items:center; justify-content:space-between">
                <div style="display:flex; align-items:center; gap:10px">
                    <div style="width:34px; height:34px; border-radius:8px; background:{{ $r['bg'] }}; display:flex; align-items:center; justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $r['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700">{{ $r['nombre'] }}</span>
                </div>
                <button class="btn btn-secondary btn-sm">Editar</button>
            </div>
            <p style="font-size:13px; color:var(--color-text-muted); margin:0; line-height:1.5">{{ $r['desc'] }}</p>
            <div style="display:flex; gap:12px; font-size:12px; color:var(--color-text-muted); border-top:1px solid var(--color-border); padding-top:10px">
                <span>👥 {{ $r['usuarios'] }} usuario(s)</span>
                <span>🔑 {{ $r['permisos'] }} permiso(s)</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabla de permisos por módulo --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Permisos por Módulo</div>
            <div class="card-subtitle">Marca los permisos que tiene cada rol</div>
        </div>
        <div class="table-wrapper" style="border:none; margin:0 -20px">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Módulo / Acción</th>
                        <th style="text-align:center">Administrador</th>
                        <th style="text-align:center">Vendedor</th>
                        <th style="text-align:center">Almacén</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $permisos = [
                        ['mod'=>'Productos — Ver',     'admin'=>true,  'vend'=>true,  'alm'=>true ],
                        ['mod'=>'Productos — Crear',   'admin'=>true,  'vend'=>false, 'alm'=>true ],
                        ['mod'=>'Productos — Editar',  'admin'=>true,  'vend'=>false, 'alm'=>true ],
                        ['mod'=>'Productos — Eliminar','admin'=>true,  'vend'=>false, 'alm'=>false],
                        ['mod'=>'Ventas — Ver',        'admin'=>true,  'vend'=>true,  'alm'=>false],
                        ['mod'=>'Ventas — Crear',      'admin'=>true,  'vend'=>true,  'alm'=>false],
                        ['mod'=>'Ventas — Anular',     'admin'=>true,  'vend'=>false, 'alm'=>false],
                        ['mod'=>'Proveedores — Ver',   'admin'=>true,  'vend'=>false, 'alm'=>true ],
                        ['mod'=>'Proveedores — Crear', 'admin'=>true,  'vend'=>false, 'alm'=>true ],
                        ['mod'=>'Estadísticas — Ver',  'admin'=>true,  'vend'=>true,  'alm'=>false],
                        ['mod'=>'Historial — Ver',     'admin'=>true,  'vend'=>false, 'alm'=>false],
                        ['mod'=>'Usuarios — Gestionar','admin'=>true,  'vend'=>false, 'alm'=>false],
                        ['mod'=>'Roles — Gestionar',   'admin'=>true,  'vend'=>false, 'alm'=>false],
                    ];
                    @endphp
                    @foreach($permisos as $p)
                    <tr>
                        <td style="font-size:13px">{{ $p['mod'] }}</td>
                        <td style="text-align:center">
                            @if($p['admin'])
                                <span style="color:#16a34a; font-size:16px">✓</span>
                            @else
                                <span style="color:#d1d5db; font-size:16px">✕</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            @if($p['vend'])
                                <span style="color:#16a34a; font-size:16px">✓</span>
                            @else
                                <span style="color:#d1d5db; font-size:16px">✕</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            @if($p['alm'])
                                <span style="color:#16a34a; font-size:16px">✓</span>
                            @else
                                <span style="color:#d1d5db; font-size:16px">✕</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 0 0; display:flex; justify-content:flex-end">
            <button class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Guardar permisos
            </button>
        </div>
    </div>

</x-app-layout>
