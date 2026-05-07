<aside class="sidebar" id="sidebar">

    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="3 3 18 18" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="19" y2="19" stroke="#2563eb" stroke-width="3.5" />
            <line x1="19" y1="5" x2="14" y2="10" stroke="#10b981" stroke-width="3.5" />
            <line x1="10" y1="14" x2="5" y2="19" stroke="#10b981" stroke-width="3.5" />
        </svg>
        <span style="margin-left: -10px;">Stock</span>
    </a>

    <nav class="sidebar-nav">

        <div class="sidebar-section">
            <div class="sidebar-section-title">Inicio</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Gestión</div>
            @if(auth()->user()->hasPermission('productos.ver'))
            <a href="{{ route('productos.index') }}" class="sidebar-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                Productos
            </a>
            @endif

            @if(auth()->user()->hasPermission('ventas.ver'))
            <a href="{{ route('ventas.index') }}" class="sidebar-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Ventas
            </a>
            @endif

            @if(auth()->user()->hasPermission('proveedores.ver'))
            <a href="{{ route('proveedores.index') }}" class="sidebar-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"/>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                Proveedores
            </a>
            @endif

            @if(auth()->user()->hasPermission('estadisticas.ver'))
            <a href="{{ route('estadisticas.index') }}" class="sidebar-link {{ request()->routeIs('estadisticas.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6"  y1="20" x2="6"  y2="14"/>
                </svg>
                Estadísticas
            </a>
            @endif
        </div>

        @if(auth()->user()->hasPermission('historial.ver') || auth()->user()->hasPermission('usuarios.gestionar') || auth()->user()->hasPermission('roles.gestionar'))
        <div class="sidebar-section">
            <div class="sidebar-section-title">Administración</div>
            
            @if(auth()->user()->hasPermission('historial.ver'))
            <a href="{{ route('historial.index') }}" class="sidebar-link {{ request()->routeIs('historial.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Historial
            </a>
            @endif

            @if(auth()->user()->hasPermission('usuarios.gestionar'))
            <a href="{{ route('usuarios.index') }}" class="sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Usuarios
            </a>
            @endif

            @if(auth()->user()->hasPermission('roles.gestionar'))
            <a href="{{ route('roles.index') }}" class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Roles y Permisos
            </a>
            @endif
        </div>
        @endif

    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" style="display:flex; align-items:center; gap:12px; text-decoration:none; flex:1; overflow:hidden; padding:4px; margin-left:-4px; border-radius:6px; transition:background 0.2s" onmouseover="this.style.background='rgba(0,0,0,0.04)'" onmouseout="this.style.background='transparent'" title="Ajustes de Perfil">
            <div class="user-avatar" style="flex-shrink:0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div style="overflow:hidden; min-width:0; flex:1">
                <div style="font-size:12.5px; font-weight:600; color:var(--color-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis">
                    {{ Auth::user()->name ?? 'Usuario' }}
                </div>
                <div style="font-size:11px; color:var(--color-text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis">
                    {{ Auth::user()->email ?? '' }}
                </div>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" title="Cerrar sesión" style="width:36px; height:36px; border-radius:50%; border:1px solid var(--color-danger); background:transparent; color:var(--color-danger); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; padding:0" onmouseover="this.style.background='var(--color-danger)'; this.style.color='#ffffff'" onmouseout="this.style.background='transparent'; this.style.color='var(--color-danger)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:2px">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>
    </div>

</aside>
