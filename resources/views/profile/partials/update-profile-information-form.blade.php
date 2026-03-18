<section>
    <header style="margin-bottom:16px">
        <h2 class="card-title">Información del Perfil</h2>
        <p class="card-subtitle">Actualiza la información de tu cuenta y dirección de correo electrónico.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="display:flex; flex-direction:column; gap:16px">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name') <span style="color:var(--color-danger); font-size:12px">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <span style="color:var(--color-danger); font-size:12px">{{ $message }}</span> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:8px">
                    <p style="font-size:12px; color:var(--color-warning); margin:0">Tu dirección de correo electrónico no está verificada.
                        <button form="send-verification" class="btn btn-secondary btn-sm" style="margin-left:8px">Haz clic aquí para reenviar el correo de verificación.</button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="font-size:12px; color:var(--color-success); margin-top:4px">Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.</p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display:flex; align-items:center; gap:12px; margin-top:8px">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" style="font-size:13px; color:var(--color-success); margin:0">Guardado exitosamente.</p>
            @endif
        </div>
    </form>
</section>
