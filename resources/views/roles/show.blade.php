<x-app-layout :pageTitle="'Permisos de ' . $role->nombre">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Permisos del Rol: {{ $role->nombre }}</h1>
            <p>Configura específicamente a qué módulos y acciones tiene acceso este rol.</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver a Roles
        </a>
    </div>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success" style="margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:10px">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; display:flex; align-items:center; opacity:0.7">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif

    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <div class="card-title">Detalles y Permisos</div>
            <div class="card-subtitle">
                @if($role->id == 1)
                    El Administrador principal siempre tiene acceso total. Su nombre y permisos no pueden modificarse.
                @else
                    Actualiza la información y marca los cajetines para conceder accesos.
                @endif
            </div>
        </div>

        <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display:flex; flex-direction:column; gap:14px; margin-bottom: 24px;">
                <div class="form-group">
                    <label class="form-label" for="nombre">Nombre <span style="color:var(--color-danger)">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-input"
                        value="{{ old('nombre', $role->nombre) }}" {{ $role->id == 1 ? 'readonly' : 'required' }}>
                </div>

                <div class="form-group">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-textarea">{{ old('descripcion', $role->descripcion) }}</textarea>
                </div>
            </div>

            <div class="table-wrapper" style="border:none; margin:0 -20px">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Módulo / Acción</th>
                            <th style="text-align:center; width: 100px;">Acceso</th>
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
                        <tr class="permission-row" style="cursor:{{ $role->id == 1 ? 'not-allowed' : 'pointer' }}" onclick="let cb = this.querySelector('input[type=checkbox]'); if(event.target.tagName !== 'INPUT' && event.target.tagName !== 'LABEL' && !cb.disabled) { cb.click(); }">
                            <td style="font-size:13px; padding-left:14px"><b>{{ explode(' —', $label)[0] }}</b> <span style="color:var(--color-text-muted)">— {{ explode('— ', $label)[1] }}</span></td>
                            <td style="text-align:center">
                                <label style="cursor:{{ $role->id == 1 ? 'not-allowed' : 'pointer' }}; display:flex; justify-content:center; align-items:center; width:100%; height:100%; margin:0">
                                    <input type="checkbox" name="permisos[]" value="{{ $key }}" 
                                        {{ is_array($role->permisos) && in_array($key, $role->permisos) ? 'checked' : '' }}
                                        {{ $role->id == 1 ? 'onclick="return false;"' : '' }}
                                        style="width:18px; height:18px; accent-color:#2563eb; cursor:{{ $role->id == 1 ? 'not-allowed' : 'pointer' }}">
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:16px 20px; display:flex; justify-content:flex-end; border-top:1px solid #eee">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Actualizar Rol
                </button>
            </div>
        </form>
    </div>

</x-app-layout>

<style>
    .permission-row {
        transition: background-color 0.2s ease;
    }
    .permission-row:hover {
        background-color: #f8fafc;
    }
    [data-theme="dark"] .permission-row:hover {
        background-color: #272f3d;
    }
</style>
