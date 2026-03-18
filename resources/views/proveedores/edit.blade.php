<x-app-layout :pageTitle="'Editar Proveedor'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Editar Proveedor</h1>
            <p>Modifica la información del proveedor</p>
        </div>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="max-width:680px">
        <div class="card">
            <form method="POST" action="{{ route('proveedores.update', $proveedor->id ?? 1) }}">
                @csrf @method('PUT')
                <div style="display:flex; flex-direction:column; gap:16px">

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Nombre / Razón social</label>
                            <input type="text" name="nombre" class="form-input" value="{{ $proveedor->nombre ?? 'TechParts S.A.' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">RUC / NIF</label>
                            <input type="text" name="ruc" class="form-input" value="{{ $proveedor->ruc ?? '20501234567' }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Persona de contacto</label>
                            <input type="text" name="contacto" class="form-input" value="{{ $proveedor->contacto ?? 'Carlos Gómez' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-input" value="{{ $proveedor->telefono ?? '+51 998 112 233' }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ $proveedor->email ?? 'cgomez@techparts.com' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-input" value="{{ $proveedor->direccion ?? 'Av. Industrial 1234, Lima' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notas</label>
                        <textarea name="notas" class="form-textarea">{{ $proveedor->notas ?? '' }}</textarea>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; justify-content:space-between; align-items:center">
                        <form method="POST" action="{{ route('proveedores.destroy', $proveedor->id ?? 1) }}" onsubmit="return confirm('¿Eliminar proveedor?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <div style="display:flex; gap:10px">
                            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

</x-app-layout>
