<x-app-layout :pageTitle="'Nuevo Proveedor'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Nuevo Proveedor</h1>
            <p>Registra un nuevo proveedor en el sistema</p>
        </div>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="max-width:560px">
        <div class="card">
            <form method="POST" action="{{ route('proveedores.store') }}">
                @csrf

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
                            value="{{ old('nombre') }}" placeholder="Ej: Tech Distribuidores Ltda." autofocus required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="ruc">RUC / NIF</label>
                            <input type="text" id="ruc" name="ruc" class="form-input"
                                value="{{ old('ruc') }}" placeholder="Ej: 76.123.456-7">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-input"
                                value="{{ old('telefono') }}" placeholder="+56 9 1234 5678">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="contacto">Persona de contacto</label>
                            <input type="text" id="contacto" name="contacto" class="form-input"
                                value="{{ old('contacto') }}" placeholder="Nombre del contacto">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-input"
                                value="{{ old('email') }}" placeholder="contacto@empresa.cl">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-input"
                            value="{{ old('direccion') }}" placeholder="Calle, número, ciudad">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notas">Notas adicionales</label>
                        <textarea id="notas" name="notas" class="form-textarea"
                            placeholder="Condiciones de pago, plazos de entrega, etc.">{{ old('notas') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <div style="display:flex; gap:10px">
                            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px">
                                <input type="radio" name="estado" value="activo"
                                    {{ old('estado', 'activo') === 'activo' ? 'checked' : '' }}>
                                <span class="badge badge-green">Activo</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px">
                                <input type="radio" name="estado" value="inactivo"
                                    {{ old('estado') === 'inactivo' ? 'checked' : '' }}>
                                <span class="badge badge-gray">Inactivo</span>
                            </label>
                        </div>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; gap:10px; justify-content:flex-end">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            Guardar Proveedor
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</x-app-layout>
