<x-app-layout :pageTitle="'Nuevo Rol'">

    <div class="page-header" style="max-width:560px; margin: 0 auto 20px auto; padding: 0;">
        <div class="page-header-left">
            <h1>Nuevo Rol</h1>
            <p>Por favor, completa los datos para crear un nuevo rol</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="max-width:560px; margin: 0 auto;">
        <div class="card">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom:16px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <ul style="margin:0; padding-left:16px">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div style="display:flex; flex-direction:column; gap:14px">

                    <div class="form-group">
                        <label class="form-label" for="nombre">Nombre <span style="color:var(--color-danger)">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-input"
                            value="{{ old('nombre') }}" placeholder="Ej: Gerente, Supervisor..." autofocus required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-textarea" placeholder="Describe brevemente las funciones de este rol...">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Permisos del Rol</label>
                        <div class="table-wrapper" style="border:1px solid var(--color-border); margin-top:8px">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Módulo / Acción</th>
                                        <th style="text-align:center; width: 100px;">Otorgar</th>
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
                                    <tr class="permission-row" style="cursor:pointer" onclick="let cb = this.querySelector('input[type=checkbox]'); if(event.target.tagName !== 'INPUT' && event.target.tagName !== 'LABEL' && !cb.disabled) { cb.click(); }">
                                        <td style="font-size:13px; padding-left:14px"><b>{{ explode(' —', $label)[0] }}</b> <span style="color:var(--color-text-muted)">— {{ explode('— ', $label)[1] }}</span></td>
                                        <td style="text-align:center">
                                            <label style="cursor:pointer; display:flex; justify-content:center; align-items:center; width:100%; height:100%; margin:0">
                                                <input type="checkbox" name="permisos[]" value="{{ $key }}"
                                                    style="width:18px; height:18px; accent-color:#2563eb; cursor:pointer">
                                            </label>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; gap:10px; justify-content:flex-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Guardar Rol
                        </button>
                    </div>

                </div>
            </form>
        </div>
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
