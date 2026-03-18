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

    <div style="max-width:680px">
        <div class="card">
            <form method="POST" action="{{ route('proveedores.store') }}">
                @csrf
                <div style="display:flex; flex-direction:column; gap:16px">

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Nombre / Razón social <span style="color:var(--color-danger)">*</span></label>
                            <input type="text" name="nombre" class="form-input" placeholder="Ej: TechParts S.A." required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">RUC / NIF</label>
                            <input type="text" name="ruc" class="form-input" placeholder="20501234567">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Persona de contacto</label>
                            <input type="text" name="contacto" class="form-input" placeholder="Nombre y apellido">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-input" placeholder="+51 999 000 111">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="contacto@proveedor.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sitio web</label>
                            <input type="url" name="web" class="form-input" placeholder="https://proveedor.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-input" placeholder="Av. Industrial 123, Lima">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notas</label>
                        <textarea name="notas" class="form-textarea" placeholder="Condiciones de pago, observaciones..."></textarea>
                    </div>

                    <hr class="divider">

                    <div style="display:flex; gap:10px; justify-content:flex-end">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Guardar Proveedor
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</x-app-layout>
