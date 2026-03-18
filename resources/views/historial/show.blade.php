<x-app-layout :pageTitle="'Detalle de Actividad'">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Detalle de Registro #{{ str_pad($logActividad->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p>Consulta a fondo la bitácora de esta acción ejecutada en el sistema.</p>
        </div>
        <div style="display:flex; gap:8px">
            <a href="{{ route('historial.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Volver al Historial
            </a>
        </div>
    </div>

    <div class="card" style="max-width:800px; margin:0 auto">
        <h3 style="margin-bottom:20px; padding-bottom:10px; border-bottom:1px solid var(--color-border); font-size:16px; display:flex; justify-content:space-between">
            <span><span class="badge {{ $logActividad->badgeClass }}">{{ $logActividad->accion }}</span> en el Módulo {{ $logActividad->modulo }}</span>
            <span style="color:var(--color-text-muted); font-size:14px">{{ $logActividad->created_at->format('d M Y, h:i A') }}</span>
        </h3>
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; padding-bottom:20px">
            <div>
                <p style="font-size:12px; color:var(--color-text-muted); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px">Autor / Usuario</p>
                <div style="display:flex; align-items:center; gap:10px">
                    <div style="width:32px; height:32px; border-radius:50%; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0">
                        {{ $logActividad->user ? strtoupper(substr($logActividad->user->name, 0, 1)) : 'S' }}
                    </div>
                    <div>
                        <p style="font-weight:600; margin:0">{{ $logActividad->user ? $logActividad->user->name : 'Sistema / Invitado' }}</p>
                        <p style="font-size:12px; color:var(--color-text-muted); margin:0">{{ $logActividad->user ? $logActividad->user->email : 'N/A' }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <p style="font-size:12px; color:var(--color-text-muted); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px">Dirección IP de Origen</p>
                <p style="font-family:monospace; margin:0; padding:8px; background:var(--color-bg); border-radius:6px; border:1px solid var(--color-border); display:inline-block">
                    {{ $logActividad->ip_address ?? 'Desconocida' }}
                </p>
            </div>
        </div>

        <div>
            <p style="font-size:12px; color:var(--color-text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px">Resumen del Evento</p>
            <div style="background:var(--color-bg); border-left:3px solid var(--color-primary); padding:15px; border-radius:4px; font-size:14px; line-height:1.6; white-space:pre-wrap; word-break:break-word">
                {!! nl2br(e($logActividad->detalle)) !!}
            </div>
            @if(str_contains(strtolower($logActividad->detalle), 'elimin'))
                <p style="font-size:12px; color:#ef4444; margin-top:8px; display:flex; gap:4px; align-items:center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Nota: Los registros eliminados no se pueden recuperar mediante la interfaz del sistema.
                </p>
            @endif
        </div>
    </div>
</x-app-layout>
