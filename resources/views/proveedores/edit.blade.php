<x-app-layout :pageTitle="'Editar Proveedor'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Editar Proveedor</h1>
            <p>{{ $proveedor->nombre }}</p>
        </div>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="max-width:560px">
        <div class="card">
            <form method="POST" action="{{ route('proveedores.update', $proveedor) }}" id="form-update">
                @csrf @method('PUT')

                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom:16px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <ul style="margin:0; padding-left:16px">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div style="display:flex; flex-direction:column; gap:14px">

                    <div class="form-group">
                        <label class="form-label" for="nombre">Nombre / Razón Social <span style="color:var(--color-danger)">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-input"
                            value="{{ old('nombre', $proveedor->nombre) }}" required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="ruc">RUC / NIF</label>
                            <input type="text" id="ruc" name="ruc" class="form-input"
                                value="{{ old('ruc', $proveedor->ruc) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-input"
                                value="{{ old('telefono', $proveedor->telefono) }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="contacto">Persona de contacto</label>
                            <input type="text" id="contacto" name="contacto" class="form-input"
                                value="{{ old('contacto', $proveedor->contacto) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-input"
                                value="{{ old('email', $proveedor->email) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-input"
                            value="{{ old('direccion', $proveedor->direccion) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notas">Notas adicionales</label>
                        <textarea id="notas" name="notas" class="form-textarea">{{ old('notas', $proveedor->notas) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <div style="display:flex; gap:10px">
                            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px">
                                <input type="radio" name="estado" value="activo"
                                    {{ old('estado', $proveedor->estado) === 'activo' ? 'checked' : '' }}>
                                <span class="badge badge-green">Activo</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px">
                                <input type="radio" name="estado" value="inactivo"
                                    {{ old('estado', $proveedor->estado) === 'inactivo' ? 'checked' : '' }}>
                                <span class="badge badge-gray">Inactivo</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="divider">

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px">
                <form method="POST" action="{{ route('proveedores.destroy', $proveedor) }}"
                      onsubmit="return confirm('¿Eliminar el proveedor «{{ $proveedor->nombre }}»? Sus productos quedarán sin proveedor.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                        Eliminar
                    </button>
                </form>
                <div style="display:flex; gap:10px">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" form="form-update" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>

        @if($proveedor->productos->isNotEmpty())
        <div class="card" style="margin-top:16px">
            <div class="card-header">
                <div class="card-title">Productos de este proveedor</div>
                <span class="badge badge-blue">{{ $proveedor->productos->count() }}</span>
            </div>
            <div style="display:flex; flex-direction:column; gap:8px">
                @foreach($proveedor->productos as $p)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--color-border)">
                    <span style="font-size:13px; font-weight:500">{{ $p->nombre }}</span>
                    <div style="display:flex; align-items:center; gap:10px">
                        <span style="font-size:12px; color:var(--color-text-muted)">Stock: {{ $p->stock }}</span>
                        <span class="badge {{ $p->badge_estado }}">{{ $p->label_estado }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <p style="margin-top:12px; font-size:12px; color:var(--color-text-muted); text-align:right">
            Creado: {{ $proveedor->created_at?->format('d/m/Y H:i') }}
        </p>
    </div>

</x-app-layout>
