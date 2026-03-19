<x-guest-layout>
    <div style="width: 100%; max-width: 340px; text-align: center; display: flex; flex-direction: column; align-items: center;">
        
        <h2 class="auth-title">Iniciar Sesión</h2>
        <div class="auth-subtitle">
            o usa tus credenciales de empleado
        </div>

        <form method="POST" action="{{ route('login') }}" style="width:100%; display:flex; flex-direction:column; align-items:center;">
            @csrf

            <!-- Email Address -->
            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Correo electrónico">
            </div>
            
            <div style="width:100%; text-align:left">
                <x-input-error :messages="$errors->get('email')" style="margin-top:-8px; margin-bottom:12px; color:var(--color-danger); font-size:12px;" />
            </div>

            <!-- Password -->
            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input id="password" class="input-field" style="letter-spacing:2px; padding-right:8px" type="password" name="password" required autocomplete="current-password" placeholder="Contraseña">
                
                <button type="button" id="toggle-password" style="background:none; border:none; color:var(--color-text-muted); cursor:pointer; padding:4px; display:flex; align-items:center" title="Mostrar/Ocultar">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>

            <div style="width:100%; text-align:left">
                <x-input-error :messages="$errors->get('password')" style="margin-top:-8px; margin-bottom:12px; color:var(--color-danger); font-size:12px;" />
            </div>
            
            <!-- Hint / Remember me replacement for demo purposes -->
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:12.5px; margin-top:20px; margin-bottom:28px; width:100%">
                <label for="remember_visual" style="display:flex; align-items:center; gap:10px; cursor:pointer; color:var(--color-text-muted);">
                    <div style="position:relative; width:16px; height:16px;">
                        <input id="remember_visual" type="checkbox" style="opacity:0; position:absolute; width:100%; height:100%; cursor:pointer; z-index:2" onchange="document.getElementById('custom-check').style.background = this.checked ? 'var(--color-primary)' : 'transparent'; document.getElementById('custom-check').style.borderColor = this.checked ? 'var(--color-primary)' : 'var(--color-border)'; document.getElementById('remember_me').checked = this.checked;">
                        <div id="custom-check" style="position:absolute; inset:0; border:1px solid var(--color-border); border-radius:3px; pointer-events:none; transition:all 0.2s; display:flex; align-items:center; justify-content:center; background:var(--color-primary); border-color:var(--color-primary);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                    </div>
                    Recordarme
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link" style="margin-top:0;">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                INGRESAR
            </button>
        </form>
    </div>
</x-guest-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePassword.addEventListener('click', function (e) {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'text') {
                passwordInput.style.letterSpacing = 'normal';
                eyeIcon.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>`;
            } else {
                passwordInput.style.letterSpacing = '2px';
                eyeIcon.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;
            }
        });
    });
</script>
