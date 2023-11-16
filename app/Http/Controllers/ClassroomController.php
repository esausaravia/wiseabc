<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classroom $classroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        //
    }

    public function disponibles(Request $request)
    {

        //$cursos = Curso::where('status',1)->where('edad', $request->input('edad'))->where('nivel', $request->input('nivel') )->get()

        $getEdad = $request->input('edad');
        $getNivel = $request->input('nivel');
        $getHorarios = $request->input('horarios');
        $getHorarios = is_array($getHorarios) ? $getHorarios : null;

        $result = DB::table('classrooms')
            ->join('cursos', 'classrooms.curso_id', '=', 'cursos.id')
            ->join('class_horarios', 'classrooms.id', '=', 'class_horarios.class_id')
            ->select('classrooms.id')
            ->when($getEdad, function ($query, $getEdad) {
                $query->where('cursos.edad', $getEdad);
            })
            ->when($getNivel, function ($query, $getNivel) {
                $query->where('cursos.nivel', $getNivel);
            })
            ->when($getHorarios, function ($query, $getHorarios) {
                $query->whereIn('class_horarios.hr', $getHorarios);
            })
            ->get();
        //select `classrooms`.`id` from `classrooms` inner join `cursos` on `classrooms`.`curso_id` = `cursos`.`id` inner join `class_horarios` on `classrooms`.`id` = `class_horarios`.`class_id` where (`cursos`.`edad` = 1 and `cursos`.`nivel` = 1) and `class_horarios`.`hr` in (9,10,11,12)

        $arrClassId = [];
        foreach ($result as $row) {
            $arrClassId[] = $row->id;
        }

        $clases = \App\Models\Classroom::whereIn('id', $arrClassId)->orderBy('tipo')->orderBy('ritmo')->get();

        return $request->wantsJson() && empty($request->input('html'))
          ? response()->json($clases->toJson())
          : view('ajx.clases-disponibles', [
              'weekdays' => config('wiseabc.weekdays'),
              'clases' => $clases,
              'show_prices' => ! empty($request->input('precios')),
          ]);
    }//END disponibles
}
