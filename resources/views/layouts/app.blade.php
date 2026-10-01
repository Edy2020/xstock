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

        // Escapa texto antes de insertarlo con innerHTML (evita XSS con nombres de productos, recordatorios, etc.)
        window.escapeHtml = function (value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };
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

                <div class="notif-wrapper">
                    <button type="button" id="notif-toggle" class="icon-btn" title="Notificaciones" aria-label="Notificaciones" aria-haspopup="true" aria-expanded="false">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span id="notif-badge" class="notif-badge">0</span>
                    </button>

                    <div id="notif-dropdown" class="notif-dropdown">
                        <div class="notif-header">
                            <span class="notif-header-title">Notificaciones</span>
                            <button type="button" id="notif-clear-all" class="link-btn">Limpiar todas</button>
                        </div>
                        <div id="notif-list" class="notif-list"></div>
                        <div id="notif-empty" class="notif-empty">No tienes notificaciones nuevas.</div>
                    </div>
                </div>

                <button type="button" id="theme-toggle" class="icon-btn" title="Cambiar tema" aria-label="Cambiar tema">
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
            <x-flash />
            {{ $slot }}
        </div>

    </div>
</div>

{{-- Modal de confirmación: reemplaza a confirm() en formularios con data-confirm="..." --}}
<dialog id="confirm-modal" class="confirm-modal" aria-labelledby="confirm-modal-title">
    <div class="confirm-modal-body">
        <div class="confirm-modal-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
            <h3 id="confirm-modal-title" class="confirm-modal-title">¿Estás seguro?</h3>
            <p id="confirm-modal-text" class="confirm-modal-text"></p>
        </div>
    </div>
    <div class="confirm-modal-actions">
        <button type="button" class="btn btn-secondary" id="confirm-modal-cancel">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirm-modal-accept">Confirmar</button>
    </div>
</dialog>

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

    // ---- Mensajes flash ----
    function hideFlash(el) {
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 300);
    }
    document.querySelectorAll('.flash-alert').forEach(function(el) {
        el.querySelector('.flash-alert-close').addEventListener('click', () => hideFlash(el));
        if (el.dataset.autohide === 'true') setTimeout(() => hideFlash(el), 4000);
    });

    // ---- Modal de confirmación ----
    (function () {
        const modal = document.getElementById('confirm-modal');
        const text = document.getElementById('confirm-modal-text');
        const acceptBtn = document.getElementById('confirm-modal-accept');
        let resolver = null;

        // Uso: confirmDialog('¿Eliminar?', 'Eliminar').then(ok => { if (ok) ... })
        window.confirmDialog = function (message, buttonLabel) {
            text.textContent = message;
            acceptBtn.textContent = buttonLabel || 'Confirmar';
            modal.showModal();
            acceptBtn.focus();
            return new Promise(resolve => { resolver = resolve; });
        };

        function finish(result) {
            if (resolver) { resolver(result); resolver = null; }
            if (modal.open) modal.close();
        }

        acceptBtn.addEventListener('click', () => finish(true));
        document.getElementById('confirm-modal-cancel').addEventListener('click', () => finish(false));
        modal.addEventListener('close', () => finish(false));

        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form.matches('form[data-confirm]')) return;
            e.preventDefault();
            confirmDialog(form.dataset.confirm, form.dataset.confirmButton).then(ok => {
                if (ok) form.submit();
            });
        });
    })();

    // ---- Notificaciones ----
    const notifToggle = document.getElementById('notif-toggle');
    const notifDropdown = document.getElementById('notif-dropdown');
    const notifBadge = document.getElementById('notif-badge');
    const notifList = document.getElementById('notif-list');
    const notifEmpty = document.getElementById('notif-empty');
    const notifClearAll = document.getElementById('notif-clear-all');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (notifToggle) {
        function setNotifOpen(open) {
            notifDropdown.classList.toggle('open', open);
            notifToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        notifToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const willOpen = !notifDropdown.classList.contains('open');
            setNotifOpen(willOpen);
            if (willOpen) loadNotifications();
        });

        document.addEventListener('click', function(e) {
            if (!notifToggle.contains(e.target) && !notifDropdown.contains(e.target)) {
                setNotifOpen(false);
            }
        });

        function loadNotifications() {
            fetch('{{ route('notificaciones.unread') }}', { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderNotifications(data.notifications, data.count);
                    }
                })
                .catch(err => console.error('Error al cargar notificaciones:', err));
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

            const iconMap = { 'success': '🟢', 'danger': '🔴', 'warning': '🟠', 'info': '🔵' };

            notifList.innerHTML = '';
            notifications.forEach(n => {
                const data = n.data || {};

                const item = document.createElement('div');
                item.className = 'notif-item';
                item.innerHTML = `
                    <div class="notif-item-icon">${iconMap[data.tipo] || '⚪'}</div>
                    <div class="notif-item-body">
                        <div class="notif-item-title">${escapeHtml(data.titulo || 'Notificación')}</div>
                        <div class="notif-item-text">${escapeHtml(data.mensaje)}</div>
                    </div>
                    <button type="button" class="notif-item-close" title="Eliminar notificación" aria-label="Eliminar notificación">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                `;

                item.querySelector('.notif-item-close').addEventListener('click', (e) => {
                    e.stopPropagation();
                    markAsRead(n.id);
                });

                item.addEventListener('click', () => {
                    if (data.url && data.url !== '#') {
                        window.location.href = data.url;
                    }
                });

                notifList.appendChild(item);
            });
        }

        function markAsRead(id) {
            fetch(`{{ url('notificaciones') }}/${encodeURIComponent(id)}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            }).then(() => loadNotifications());
        }

        notifClearAll.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('{{ route('notificaciones.clearAll') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            }).then(() => loadNotifications());
        });

        loadNotifications();
        setInterval(loadNotifications, 60000);
    }
</script>

</body>
</html>
