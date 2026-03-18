<x-app-layout :pageTitle="'Editar Producto'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Editar Producto</h1>
            <p>Modifica la información del producto</p>
        </div>
        <div style="display:flex; gap:8px">
            <a href="{{ route('productos.show', $producto->id ?? 1) }}" class="btn btn-secondary">Ver detalles</a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver
            </a>
        </div>
    </div>

    <div style="max-width:680px">
        <div class="card">
            <form method="POST" action="{{ route('productos.update', $producto->id ?? 1) }}">
                @csrf @method('PUT')

                <div style="display:flex; flex-direction:column; gap:16px">

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Nombre del Producto <span style="color:var(--color-danger)">*</span></label>
                            <input type="text" name="nombre" class="form-input" value="{{ $producto->nombre ?? 'Monitor LG 24 pulgadas' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-input" value="{{ $producto->sku ?? 'PRD-001' }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option>Electrónica</option>
                                <option>Accesorios</option>
                                <option>Cables</option>
                                <option>Periféricos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Proveedor</label>
                            <select name="proveedor_id" class="form-select">
                                <option>TechParts S.A.</option>
                                <option>Electronica Global</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Precio de Costo</label>
                            <input type="number" name="precio_costo" class="form-input" value="{{ $producto->precio_costo ?? '250.00' }}" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Precio de Venta</label>
                            <input type="number" name="precio_venta" class="form-input" value="{{ $producto->precio_venta ?? '320.00' }}" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="form-label">IVA (%)</label>
                            <input type="number" name="iva" class="form-input" value="{{ $producto->iva ?? '0' }}">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Stock Actual</label>
                            <input type="number" name="stock" class="form-input" value="{{ $producto->stock ?? '15' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock Mínimo</label>
                            <input type="number" name="stock_min" class="form-input" value="{{ $producto->stock_min ?? '5' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-textarea">{{ $producto->descripcion ?? 'Monitor Full HD de 24 pulgadas con panel IPS.' }}</textarea>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; justify-content:space-between; align-items:center">
                        <form method="POST" action="{{ route('productos.destroy', $producto->id ?? 1) }}" onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar producto</button>
                        </form>
                        <div style="display:flex; gap:10px">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
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
