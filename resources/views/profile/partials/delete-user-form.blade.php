<section>
    <header style="margin-bottom:16px">
        <h2 class="card-title" style="color:var(--color-danger)">Eliminar Cuenta</h2>
        <p class="card-subtitle">Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente.</p>
    </header>

    <button type="button" class="btn btn-danger" onclick="document.getElementById('confirmDeleteModal').style.display='flex'">Eliminar Cuenta</button>

    <!-- Modal Background -->
    <div id="confirmDeleteModal" style="display:{{ $errors->userDeletion->isNotEmpty() ? 'flex' : 'none' }}; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:20px">
        <!-- Modal Content -->
        <div style="background:var(--color-surface); border:1px solid var(--color-border); border-radius:8px; padding:24px; max-width:400px; width:100%; box-shadow:0 10px 25px rgba(0,0,0,0.1)">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h2 style="font-size:16px; font-weight:600; margin-bottom:8px; color:var(--color-text)">¿Estás seguro de que deseas eliminar tu cuenta?</h2>
                <p style="font-size:13px; color:var(--color-text-muted); margin-bottom:16px; line-height:1.4">Ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.</p>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Tu contraseña" required>
                    @error('password', 'userDeletion') <span style="color:var(--color-danger); font-size:12px; margin-top:4px">{{ $message }}</span> @enderror
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('confirmDeleteModal').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                </div>
            </form>
        </div>
    </div>
</section>
