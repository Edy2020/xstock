<x-app-layout :pageTitle="'Editar Producto'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Editar Producto</h1>
            <p>{{ $producto->nombre }}</p>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="max-width:560px">
        <div class="card">
            <form method="POST" action="{{ route('productos.update', $producto) }}" id="form-update">
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
                        <label class="form-label" for="nombre">Nombre <span style="color:var(--color-danger)">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-input"
                            value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="categoria">Categoría</label>
                        <input type="text" id="categoria" name="categoria" class="form-input"
                            value="{{ old('categoria', $producto->categoria) }}"
                            placeholder="Ej: Electrónica, Accesorios..." list="categorias-lista">
                        <datalist id="categorias-lista">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="proveedor_id">Proveedor</label>
                        <select id="proveedor_id" name="proveedor_id" class="form-select">
                            <option value="">Sin proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}"
                                    {{ old('proveedor_id', $producto->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="precio">Precio (CLP) <span style="color:var(--color-danger)">*</span></label>
                        <div style="position:relative">
                            <span style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--color-text-muted); font-size:13px; pointer-events:none">$</span>
                            <input type="number" id="precio" name="precio" class="form-input"
                                value="{{ old('precio', $producto->precio) }}" min="0" step="1"
                                style="padding-left:22px" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="stock">Stock <span style="color:var(--color-danger)">*</span></label>
                        <input type="number" id="stock" name="stock" class="form-input"
                            value="{{ old('stock', $producto->stock) }}" min="0" step="1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <div style="display:flex; gap:10px">
                            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px">
                                <input type="radio" name="estado" value="activo"
                                    {{ old('estado', $producto->estado) === 'activo' ? 'checked' : '' }}>
                                <span class="badge badge-green">Activo</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; font-size:13px">
                                <input type="radio" name="estado" value="inactivo"
                                    {{ old('estado', $producto->estado) === 'inactivo' ? 'checked' : '' }}>
                                <span class="badge badge-gray">Inactivo</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="divider">

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px">
                @if(auth()->user()->hasPermission('productos.eliminar'))
                <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                      onsubmit="return confirm('¿Eliminar el producto «{{ $producto->nombre }}»?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                        Eliminar
                    </button>
                </form>
                @else
                <div></div> <!-- Placeholder -->
                @endif
                <div style="display:flex; gap:10px">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" form="form-update" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>

        <p style="margin-top:12px; font-size:12px; color:var(--color-text-muted); text-align:right">
            Creado: {{ $producto->created_at?->format('d/m/Y H:i') }} —
            Actualizado: {{ $producto->updated_at?->format('d/m/Y H:i') }}
        </p>
    </div>

</x-app-layout>
