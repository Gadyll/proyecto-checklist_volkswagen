<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsesorController extends Controller
{
    public function index()
    {
        $asesores = Asesor::latest()->paginate(10);
        return view('asesores.index', compact('asesores'));
    }

    public function create()
    {
        return view('asesores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'correo' => 'nullable|email|max:150',
            'telefono' => 'nullable|string|max:20',
            'fecha_registro' => 'nullable|date',
        ]);

        Asesor::create($data);

        return redirect()->route('asesores.index')->with('ok', 'Asesor registrado correctamente.');
    }

    public function edit(Asesor $asesor)
    {
        return view('asesores.edit', compact('asesor'));
    }

    public function update(Request $request, Asesor $asesor)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'correo' => ['nullable', 'email', 'max:150', Rule::unique('asesores', 'correo')->ignore($asesor->id)],
            'telefono' => 'nullable|string|max:20',
            'fecha_registro' => 'nullable|date',
        ]);

        $asesor->update($data);

        return redirect()->route('asesores.index')->with('ok', 'Asesor actualizado correctamente.');
    }

    public function destroy(Asesor $asesor)
    {
        if ($asesor->ordenes()->count() > 0) {
            return back()->withErrors('No se puede eliminar un asesor con órdenes registradas.');
        }

        $asesor->delete();
        return redirect()->route('asesores.index')->with('ok', 'Asesor eliminado correctamente.');
    }
}


