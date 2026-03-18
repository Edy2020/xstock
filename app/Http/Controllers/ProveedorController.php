<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\LogActividad;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::withCount('productos');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('ruc', 'like', '%' . $request->buscar . '%')
                  ->orWhere('contacto', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $proveedores = $query->latest()->get();

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasPermission('proveedores.crear'), 403, 'No tienes permiso para crear proveedores.');
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.crear'), 403, 'No tienes permiso para crear proveedores.');

        $request->validate([
            'nombre'    => 'required|string|max:255',
            'ruc'       => 'nullable|string|max:50',
            'contacto'  => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'notas'     => 'nullable|string',
            'estado'    => 'required|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'email.email'     => 'El email no tiene un formato válido.',
        ]);

        Proveedor::create($request->only(
            'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'notas', 'estado'
        ));

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $request->nombre . '" añadido correctamente.');
    }

    public function show(Proveedor $proveedor)
    {
        $productos = $proveedor->productos()->latest()->get();
        return view('proveedores.show', compact('proveedor', 'productos'));
    }

    public function edit(Proveedor $proveedor)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.editar'), 403, 'No tienes permiso para editar proveedores.');
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.editar'), 403, 'No tienes permiso para editar proveedores.');

        $request->validate([
            'nombre'    => 'required|string|max:255',
            'ruc'       => 'nullable|string|max:50',
            'contacto'  => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'notas'     => 'nullable|string',
            'estado'    => 'required|in:activo,inactivo',
        ]);

        $proveedor->update($request->only(
            'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'notas', 'estado'
        ));

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.eliminar'), 403, 'No tienes permiso para eliminar proveedores.');

        // Quita la relación antes de eliminar
        $proveedor->productos()->update(['proveedor_id' => null]);
        $nombre = $proveedor->nombre;
        $proveedor->delete();

        // Registrar Historial
        LogActividad::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación',
            'modulo' => 'Proveedores',
            'detalle' => 'Eliminó al proveedor: ' . $nombre,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor "' . $nombre . '" eliminado.');
    }

    public function import(Request $request)
    {
        abort_unless(auth()->user()->hasPermission('proveedores.crear'), 403, 'No tienes permiso para importar proveedores.');

        $request->validate([
            'archivo_csv' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'archivo_csv.required' => 'Debe seleccionar un archivo CSV.',
            'archivo_csv.mimes'    => 'El archivo debe tener formato .csv o .txt.',
        ]);

        $file = $request->file('archivo_csv');
        $handle = fopen($file->getRealPath(), "r");
        
        // Saltamos la primera fila de cabeceras
        fgetcsv($handle, 1000, ",");
        
        $agregados = 0;
        $omitidos = 0;
        $nombresAgregados = [];

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if (empty(trim($row[0] ?? ''))) continue;

                $nombre = trim($row[0]);
                // Verificamos unicidad de nombre antes de insertar
                if (Proveedor::where('nombre', $nombre)->exists()) {
                    $omitidos++;
                    continue;
                }

                Proveedor::create([
                    'nombre'    => $nombre,
                    'ruc'       => trim($row[1] ?? null),
                    'contacto'  => trim($row[2] ?? null),
                    'email'     => filter_var(trim($row[3] ?? ''), FILTER_VALIDATE_EMAIL) ? trim($row[3]) : null,
                    'telefono'  => trim($row[4] ?? null),
                    'direccion' => trim($row[5] ?? null),
                    'notas'     => trim($row[6] ?? null),
                    'estado'    => in_array(strtolower(trim($row[7] ?? '')), ['activo', 'inactivo']) ? strtolower(trim($row[7])) : 'activo',
                ]);
                $agregados++;
                if (count($nombresAgregados) < 300) {
                    $nombresAgregados[] = "• " . $nombre;
                }
            }
            fclose($handle);
            \Illuminate\Support\Facades\DB::commit();

            $detalleStr = "Se importó masivamente un archivo CSV.\n";
            $detalleStr .= "Total insertados exitosamente: {$agregados}.\n";
            $detalleStr .= "Total omitidos (por nombre duplicado): {$omitidos}.\n\n";
            if ($agregados > 0) {
                $detalleStr .= "Proveedores cargados en esta sesión:\n";
                $detalleStr .= implode("\n", $nombresAgregados);
                if ($agregados > 300) {
                    $detalleStr .= "\n... y " . ($agregados - 300) . " proveedores más.";
                }
            }

            LogActividad::create([
                'user_id' => auth()->id(),
                'accion' => 'Importación CSV',
                'modulo' => 'Proveedores',
                'detalle' => $detalleStr,
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('proveedores.index')
                ->with('success', "Importación completada: {$agregados} proveedores añadidos, {$omitidos} omitidos por nombre duplicado.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            fclose($handle);
            return back()->withErrors(['error' => 'Error estructural al leer CSV. Asegúrese de que tenga el formato correcto: Nombre, RUC/NIF, Contacto, Email, Teléfono, Dirección, Notas, Estado.']);
        }
    }
}
