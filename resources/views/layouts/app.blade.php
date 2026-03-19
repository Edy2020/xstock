<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Dashboard' }} — XStock</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/xstock.css') }}">

    <script>
        const savedTheme = localStorage.getItem('xstock-theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="layout-wrapper">

    @include('layouts.sidebar')

    <div class="main-content">

        <div class="topbar">
            <div class="topbar-left">
                <button class="btn-hamburger" onclick="openSidebar()" aria-label="Abrir menú">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6"  x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <span class="topbar-title">{{ $pageTitle ?? 'Dashboard' }}</span>
            </div>

            <div class="topbar-actions" style="display:flex; align-items:center; gap:12px; position:relative">
                
                <div class="dropdown-notifications" style="position:relative">
                    <button type="button" id="notif-toggle" title="Notificaciones" style="width:36px; height:36px; border-radius:50%; border:none; background:var(--color-bg); color:var(--color-text-muted); display:flex; align-items:center; justify-content:center; cursor:pointer; position:relative; transition:all 0.2s" onmouseover="this.style.color='var(--color-text)'" onmouseout="this.style.color='var(--color-text-muted)'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span id="notif-badge" style="position:absolute; top:2px; right:2px; background:var(--color-danger); color:white; font-size:10px; font-weight:700; width:16px; height:16px; border-radius:50%; display:none; align-items:center; justify-content:center; border:2px solid var(--color-surface); box-sizing:content-box">0</span>
                    </button>

                    <div id="notif-dropdown" style="display:none; position:absolute; top:45px; right:0; width:340px; background:var(--color-surface); border:1px solid var(--color-border); box-shadow:0 10px 25px rgba(0,0,0,0.1); border-radius:8px; z-index:100; flex-direction:column; overflow:hidden;">
                        <div style="padding:12px 16px; border-bottom:1px solid var(--color-border); display:flex; justify-content:space-between; align-items:center; background:var(--color-bg)">
                            <span style="font-size:14px; font-weight:600; color:var(--color-text)">Notificaciones</span>
                            <button id="notif-clear-all" style="font-size:12px; color:var(--color-primary); background:none; border:none; cursor:pointer; padding:0; font-weight:600;">Limpiar todas</button>
                        </div>
                        <div id="notif-list" style="max-height:350px; overflow-y:auto; display:flex; flex-direction:column;">
                        </div>
                        <div id="notif-empty" style="padding:30px 20px; text-align:center; color:var(--color-text-muted); font-size:13px; display:none;">
                            No tienes notificaciones nuevas.
                        </div>
                    </div>
                </div>

                <button type="button" id="theme-toggle" title="Cambiar tema" style="width:36px; height:36px; border-radius:50%; border:none; background:var(--color-bg); color:var(--color-text-muted); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s" onmouseover="this.style.color='var(--color-text)'" onmouseout="this.style.color='var(--color-text-muted)'">
                    <svg id="theme-icon-light" style="display:none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                    </svg>
                    <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="page-content">
            {{ $slot }}
        </div>

    </div>
</div>

<script>
    const themeToggleBtn = document.getElementById('theme-toggle');
    const iconLight = document.getElementById('theme-icon-light');
    const iconDark = document.getElementById('theme-icon-dark');
    
    function updateThemeUI(theme) {
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            iconDark.style.display = 'none';
            iconLight.style.display = 'block';
        } else {
            document.documentElement.removeAttribute('data-theme');
            iconDark.style.display = 'block';
            iconLight.style.display = 'none';
        }
    }

    updateThemeUI(localStorage.getItem('xstock-theme') || 'light');

    themeToggleBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        localStorage.setItem('xstock-theme', newTheme);
        updateThemeUI(newTheme);
    });

    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
    document.querySelectorAll('.sidebar-link').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    const notifToggle = document.getElementById('notif-toggle');
    const notifDropdown = document.getElementById('notif-dropdown');
    const notifBadge = document.getElementById('notif-badge');
    const notifList = document.getElementById('notif-list');
    const notifEmpty = document.getElementById('notif-empty');
    const notifClearAll = document.getElementById('notif-clear-all');

    if(notifToggle) {
        notifToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = notifDropdown.style.display === 'flex';
            notifDropdown.style.display = isVisible ? 'none' : 'flex';
            if (!isVisible) loadNotifications();
        });

        document.addEventListener('click', function(e) {
            if (!notifToggle.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.style.display = 'none';
            }
        });

        function loadNotifications() {
            fetch('/notificaciones/unread')
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        renderNotifications(data.notifications, data.count);
                    }
                })
                .catch(err => console.error('Error fetching notificaciones:', err));
        }

        function renderNotifications(notifications, count) {
            if (count > 0) {
                notifBadge.style.display = 'flex';
                notifBadge.innerText = count > 9 ? '9+' : count;
                notifEmpty.style.display = 'none';
            } else {
                notifBadge.style.display = 'none';
                notifEmpty.style.display = 'block';
            }

            notifList.innerHTML = '';
            notifications.forEach(n => {
                const data = n.data || {};
                const iconMap = {
                    'success': '🟢', 'danger': '🔴', 'warning': '🟠', 'info': '🔵'
                };
                const icon = iconMap[data.tipo] || '⚪';

                const item = document.createElement('div');
                item.style.cssText = 'padding:12px 16px; border-bottom:1px solid var(--color-border); display:flex; gap:12px; align-items:flex-start; cursor:pointer; transition:background 0.2s; position:relative';
                item.onmouseover = () => item.style.background = 'var(--color-bg)';
                item.onmouseout = () => item.style.background = 'transparent';
                
                const delBtn = document.createElement('button');
                delBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                delBtn.style.cssText = 'position:absolute; right:12px; top:12px; background:none; border:none; color:var(--color-text-muted); cursor:pointer; padding:4px';
                delBtn.title = "Eliminar notificación";
                delBtn.onclick = (e) => {
                    e.stopPropagation();
                    markAsRead(n.id);
                };

                item.onclick = () => {
                    if (data.url && data.url !== '#') {
                        window.location.href = data.url;
                    }
                };

                item.innerHTML = `
                    <div style="font-size:16px; margin-top:2px">${icon}</div>
                    <div style="flex:1; padding-right:24px;">
                        <div style="font-size:13px; font-weight:600; color:var(--color-text); margin-bottom:4px">${data.titulo || 'Notificación'}</div>
                        <div style="font-size:12px; color:var(--color-text-muted); line-height:1.4">${data.mensaje || ''}</div>
                    </div>
                `;
                item.appendChild(delBtn);
                notifList.appendChild(item);
            });
        }

        function markAsRead(id) {
            fetch(`/notificaciones/${id}/read`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
            }).then(() => loadNotifications());
        }

        notifClearAll.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('/notificaciones/clear-all', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
            }).then(() => loadNotifications());
        });

        loadNotifications();
        setInterval(loadNotifications, 60000);
    }
</script>

</body>
</html>
