<section>
    <header style="margin-bottom:16px">
        <h2 class="card-title">Actualizar Contraseña</h2>
        <p class="card-subtitle">Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerte seguro.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="display:flex; flex-direction:column; gap:16px">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">Contraseña Actual</label>
            <input type="password" id="update_password_current_password" name="current_password" class="form-input" autocomplete="current-password" />
            @error('current_password', 'updatePassword') <span style="color:var(--color-danger); font-size:12px">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">Nueva Contraseña</label>
            <input type="password" id="update_password_password" name="password" class="form-input" autocomplete="new-password" />
            @error('password', 'updatePassword') <span style="color:var(--color-danger); font-size:12px">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword') <span style="color:var(--color-danger); font-size:12px">{{ $message }}</span> @enderror
        </div>

        <div style="display:flex; align-items:center; gap:12px; margin-top:8px">
            <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" style="font-size:13px; color:var(--color-success); margin:0">Guardado exitosamente.</p>
            @endif
        </div>
    </form>
</section>
