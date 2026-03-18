<x-app-layout :pageTitle="'Nueva Venta'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Nueva Venta</h1>
            <p>Registra una venta y actualiza el stock automáticamente</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Volver
        </a>
    </div>

    <div style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:flex-start">

        {{-- Panel izquierdo: selección de productos --}}
        <div style="display:flex; flex-direction:column; gap:16px">

            {{-- Buscar producto --}}
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Añadir Producto</div>
                <div style="display:flex; gap:8px">
                    <div class="search-bar" style="flex:1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" placeholder="Buscar por nombre o SKU..." style="width:100%">
                    </div>
                    <button class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                        Todos
                    </button>
                </div>
            </div>

            {{-- Tabla de items en la venta --}}
            <div class="card">
                <div class="card-title" style="margin-bottom:12px">Productos en esta venta</div>
                <div class="table-wrapper" style="border:none; margin:0 -20px">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unit.</th>
                                <th>Descuento</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span style="font-weight:500">Monitor LG 24"</span><br>
                                    <span style="font-size:11.5px; color:var(--color-text-muted)">Stock disponible: 15</span>
                                </td>
                                <td>$320.00</td>
                                <td>
                                    <input type="number" class="form-input" value="0" min="0" max="100" style="width:60px; padding:5px 8px; text-align:center">
                                    <span style="font-size:12px; color:var(--color-text-muted)">%</span>
                                </td>
                                <td>
                                    <input type="number" class="form-input" value="1" min="1" style="width:60px; padding:5px 8px; text-align:center">
                                </td>
                                <td style="font-weight:600">$320.00</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="font-weight:500">Teclado Mecánico</span><br>
                                    <span style="font-size:11.5px; color:var(--color-text-muted)">Stock disponible: 8</span>
                                </td>
                                <td>$90.00</td>
                                <td>
                                    <input type="number" class="form-input" value="10" min="0" max="100" style="width:60px; padding:5px 8px; text-align:center">
                                    <span style="font-size:12px; color:var(--color-text-muted)">%</span>
                                </td>
                                <td>
                                    <input type="number" class="form-input" value="2" min="1" style="width:60px; padding:5px 8px; text-align:center">
                                </td>
                                <td style="font-weight:600">$162.00</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin:12px 0 0; padding-top:12px; border-top:1px solid var(--color-border); text-align:right">
                    <button class="btn btn-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Añadir producto
                    </button>
                </div>
            </div>

        </div>

        {{-- Panel derecho: resumen y confirmar --}}
        <div style="position:sticky; top:72px">
            <div class="card">
                <div class="card-title" style="margin-bottom:14px">Resumen de Venta</div>

                <div style="display:flex; flex-direction:column; gap:8px; font-size:13px">
                    <div style="display:flex; justify-content:space-between">
                        <span style="color:var(--color-text-muted)">Subtotal</span>
                        <span>$482.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="color:var(--color-text-muted)">Descuentos</span>
                        <span style="color:var(--color-danger)">−$9.00</span>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="color:var(--color-text-muted)">IVA (0%)</span>
                        <span>$0.00</span>
                    </div>
                    <hr class="divider" style="margin:4px 0">
                    <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700">
                        <span>Total</span>
                        <span>$473.00</span>
                    </div>
                </div>

                <hr class="divider">

                <div style="display:flex; flex-direction:column; gap:10px">
                    <div class="form-group">
                        <label class="form-label">Método de pago</label>
                        <select class="form-select">
                            <option>Efectivo</option>
                            <option>Tarjeta de crédito</option>
                            <option>Tarjeta de débito</option>
                            <option>Transferencia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notas (opcional)</label>
                        <textarea class="form-textarea" style="min-height:60px" placeholder="Observaciones de la venta..."></textarea>
                    </div>
                </div>

                <hr class="divider">

                <div style="display:flex; flex-direction:column; gap:8px">
                    <button class="btn btn-primary" style="justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Confirmar Venta
                    </button>
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary" style="justify-content:center">Cancelar</a>
                </div>

                <div class="alert alert-info" style="margin-top:12px">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    El stock se descontará automáticamente al confirmar.
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
