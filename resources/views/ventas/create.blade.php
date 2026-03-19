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

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px">
            <ul style="margin:0; padding-left:16px">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ventas.store') }}" id="form-venta">
        @csrf
        <input type="hidden" name="productos" id="productos-input" value="[]">
        <input type="hidden" name="descuento_global" id="descuento-global-input" value="0">

        <div style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:flex-start">

            {{-- Panel izquierdo: selección de productos --}}
            <div style="display:flex; flex-direction:column; gap:16px">

                {{-- Buscar y añadir producto --}}
                <div class="card">
                    <div class="card-title" style="margin-bottom:12px">Catálogo de Productos</div>
                    <div class="search-bar" style="width:100%; margin-bottom:16px">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="product-search-input" placeholder="Buscar producto por nombre..." autocomplete="off">
                    </div>
                    
                    {{-- Catálogo en tabla con slider vertical --}}
                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid var(--color-border); border-radius: 6px;">
                        <table class="data-table" style="margin: 0; min-width: 100%">
                            <thead style="position: sticky; top: 0; z-index: 10; background: var(--color-bg-alt);">
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Precio</th>
                                    <th style="width: 80px; text-align: center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="catalog-tbody">
                                <!-- Opciones generadas vía JS -->
                            </tbody>
                        </table>
                        <div id="catalog-empty-state" style="display:none; padding:20px; text-align:center; color:var(--color-text-muted); font-size:13px">
                            No se encontraron productos.
                        </div>
                    </div>
                </div>

                {{-- Tabla de items en la venta (Carrito) --}}
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
                            <tbody id="cart-tbody">
                                {{-- Row rendered by JS --}}
                                <tr id="empty-cart-row">
                                    <td colspan="6" style="text-align:center; padding:30px; color:var(--color-text-muted)">
                                        El carrito está vacío. Seleccione productos arriba para comenzar.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Panel derecho: resumen y confirmar --}}
            <div style="position:sticky; top:20px">
                <div class="card">
                    <div class="card-title" style="margin-bottom:14px">Resumen de Venta</div>

                    <div style="display:flex; flex-direction:column; gap:8px; font-size:13px">
                        <div style="display:flex; justify-content:space-between">
                            <span style="color:var(--color-text-muted)">Subtotal</span>
                            <span id="summary-subtotal">$0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center">
                            <span style="color:var(--color-text-muted)">Desc. Global (%)</span>
                            <input type="number" id="global-discount-ui" min="0" max="100" value="0" class="form-input" style="width:60px; text-align:center; padding:4px; font-size:12px">
                        </div>
                        <div style="display:flex; justify-content:space-between">
                            <span style="color:var(--color-text-muted)">Descuentos Acum.</span>
                            <span id="summary-discount" style="color:var(--color-danger)">−$0</span>
                        </div>
                        <hr class="divider" style="margin:4px 0">
                        <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700">
                            <span>Total</span>
                            <span id="summary-total">$0</span>
                        </div>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; flex-direction:column; gap:10px">
                        <div class="form-group">
                            <label class="form-label">Método de pago</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                                <option value="Tarjeta de débito">Tarjeta de débito</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notas (opcional)</label>
                            <textarea name="notas" class="form-textarea" style="min-height:60px" placeholder="Observaciones de la venta...">{{ old('notas') }}</textarea>
                        </div>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; flex-direction:column; gap:8px">
                        <button type="submit" id="btn-submit" class="btn btn-primary" style="justify-content:center" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Confirmar Venta
                        </button>
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary" style="justify-content:center">Cancelar</a>
                    </div>

                    <div class="alert alert-info" style="margin-top:12px; padding:10px; font-size:12px">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        El stock se descontará automáticamente al confirmar.
                    </div>
                </div>
            </div>

        </div>
    </form>

    @php
        $catalogoArray = $productos->map(function($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'precio' => $p->precio,
                'stock' => $p->stock
            ];
        })->values()->all();
    @endphp

    <script>
        // Catálogo de productos inyectado desde backend
        const catalogo = @json($catalogoArray);

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('product-search-input');
            const catalogTbody = document.getElementById('catalog-tbody');
            const catalogEmpty = document.getElementById('catalog-empty-state');
            const tbody = document.getElementById('cart-tbody');
            const inputData = document.getElementById('productos-input');
            const btnSubmit = document.getElementById('btn-submit');
            
            // Format numbers to CLP
            const formatCLP = (num) => '$' + Math.round(num).toLocaleString('es-CL');

            let cart = [];

            function renderCart() {
                tbody.innerHTML = '';
                let grossSubtotal = 0;
                let totalDiscount = 0;

                if (cart.length === 0) {
                    tbody.innerHTML = `
                        <tr id="empty-cart-row">
                            <td colspan="6" style="text-align:center; padding:30px; color:var(--color-text-muted)">
                                El carrito está vacío. Seleccione productos arriba para comenzar.
                            </td>
                        </tr>
                    `;
                    inputData.value = "[]";
                    btnSubmit.disabled = true;
                } else {
                    cart.forEach((item, index) => {
                        const amount = item.precio * item.cantidad;
                        const discount = amount * (item.descuento / 100);
                        const netAmount = amount - discount;

                        grossSubtotal += amount;
                        totalDiscount += discount;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <span style="font-weight:500">${item.nombre}</span><br>
                                <span style="font-size:11.5px; color:var(--color-text-muted)">Stock disp: ${item.stock}</span>
                            </td>
                            <td>${formatCLP(item.precio)}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:4px">
                                    <input type="number" class="form-input dt-desc" data-index="${index}" value="${item.descuento}" min="0" max="100" style="width:60px; padding:5px; text-align:center">
                                    <span style="font-size:12px; color:var(--color-text-muted)">%</span>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-input dt-qty" data-index="${index}" value="${item.cantidad}" min="1" max="${item.stock}" style="width:60px; padding:5px; text-align:center">
                            </td>
                            <td style="font-weight:600">${formatCLP(netAmount)}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm dt-btn-remove" data-index="${index}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });

                    // Update hidden input
                    inputData.value = JSON.stringify(cart);
                    btnSubmit.disabled = false;
                }

                // Global discount logic
                let globalDescPct = parseInt(document.getElementById('global-discount-ui').value) || 0;
                if(globalDescPct < 0) globalDescPct = 0;
                if(globalDescPct > 100) globalDescPct = 100;
                document.getElementById('descuento-global-input').value = globalDescPct;

                let currentSubtotalDescItems = grossSubtotal - totalDiscount;
                let addGlobalDiscount = currentSubtotalDescItems * (globalDescPct / 100);
                totalDiscount += addGlobalDiscount;

                // Update summary
                document.getElementById('summary-subtotal').innerText = formatCLP(grossSubtotal);
                document.getElementById('summary-discount').innerText = '−' + formatCLP(totalDiscount);
                document.getElementById('summary-total').innerText = formatCLP(grossSubtotal - totalDiscount);

                attachEvents();
                updateCatalogHighlights();
            }

            function updateCatalogHighlights() {
                const rows = catalogTbody.querySelectorAll('tr[data-pid]');
                rows.forEach(tr => {
                    const pid = parseInt(tr.dataset.pid);
                    const cartItem = cart.find(c => c.id === pid);
                    const stockCell = tr.querySelector('.cat-stock-cell');
                    
                    if (cartItem) {
                        tr.style.backgroundColor = 'var(--color-bg-alt)';
                        tr.style.boxShadow = 'inset 4px 0 0 var(--color-primary)';
                        if (stockCell) stockCell.innerText = cartItem.stock - cartItem.cantidad;
                    } else {
                        tr.style.backgroundColor = 'transparent';
                        tr.style.boxShadow = 'none';
                        if (stockCell) stockCell.innerText = stockCell.dataset.originalStock;
                    }
                });
            }

            // Escuchar cambios en descuento global
            document.getElementById('global-discount-ui').addEventListener('input', renderCart);

            function attachEvents() {
                // Cantidad change
                document.querySelectorAll('.dt-qty').forEach(input => {
                    input.addEventListener('change', (e) => {
                        let i = e.target.getAttribute('data-index');
                        let val = parseInt(e.target.value);
                        if(isNaN(val) || val < 1) val = 1;
                        if(val > cart[i].stock) val = cart[i].stock; // Max stock check
                        cart[i].cantidad = val;
                        renderCart();
                    });
                });

                // Descuento change
                document.querySelectorAll('.dt-desc').forEach(input => {
                    input.addEventListener('change', (e) => {
                        let i = e.target.getAttribute('data-index');
                        let val = parseInt(e.target.value);
                        if(isNaN(val) || val < 0) val = 0;
                        if(val > 100) val = 100;
                        cart[i].descuento = val;
                        renderCart();
                    });
                });

                // Botón eliminar
                document.querySelectorAll('.dt-btn-remove').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        let i = e.currentTarget.getAttribute('data-index');
                        cart.splice(i, 1);
                        renderCart();
                    });
                });
            }

            // --- Lógica Autocompletado Búsqueda ---
            
            function addToCart(productoData) {
                const pid = productoData.id;
                
                // Verificar si ya existe en el carrito
                const exists = cart.findIndex(item => item.id === pid);
                if(exists >= 0) {
                    if (cart[exists].cantidad < cart[exists].stock) {
                        cart[exists].cantidad += 1;
                    } else {
                        alert(`Stock máximo alcanzado para ${productoData.nombre}.`);
                    }
                } else {
                    cart.push({
                        id: pid,
                        nombre: productoData.nombre,
                        precio: productoData.precio,
                        stock: productoData.stock,
                        cantidad: 1,
                        descuento: 0
                    });
                }
                
                renderCart();
            }

            function renderCatalog(items) {
                catalogTbody.innerHTML = '';
                
                if (items.length === 0) {
                    catalogTbody.style.display = 'none';
                    catalogEmpty.style.display = 'block';
                } else {
                    catalogTbody.style.display = '';
                    catalogEmpty.style.display = 'none';

                    items.forEach(p => {
                        const tr = document.createElement('tr');
                        tr.style.cursor = 'pointer';
                        tr.dataset.pid = p.id;
                        
                        const cartItem = cart.find(c => c.id === p.id);
                        const visualStock = cartItem ? (p.stock - cartItem.cantidad) : p.stock;

                        tr.innerHTML = `
                            <td style="font-weight:500">${p.nombre}</td>
                            <td class="cat-stock-cell" data-original-stock="${p.stock}">${visualStock}</td>
                            <td>${formatCLP(p.precio)}</td>
                            <td style="text-align:center">
                                <button type="button" class="btn btn-secondary btn-sm" style="padding:4px 8px; margin:0 auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Añadir
                                </button>
                            </td>
                        `;
                        
                        // Efecto de hover visual para saber que es clickable (si no está seleccionado ya)
                        tr.addEventListener('mouseenter', () => {
                            if (!cart.some(c => c.id === p.id)) tr.style.backgroundColor = 'var(--color-bg-alt)';
                        });
                        tr.addEventListener('mouseleave', () => {
                            if (!cart.some(c => c.id === p.id)) tr.style.backgroundColor = 'transparent';
                        });
                        
                        tr.addEventListener('click', () => {
                            addToCart(p);
                        });
                        
                        catalogTbody.appendChild(tr);
                    });
                }
            }

            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                
                if (term.length === 0) {
                    renderCatalog(catalogo);
                    return;
                }

                const matches = catalogo.filter(p => p.nombre.toLowerCase().includes(term));
                renderCatalog(matches);
            });

            // Inicializar catálogo al cargar la página
            renderCatalog(catalogo);
            updateCatalogHighlights();

            // --- Fin Lógica Autocompletado Búsqueda ---

            // Prevenir submit tonto con enter en el input
            document.getElementById('form-venta').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });

            // Initial render
            renderCart();
        });
    </script>

</x-app-layout>
