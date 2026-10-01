<x-guest-layout>
    <div class="auth-form-wrap">

        <h2 class="auth-title">Recuperar Contraseña</h2>
        <p class="auth-text">
            Ingresa tu correo electrónico y te enviaremos un enlace para crear una nueva contraseña.
        </p>

        @if (session('status'))
            <div class="auth-status" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Correo electrónico" aria-label="Correo electrónico">
            </div>
            @error('email') <div class="auth-error" role="alert">{{ $message }}</div> @enderror

            <button type="submit" class="btn-submit">
                ENVIAR ENLACE
            </button>

            <a href="{{ route('login') }}" class="forgot-link">Volver al inicio de sesión</a>
        </form>
    </div>
</x-guest-layout>
