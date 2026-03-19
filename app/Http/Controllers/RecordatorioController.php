<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use Illuminate\Http\Request;

class RecordatorioController extends Controller
{
    public function index(Request $request)
    {
        $query = Recordatorio::where('user_id', auth()->id());

        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('fecha', [$request->start, $request->end]);
        }

        $recordatorios = $query->get();

        $eventos = $recordatorios->map(function ($r) {
            return [
            'id' => $r->id,
            'title' => $r->titulo,
            'start' => $r->fecha->toIso8601String(),
            'backgroundColor' => $r->color,
            'borderColor' => $r->color,
            'extendedProps' => [
            'descripcion' => $r->descripcion,
            'completado' => $r->completado,
            ]
            ];
        });

        return response()->json($eventos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'color' => 'nullable|string|max:20',
        ]);

        $recordatorio = Recordatorio::create([
            'user_id' => auth()->id(),
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'color' => $request->color ?? '#3b82f6',
            'completado' => false,
        ]);

        auth()->user()->notify(new \App\Notifications\GeneralNotification(
            'Recordatorio Programado',
            "Has programado la tarea: {$recordatorio->titulo}",
            'info',
            route('dashboard')
            ));

        return response()->json([
            'success' => true,
            'message' => 'Recordatorio guardado',
            'data' => $recordatorio
        ]);
    }

    public function update(Request $request, Recordatorio $recordatorio)
    {
        if ($recordatorio->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }
        if ($request->has('fecha') && count($request->all()) == 1) {
            $recordatorio->update(['fecha' => $request->fecha]);
            return response()->json(['success' => true]);
        }
        if ($request->has('completado')) {
            $recordatorio->update(['completado' => filter_var($request->completado, FILTER_VALIDATE_BOOLEAN)]);
            return response()->json(['success' => true]);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        $recordatorio->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'color' => $request->color ?? '#3b82f6',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Recordatorio actualizado',
            'data' => $recordatorio
        ]);
    }

    public function destroy(Recordatorio $recordatorio)
    {
        if ($recordatorio->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $recordatorio->delete();

        return response()->json(['success' => true, 'message' => 'Eliminado correctamente']);
    }
}
