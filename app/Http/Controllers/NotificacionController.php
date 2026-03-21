<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function unread(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
        }

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

        $notifications = $user->unreadNotifications()->get();

        return response()->json([
            'success' => true,
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }

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

    public function clearAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return response()->json(['success' => true]);
    }
}
