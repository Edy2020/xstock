<x-app-layout :pageTitle="'Roles y Permisos'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Roles y Permisos</h1>
            <p>Define qué puede hacer cada rol en el sistema</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Rol
        </a>
    </div>

    @if(session('error'))
        <div id="error-alert" class="alert alert-danger" style="margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; transition:opacity 0.3s ease">
            <div style="display:flex; align-items:center; gap:10px">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('error-alert').style.opacity='0'; setTimeout(()=>document.getElementById('error-alert').remove(), 300)" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; display:flex; align-items:center; opacity:0.7">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const alertEl = document.getElementById('error-alert');
                if (alertEl) {
                    alertEl.style.opacity = '0';
                    setTimeout(() => alertEl.remove(), 300);
                }
            }, 5000);
        </script>
    @endif

    @if(session('success'))
        <div id="success-alert" class="alert alert-success" style="margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; transition:opacity 0.3s ease">
            <div style="display:flex; align-items:center; gap:10px">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('success-alert').style.opacity='0'; setTimeout(()=>document.getElementById('success-alert').remove(), 300)" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; display:flex; align-items:center; opacity:0.7">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const alertEl = document.getElementById('success-alert');
                if (alertEl) {
                    alertEl.style.opacity = '0';
                    setTimeout(() => alertEl.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    <div class="grid-3" style="margin-bottom:28px">
        @php
            $colors = [
                1 => ['color'=>'#2563eb', 'bg'=>'#eff6ff'], // Admin
                2 => ['color'=>'#16a34a', 'bg'=>'#f0fdf4'], // Vendedor
                3 => ['color'=>'#d97706', 'bg'=>'#fffbeb'], // Almacén
            ];
        @endphp
        @foreach($roles as $r)
        @php 
            $c = $colors[$r->id] ?? ['color'=>'#64748b', 'bg'=>'#f8fafc'];
            $qtyPermisos = is_array($r->permisos) ? count($r->permisos) : 0;
        @endphp
        <a href="{{ route('roles.show', $r) }}" style="text-decoration:none; color:inherit; display:block;">
            <div class="card role-card-hover" style="display:flex; flex-direction:column; gap:10px; height:100%;">
                <div style="display:flex; align-items:center; justify-content:space-between">
                    <div style="display:flex; align-items:center; gap:10px">
                        <div style="width:34px; height:34px; border-radius:8px; background:{{ $c['bg'] }}; display:flex; align-items:center; justify-content:center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $c['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <span style="font-size:14px; font-weight:700">{{ $r->nombre }}</span>
                    </div>
                    <!-- Acciones -->
                    <div style="display:flex; gap:4px">
                        @if($r->id != 1)
                        <form action="{{ route('roles.destroy', $r) }}" method="POST" style="margin:0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.');">
                            @csrf @method('DELETE')
                            <!-- preventDefault to stop the anchor click when clicking delete -->
                            <button type="submit" onclick="event.preventDefault(); if(confirm('¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.')) { this.closest('form').submit(); }" style="color:var(--color-danger); padding:4px; border:none; background:none; cursor:pointer; opacity:0.7; transition:opacity 0.2s" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'" title="Eliminar Rol">
                                <svg width="15" height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <p style="font-size:13px; color:var(--color-text-muted); margin:0; line-height:1.5">{{ $r->descripcion }}</p>
                <div style="display:flex; gap:12px; font-size:12px; color:var(--color-text-muted); border-top:1px solid var(--color-border); padding-top:10px; margin-top:auto">
                    <span>👥 {{ $r->users_count }} usuario(s)</span>
                    <span>🔑 {{ $qtyPermisos }} permiso(s)</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</x-app-layout>

<style>
    .role-card-hover {
        transition: background-color 0.2s ease;
    }
    .role-card-hover:hover {
        background-color: #f8fafc; /* Color en modo claro */
    }
    [data-theme="dark"] .role-card-hover:hover {
        background-color: #272f3d; /* Color en modo oscuro */
    }
</style>
