<x-guest-layout>
    <div class="auth-form-wrap">

        <h2 class="auth-title">Verifica tu Correo</h2>
        <p class="auth-text">
            Antes de continuar, confirma tu dirección de correo con el enlace que te enviamos.
            Si no lo recibiste, podemos enviarte otro.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-status" role="status">
                Te enviamos un nuevo enlace de verificación a tu correo.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
            @csrf
            <button type="submit" class="btn-submit">
                REENVIAR CORREO
            </button>
        </form>

        <div class="auth-links">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="forgot-link">Cerrar sesión</button>
            </form>
        </div>
    </div>
</x-guest-layout>
