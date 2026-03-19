<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'XStock') }} - Acceso</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">


        <link rel="stylesheet" href="{{ asset('css/xstock.css') }}">
        
        <script>
            const savedTheme = localStorage.getItem('xstock-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        </script>
        
        <style>
            body {
                background-color: #f3f4f6;
                color: var(--color-text);
                margin: 0;
                font-family: 'Inter', sans-serif;
                display: flex;
                min-height: 100vh;
                align-items: center;
                justify-content: center;
                padding: 20px;
                box-sizing: border-box;
                position: relative;
                overflow: hidden;
                z-index: 1;
            }
            [data-theme="dark"] body {
                background-color: #111827;
            }

            .split-card {
                display: flex;
                width: 100%;
                max-width: 1200px;
                min-height: 720px;
                background-color: var(--color-surface);
                border-radius: 12px;
                overflow: hidden;
            }
            [data-theme="dark"] .split-card {
                border: 1px solid var(--color-border);
            }

            .split-left {
                flex: 0.42;
                background-color: var(--color-primary);
                padding: 40px;
                color: #ffffff;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            .left-brand {
                position: absolute;
                top: 30px;
                left: 30px;
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 600;
                font-size: 15px;
                letter-spacing: 0.5px;
            }
            .left-brand svg {
                width: 18px;
                height: 18px;
            }
            
            .left-title {
                font-size: 32px;
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 22px;
                letter-spacing: -0.5px;
            }
            .left-text {
                font-size: 14px;
                font-weight: 400;
                line-height: 1.5;
                color: rgba(255,255,255,0.9);
                margin-bottom: 36px;
                max-width: 230px;
            }
            
            .btn-outline {
                border: 1px solid #ffffff;
                color: #ffffff;
                background: transparent;
                padding: 10px 42px;
                border-radius: 9999px;
                font-weight: 700;
                font-size: 12px;
                letter-spacing: 1px;
                text-transform: uppercase;
                cursor: pointer;
                transition: background 0.2s, color 0.2s;
            }
            .btn-outline:hover {
                background: #ffffff;
                color: var(--color-primary);
            }

            .split-right {
                flex: 0.58;
                padding: 40px 70px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            @media (max-width: 768px) {
                .split-card { flex-direction: column; }
                .split-left { padding: 40px 24px; }
                .left-brand { position: static; margin-bottom: 30px; justify-content: center; }
                .split-right { padding: 40px 24px; }
            }

            .auth-title {
                font-size: 34px;
                font-weight: 800;
                color: var(--color-primary);
                margin-bottom: 14px;
                letter-spacing: -0.5px;
            }
            [data-theme="dark"] .auth-title { color: var(--color-primary); }
            
            .auth-subtitle {
                font-size: 13px;
                color: var(--color-text-muted);
                margin-bottom: 32px;
            }

            .input-group {
                width: 100%;
                margin-bottom: 14px;
                position: relative;
                display: flex;
                align-items: center;
                background-color: #f3f4f6;
                border-radius: 6px;
                padding: 0 14px;
                box-sizing: border-box;
                border: 1px solid transparent;
                transition: border-color 0.2s;
            }
            [data-theme="dark"] .input-group {
                background-color: #374151;
            }
            .input-group:focus-within {
                border-color: var(--color-primary);
            }
            
            .input-icon {
                color: var(--color-text-muted);
                width: 16px;
                height: 16px;
                margin-right: 12px;
                flex-shrink: 0;
            }
            
            .input-field {
                flex: 1;
                background: transparent;
                border: none;
                color: var(--color-text);
                padding: 14px 0;
                font-size: 14px;
                font-weight: 500;
                outline: none;
                width: 100%;
            }
            .input-field::placeholder {
                color: var(--color-text-muted);
                font-weight: 400;
            }

            .btn-submit {
                background: var(--color-primary); /* Azul lleno */
                color: #ffffff;
                border: none;
                padding: 14px 50px;
                border-radius: 9999px;
                font-weight: 700;
                font-size: 13px;
                letter-spacing: 1px;
                text-transform: uppercase;
                cursor: pointer;
                transition: transform 0.1s, opacity 0.2s;
                margin-top: 24px;
                box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
            }
            .btn-submit:hover { opacity: 0.9; }
            .btn-submit:active { transform: scale(0.98); }

            .forgot-link {
                font-size: 12px;
                color: var(--color-text-muted);
                font-weight: 600;
                text-decoration: none;
                margin-top: 14px;
                transition: color 0.2s;
                border-bottom: 1px solid transparent;
            }
            .forgot-link:hover {
                color: var(--color-text);
                border-bottom-color: var(--color-text);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="split-card">
            <div class="split-left">
                <div class="left-brand">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    XStock
                </div>
                
                <h1 class="left-title">¡Hola!</h1>
                <p class="left-text">
                    Si eres un usuario nuevo y requieres credenciales, por favor contacta al área administrativa.
                </p>
                <button type="button" class="btn-outline" onclick="alert('Dirígete a RRHH o TI para obtener acceso al sistema ERP.')">
                    SOY NUEVO
                </button>
            </div>

            <div class="split-right">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
