<x-app-layout :pageTitle="'Nuevo Producto'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Nuevo Producto</h1>
            <p>Añade un nuevo producto al inventario</p>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="max-width:680px">
        <div class="card">
            <form method="POST" action="{{ route('productos.store') }}">
                @csrf

                <div style="display:flex; flex-direction:column; gap:16px">

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Nombre del Producto <span style="color:var(--color-danger)">*</span></label>
                            <input type="text" name="nombre" class="form-input" placeholder="Ej: Monitor LG 24 pulgadas" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-input" placeholder="Ej: PRD-001">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option value="">Seleccionar categoría</option>
                                <option>Electrónica</option>
                                <option>Accesorios</option>
                                <option>Cables</option>
                                <option>Periféricos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Proveedor</label>
                            <select name="proveedor_id" class="form-select">
                                <option value="">Seleccionar proveedor</option>
                                <option>TechParts S.A.</option>
                                <option>Electronica Global</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Precio de Costo <span style="color:var(--color-danger)">*</span></label>
                            <input type="number" name="precio_costo" class="form-input" placeholder="0.00" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Precio de Venta <span style="color:var(--color-danger)">*</span></label>
                            <input type="number" name="precio_venta" class="form-input" placeholder="0.00" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">IVA (%)</label>
                            <input type="number" name="iva" class="form-input" placeholder="0" value="0">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Stock Inicial <span style="color:var(--color-danger)">*</span></label>
                            <input type="number" name="stock" class="form-input" placeholder="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stock Mínimo</label>
                            <input type="number" name="stock_min" class="form-input" placeholder="5">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-textarea" placeholder="Descripción del producto..."></textarea>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; gap:10px; justify-content:flex-end">
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Guardar Producto
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</x-app-layout>
