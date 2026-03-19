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

    <div class="grid-3" style="margin-bottom:28px">
        @php
            $colors = [
                1 => ['color'=>'#2563eb', 'bg'=>'#eff6ff'], // Admin
                2 => ['color'=>'#16a34a', 'bg'=>'#f0fdf4'], // Vendedor
                3 => ['color'=>'#d97706', 'bg'=>'#fffbeb'], // Almacén
            ];
        @endphp
        @foreach($roles as $r)
        @php 
            $c = $colors[$r->id] ?? ['color'=>'#64748b', 'bg'=>'#f8fafc'];
            $qtyPermisos = is_array($r->permisos) ? count($r->permisos) : 0;
        @endphp
        <div class="card" style="display:flex; flex-direction:column; gap:10px">
            <div style="display:flex; align-items:center; justify-content:space-between">
                <div style="display:flex; align-items:center; gap:10px">
                    <div style="width:34px; height:34px; border-radius:8px; background:{{ $c['bg'] }}; display:flex; align-items:center; justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $c['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700">{{ $r->nombre }}</span>
                </div>
            </div>
            <p style="font-size:13px; color:var(--color-text-muted); margin:0; line-height:1.5">{{ $r->descripcion }}</p>
            <div style="display:flex; gap:12px; font-size:12px; color:var(--color-text-muted); border-top:1px solid var(--color-border); padding-top:10px">
                <span>👥 {{ $r->users_count }} usuario(s)</span>
                <span>🔑 {{ $qtyPermisos }} permiso(s)</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Permisos por Módulo</div>
            <div class="card-subtitle">Marca los cajetines para otorgar/revocar permisos. Administrador siempre tiene acceso total.</div>
        </div>
        
        <form action="{{ route('roles.bulk') }}" method="POST">
            @csrf
            <div class="table-wrapper" style="border:none; margin:0 -20px">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Módulo / Acción</th>
                            @foreach($roles as $r)
                                <th style="text-align:center">{{ $r->nombre }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $listaPermisos = [
                            'productos.ver' => 'Productos — Ver',
                            'productos.crear' => 'Productos — Crear',
                            'productos.editar' => 'Productos — Editar',
                            'productos.eliminar' => 'Productos — Eliminar',
                            'ventas.ver' => 'Ventas — Ver',
                            'ventas.crear' => 'Ventas — Crear',
                            'ventas.anular' => 'Ventas — Anular',
                            'proveedores.ver' => 'Proveedores — Ver',
                            'proveedores.crear' => 'Proveedores — Crear',
                            'proveedores.editar' => 'Proveedores — Editar',
                            'proveedores.eliminar' => 'Proveedores — Eliminar',
                            'estadisticas.ver' => 'Estadísticas — Ver',
                            'historial.ver' => 'Historial Logs — Ver',
                            'usuarios.gestionar' => 'Usuarios — Gestionar',
                            'roles.gestionar' => 'Roles — Gestionar',
                        ];
                        @endphp
                        @foreach($listaPermisos as $key => $label)
                        <tr>
                            <td style="font-size:13px;"><b>{{ explode(' —', $label)[0] }}</b> <span style="color:var(--color-text-muted)">— {{ explode('— ', $label)[1] }}</span></td>
                            @foreach($roles as $r)
                                <td style="text-align:center">
                                    <label style="cursor:pointer; display:flex; justify-content:center; align-items:center; width:100%; height:100%;">
                                        <input type="checkbox" name="roles[{{ $r->id }}][permisos][]" value="{{ $key }}" 
                                            {{ is_array($r->permisos) && in_array($key, $r->permisos) ? 'checked' : '' }}
                                            {{ $r->id == 1 ? 'onclick="return false;"' : '' }}
                                            style="width:18px; height:18px; accent-color:#2563eb; cursor:{{ $r->id == 1 ? 'not-allowed' : 'pointer' }}">
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="padding:16px 20px; display:flex; justify-content:flex-end; border-top:1px solid #eee">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Guardar Matriz de Permisos
                </button>
            </div>
        </form>
    </div>

</x-app-layout>
