<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function index()
    {
        return Tarea::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'prioridad' => 'required|in:baja,media,alta',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        return Tarea::create($request->all());
    }

    public function show(Tarea $tarea)
    {
        return $tarea;
    }

    public function update(Request $request, Tarea $tarea)
    {
        $tarea->update($request->all());
        $tarea->update($request->only(['titulo', 'descripcion', 'prioridad', 'estado']));
        return $tarea;
    }

    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return response()->json(['message' => 'Tarea eliminada']);
    }
}
