<?php

namespace App\Http\Controllers;

use App\Models\LogActividad;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $query = LogActividad::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('detalle', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('modulo') && $request->modulo !== 'Todos los módulos') {
            $query->where('modulo', $request->modulo);
        }

        if ($request->filled('accion') && $request->accion !== 'Todas las acciones') {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20);

        return view('historial.index', compact('logs'));
    }

    public function show(LogActividad $logActividad)
    {
        $logActividad->load('user');
        return view('historial.show', compact('logActividad'));
    }
}
