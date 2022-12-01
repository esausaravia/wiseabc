<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.cursos', [
			'cursos' => Curso::orderBy('status')->orderBy('edad')->orderBy('nivel')->get()
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Curso $curso)
    {
		return view('admin.curso', [
            'title' => 'Nuevo',
            'form_action' => route('admin.cursos.store'),
            'curso'=>$curso
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'edad' => 'required:integer',
            'nivel' => 'required:integer',
            'duracion' => 'required:integer'
        ]);

        $curso = Curso::create([
            'name' => $valid['name'],
            'status' => $valid['status'],
            'edad' => $valid['edad'],
            'nivel' => $valid['nivel'],
            'duracion' => $valid['duracion']
        ]);

        return redirect()->route('admin.cursos.index')->with('success', $valid['name'].' creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Curso $curso)
    {
		return view('admin.curso', [
            'title' => 'Editar',
            'form_action' => route('admin.cursos.update', ['curso'=> $curso]),
            'curso'=>$curso
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curso $curso)
    {
        $valid = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'edad' => 'required:integer',
            'nivel' => 'required:integer',
            'duracion' => 'required:integer'
        ]);

        $curso->update([
            'name' => $valid['name'],
            'status' => $valid['status'],
            'edad' => $valid['edad'],
            'nivel' => $valid['nivel'],
            'duracion' => $valid['duracion']
        ]);

        return redirect()->route('admin.cursos.index')->with('success', $valid['name'].' actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
