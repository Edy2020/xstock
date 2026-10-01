<x-guest-layout>
    <div class="auth-form-wrap">

        <h2 class="auth-title">Nueva Contraseña</h2>
        <p class="auth-text">Elige una contraseña segura para tu cuenta.</p>

        <form method="POST" action="{{ route('password.store') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="Correo electrónico" aria-label="Correo electrónico">
            </div>
            @error('email') <div class="auth-error" role="alert">{{ $message }}</div> @enderror

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input id="password" class="input-field" type="password" name="password" required autocomplete="new-password" placeholder="Nueva contraseña" aria-label="Nueva contraseña">
            </div>
            @error('password') <div class="auth-error" role="alert">{{ $message }}</div> @enderror

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña" aria-label="Confirmar contraseña">
            </div>
            @error('password_confirmation') <div class="auth-error" role="alert">{{ $message }}</div> @enderror

            <button type="submit" class="btn-submit">
                GUARDAR CONTRASEÑA
            </button>
        </form>
    </div>
</x-guest-layout>
