<x-app-layout :pageTitle="'Usuarios'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Usuarios</h1>
            <p>Gestión de usuarios del sistema</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openCreateUser()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nuevo Usuario
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background:#fef2f2; color:#dc2626; padding:12px; border-radius:6px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Búsqueda Dinámica --}}
    <form method="GET" action="{{ route('usuarios.index') }}" id="form-filters" style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar usuario..." onblur="document.getElementById('form-filters').submit()">
        </div>
        <select name="role_id" class="form-select" style="width:auto; padding:7px 11px" onchange="document.getElementById('form-filters').submit()">
            <option value="">Todos los roles</option>
            @foreach($roles as $r)
                <option value="{{ $r->id }}" {{ request('role_id') == $r->id ? 'selected' : '' }}>{{ $r->nombre }}</option>
            @endforeach
        </select>
        <select name="estado" class="form-select" style="width:auto; padding:7px 11px" onchange="document.getElementById('form-filters').submit()">
            <option value="">Todos los estados</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
        @if(request()->anyFilled(['search', 'role_id', 'estado']))
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
        @endif
    </form>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Último acceso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px">
                            <div style="width:32px; height:32px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0">
                                {{ strtoupper(substr($u->name,0,1)) }}
                            </div>
                            <span style="font-weight:500">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--color-text-muted)">{{ $u->email }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $u->role->nombre ?? 'Sin Rol' }}</span>
                    </td>
                    <td style="color:var(--color-text-muted); font-size:12px">{{ $u->last_login_at ? $u->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</td>
                    <td><span class="badge {{ $u->estado === 'activo' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($u->estado) }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px">
                            <button class="btn btn-secondary btn-sm" onclick="editUser({{ $u->toJson() }})">Editar</button>
                            @if(auth()->id() !== $u->id)
                            <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar este usuario?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:20px; color:gray">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($usuarios->hasPages())
    <div class="pagination" style="margin-top:20px">
        <span style="color:var(--color-text-muted); font-size:12px; margin-right:8px">Mostrando {{ $usuarios->firstItem() }} de {{ $usuarios->total() }}</span>
        @if($usuarios->onFirstPage())
            <button class="pg-btn" disabled>‹</button>
        @else
            <a href="{{ $usuarios->previousPageUrl() }}" class="pg-btn">‹</a>
        @endif
        
        <button class="pg-btn active">{{ $usuarios->currentPage() }}</button>
        
        @if($usuarios->hasMorePages())
            <a href="{{ $usuarios->nextPageUrl() }}" class="pg-btn">›</a>
        @else
            <button class="pg-btn" disabled>›</button>
        @endif
    </div>
    @endif

    {{-- Modal Usuarios (CRUD genérico Html5) --}}
    <dialog id="userModal" style="padding:0; border:none; border-radius:8px; box-shadow:0 4px 20px rgba(0,0,0,0.15); max-width:450px; width:100%;">
        <form method="POST" id="userForm" style="display:flex; flex-direction:column; margin:0;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div style="padding:20px; border-bottom:1px solid #eee;">
                <h3 style="margin:0; font-size:16px;" id="modalTitle">Nuevo Usuario</h3>
            </div>
            <div style="padding:20px; display:flex; flex-direction:column; gap:16px;">
                <div>
                    <label style="font-size:12px; font-weight:600; margin-bottom:4px; display:block">Nombre <span style="color:red">*</span></label>
                    <input type="text" name="name" id="userName" class="form-input" required>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600; margin-bottom:4px; display:block">Email <span style="color:red">*</span></label>
                    <input type="email" name="email" id="userEmail" class="form-input" required>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600; margin-bottom:4px; display:block">Contraseña <span id="passReq" style="color:red">*</span></label>
                    <input type="password" name="password" id="userPass" class="form-input" required autocomplete="new-password">
                    <small style="color:gray; font-size:11px; display:none" id="passHint">Déjalo en blanco para mantener la actual.</small>
                </div>
                <div style="display:flex; gap:10px;">
                    <div style="flex:1">
                        <label style="font-size:12px; font-weight:600; margin-bottom:4px; display:block">Rol <span style="color:red">*</span></label>
                        <select name="role_id" id="userRole" class="form-select" required>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1">
                        <label style="font-size:12px; font-weight:600; margin-bottom:4px; display:block">Estado <span style="color:red">*</span></label>
                        <select name="estado" id="userEstado" class="form-select" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div style="padding:16px 20px; border-top:1px solid #eee; display:flex; justify-content:flex-end; gap:10px; background:#fafafa;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('userModal').close()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </dialog>

    <script>
        function openCreateUser() {
            document.getElementById('userForm').action = "{{ route('usuarios.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('modalTitle').textContent = "Nuevo Usuario";
            document.getElementById('userName').value = "";
            document.getElementById('userEmail').value = "";
            document.getElementById('userPass').value = "";
            document.getElementById('userPass').required = true;
            document.getElementById('passReq').style.display = "inline";
            document.getElementById('passHint').style.display = "none";
            document.getElementById('userRole').value = "";
            document.getElementById('userEstado').value = "activo";
            document.getElementById('userModal').showModal();
        }

        function editUser(user) {
            document.getElementById('userForm').action = "/usuarios/" + user.id;
            document.getElementById('formMethod').value = "PUT";
            document.getElementById('modalTitle').textContent = "Editar Usuario: " + user.name;
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userPass').value = "";
            document.getElementById('userPass').required = false;
            document.getElementById('passReq').style.display = "none";
            document.getElementById('passHint').style.display = "block";
            document.getElementById('userRole').value = user.role_id;
            document.getElementById('userEstado').value = user.estado;
            document.getElementById('userModal').showModal();
        }
    </script>

</x-app-layout>
