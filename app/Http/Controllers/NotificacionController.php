<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Devuelve las notificaciones no leídas del usuario (JSON para AJAX)
     */
    public function unread(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
        }

        // Revisar recordatorios pendientes (15 minutos antes o ya vencidos)
        $reminders = \App\Models\Recordatorio::where('user_id', $user->id)
            ->where('completado', false)
            ->where('notificado', false)
            ->where('fecha', '<=', now()->addMinutes(15))
            ->get();

        foreach ($reminders as $rem) {
            $rem->update(['notificado' => true]);
            $user->notify(new \App\Notifications\GeneralNotification(
                '¡Recordatorio Pendiente!',
                "Tienes una tarea programada para pronto: {$rem->titulo}",
                'warning',
                route('dashboard')
            ));
        }

        // Obtener notificaciones no leídas ordenadas desde la más reciente
        $notifications = $user->unreadNotifications()->get();

        return response()->json([
            'success' => true,
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }

    /**
     * Marca una notificación específica como leída
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notificación no encontrada'], 404);
    }

    /**
     * Elimina (o marca como leídas) todas las notificaciones
     */
    public function clearAll(Request $request)
    {
        // Podríamos simplemente marcarlas como leídas o eliminarlas de la BD
        $request->user()->notifications()->delete(); // Eliminar por completo
        
        return response()->json(['success' => true]);
    }
}
