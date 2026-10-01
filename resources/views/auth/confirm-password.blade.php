<x-guest-layout>
    <div class="auth-form-wrap">

        <h2 class="auth-title">Confirmar Contraseña</h2>
        <p class="auth-text">
            Estás entrando a un área protegida. Confirma tu contraseña para continuar.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
            @csrf

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input id="password" class="input-field" type="password" name="password" required autofocus autocomplete="current-password" placeholder="Contraseña" aria-label="Contraseña">
            </div>
            @error('password') <div class="auth-error" role="alert">{{ $message }}</div> @enderror

            <button type="submit" class="btn-submit">
                CONFIRMAR
            </button>
        </form>
    </div>
</x-guest-layout>
