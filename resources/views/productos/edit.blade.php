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
            <form method="POST" action="{{ route('productos.update', $producto) }}" id="form-update" enctype="multipart/form-data">
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
                        <label class="form-label" for="descripcion">Descripción (Opcional)</label>
                        <textarea id="descripcion" name="descripcion" class="form-textarea" placeholder="Breve descripción o caracteristicas del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Imagen del producto</label>
                        <div id="imagen-dropzone" style="border:2px dashed var(--color-border); border-radius:10px; padding:24px; text-align:center; cursor:pointer; transition:border-color 0.2s, background 0.2s; position:relative; overflow:hidden">
                            <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" style="position:absolute; inset:0; opacity:0; cursor:pointer; z-index:2">
                            <div id="imagen-placeholder" style="display:{{ $producto->imagen ? 'none' : 'flex' }}; flex-direction:column; align-items:center; gap:8px; color:var(--color-text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span style="font-size:13px">Arrastra una imagen o haz clic para seleccionar</span>
                                <span style="font-size:11px; opacity:0.6">JPG, PNG o WebP — Máx. 2 MB</span>
                            </div>
                            <div id="imagen-preview" style="display:{{ $producto->imagen ? 'block' : 'none' }}; position:relative">
                                <img id="imagen-preview-img" src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : '' }}" alt="Vista previa" style="max-width:100%; max-height:200px; border-radius:8px; object-fit:contain">
                                <button type="button" id="imagen-remove" style="position:absolute; top:6px; right:6px; background:rgba(0,0,0,0.6); color:#fff; border:none; border-radius:50%; width:28px; height:28px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:16px; z-index:3" title="Quitar imagen">&times;</button>
                            </div>
                        </div>
                        <input type="hidden" id="eliminar_imagen" name="eliminar_imagen" value="0">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="proveedor_nombre">Proveedor</label>
                        <input type="text" id="proveedor_nombre" name="proveedor_nombre" class="form-input"
                            value="{{ old('proveedor_nombre', $producto->proveedor?->nombre) }}" placeholder="Escribe o selecciona un proveedor..."
                            list="proveedores-lista">
                        <datalist id="proveedores-lista">
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->nombre }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px">
                        <div class="form-group">
                            <label class="form-label" for="precio_compra">Precio Compra (CLP) <span style="color:var(--color-danger)">*</span></label>
                            <div style="position:relative">
                                <span style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--color-text-muted); font-size:13px; pointer-events:none">$</span>
                                <input type="number" id="precio_compra" name="precio_compra" class="form-input"
                                    value="{{ old('precio_compra', $producto->precio_compra) }}" min="0" step="1"
                                    style="padding-left:22px" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="precio">Precio Venta (CLP) <span style="color:var(--color-danger)">*</span></label>
                            <div style="position:relative">
                                <span style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--color-text-muted); font-size:13px; pointer-events:none">$</span>
                                <input type="number" id="precio" name="precio" class="form-input"
                                    value="{{ old('precio', $producto->precio) }}" min="0" step="1"
                                    style="padding-left:22px" required>
                            </div>
                        </div>
                    </div>

                    <div id="margen-container" style="background:var(--color-bg); padding:10px 14px; border-radius:8px; display:flex; justify-content:space-between; align-items:center; font-size:13px; border:1px solid var(--color-border); margin-top:-4px">
                        <span style="color:var(--color-text-muted)">Margen de Ganancia Estimado:</span>
                        <span id="margen-valor" style="font-weight:700; color:var(--color-success)">$0 (0%)</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="descuento">Descuento (%)</label>
                        <div style="position:relative">
                            <input type="number" id="descuento" name="descuento" class="form-input"
                                value="{{ old('descuento', $producto->descuento) }}" min="0" max="100" step="1"
                                style="padding-right:30px">
                            <span style="position:absolute; right:11px; top:50%; transform:translateY(-50%); color:var(--color-text-muted); font-size:13px; pointer-events:none">%</span>
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
                <div></div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('imagen');
        const dropzone = document.getElementById('imagen-dropzone');
        const placeholder = document.getElementById('imagen-placeholder');
        const preview = document.getElementById('imagen-preview');
        const previewImg = document.getElementById('imagen-preview-img');
        const removeBtn = document.getElementById('imagen-remove');
        const eliminarInput = document.getElementById('eliminar_imagen');

        function showPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                placeholder.style.display = 'none';
                preview.style.display = 'block';
                eliminarInput.value = '0';
            };
            reader.readAsDataURL(file);
        }

        input.addEventListener('change', () => {
            if (input.files[0]) showPreview(input.files[0]);
        });

        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            input.value = '';
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            eliminarInput.value = '1';
        });

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = 'var(--color-primary)';
            dropzone.style.background = 'var(--color-primary-light, rgba(99,102,241,0.05))';
        });
        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = '';
            dropzone.style.background = '';
        });
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = '';
            dropzone.style.background = '';
            if (e.dataTransfer.files[0]) {
                input.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files[0]);
            }
        });

        // Cálculo de margen
        const precioCompraInput = document.getElementById('precio_compra');
        const precioVentaInput = document.getElementById('precio');
        const margenValor = document.getElementById('margen-valor');

        function calcularMargen() {
            const compra = parseFloat(precioCompraInput.value) || 0;
            const venta = parseFloat(precioVentaInput.value) || 0;
            
            const ganancia = venta - compra;
            const porcentaje = venta > 0 ? ((ganancia / venta) * 100).toFixed(1) : 0;
            
            margenValor.textContent = `$${ganancia.toLocaleString('es-CL')} (${porcentaje}%)`;
            
            if (ganancia < 0) {
                margenValor.style.color = 'var(--color-danger)';
            } else if (ganancia > 0) {
                margenValor.style.color = 'var(--color-success)';
            } else {
                margenValor.style.color = 'var(--color-text-muted)';
            }
        }

        precioCompraInput.addEventListener('input', calcularMargen);
        precioVentaInput.addEventListener('input', calcularMargen);
        calcularMargen();
    });
</script>
