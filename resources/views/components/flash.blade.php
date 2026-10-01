{{-- Mensajes flash globales: se muestran en todas las páginas desde el layout principal --}}
@foreach (['success' => 'alert-success', 'error' => 'alert-danger'] as $key => $class)
    @if (session($key))
        <div class="alert {{ $class }} flash-alert" role="{{ $key === 'error' ? 'alert' : 'status' }}" data-autohide="{{ $key === 'success' ? 'true' : 'false' }}">
            <div class="flash-alert-content">
                @if ($key === 'success')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                @endif
                <span>{{ session($key) }}</span>
            </div>
            <button type="button" class="flash-alert-close" aria-label="Cerrar mensaje">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    @endif
@endforeach
