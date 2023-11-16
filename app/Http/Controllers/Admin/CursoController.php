<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //
        $nivel = $request->get('nivel');
        $edad = $request->get('edad');
        $cursos = Curso::query()
            ->when($nivel, function ($query) use ($nivel) {
                $query->where('nivel', $nivel);
            })
            ->when($edad, function ($query) use ($edad) {
                $query->where('edad', $edad);
            })
            ->orderBy('nivel')
            ->orderBy('edad')
            ->get();

        return view('admin.cursos', compact('cursos', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Curso $curso): View
    {
        return view('admin.curso', [
            'title' => 'Nuevo',
            'form_action' => route('admin.cursos.store'),
            'curso' => $curso,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'edad' => 'required|integer',
            'nivel' => 'required|integer',
            'duracion' => 'required|integer',
        ]);

        $curso = Curso::create([
            'name' => $valid['name'],
            'status' => $valid['status'],
            'edad' => $valid['edad'],
            'nivel' => $valid['nivel'],
            'duracion' => $valid['duracion'],
        ]);

        return redirect()->route('admin.cursos.index')->with('success', $valid['name'].' creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(Curso $curso): View
    {
        return view('admin.curso', [
            'title' => 'Editar',
            'form_action' => route('admin.cursos.update', ['curso' => $curso]),
            'curso' => $curso,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, Curso $curso): RedirectResponse
    {
        $valid = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'edad' => 'required|integer',
            'nivel' => 'required|integer',
            'duracion' => 'required|integer',
        ]);

        $curso->update([
            'name' => $valid['name'],
            'status' => $valid['status'],
            'edad' => $valid['edad'],
            'nivel' => $valid['nivel'],
            'duracion' => $valid['duracion'],
        ]);

        return redirect()->route('admin.cursos.index')->with('success', $valid['name'].' actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
