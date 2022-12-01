<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\User;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clases = Classroom::with(['curso','teacher','horarios'])->withCount('students')->orderBy('tipo')->orderBy('ritmo')->orderBy('start')->get();

        return view('admin.classroom.index', [
            'clases'=>$clases,
            'weekdays'=>config('wiseabc.weekdays')
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        $clase = Classroom::with(['curso','teacher','students','horarios'])->find($id);

        $arrHorarios = $clase->teacher->horariosDisponibles();
        $arrHorariosDias = array_keys($arrHorarios);
        $weekdays = config('wiseabc.weekdays');

        foreach( $clase->getHorarioArray() AS $_dia=>$arrHr ) {
            $arrHorarios[( $_dia )] = array_merge( $arrHorarios[( $_dia )], $arrHr );
        }

        return view('admin.classroom.form',[
            'clase'=>$clase,
            'arrHorarios'=>$arrHorarios,
            'arrHorariosDias'=>$arrHorariosDias,
            'weekdays'=>$weekdays
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valid = $request->validate([
            'status' => 'string',
            'tipo' => 'integer',
            'ritmo' => 'integer',
            'start' => 'date',
            'horarios' => 'array'
        ]);

        $clase = Classroom::find($id);

        if ( !empty($valid['status']) ) {
            $clase->status = $valid['status'];
        }

        if ( !empty($valid['tipo']) ) {
            $clase->tipo = $valid['tipo'];
        }

        if ( !empty($valid['ritmo']) ) {
            $clase->ritmo = $valid['ritmo'];
        }

        if ( !empty($valid['start']) ) {
            $clase->start = $valid['start'];
        }

        if ( !$clase->save() ) {
            return $request->wantsJson() ? response()->json(['message'=>'Ocurrio un error al guardar'], 400)
              : back()->withInput()->withErrors(['message'=>'Ocurrio un error al guardar']);
        }

        if ( !empty($valid['horarios']) ) {
            $clase->saveHorarios( $valid['horarios'] );
        }

        return $request->wantsJson() ? response()->json(['message'=>'Exito', 'redirect'=>route('admin.classroom.index') ])
          : redirect()->route('admin.classroom.index')->with('success','Guardado con éxito');
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

    /**
     * Crear un classroom para curso
     *
     * @return \Illuminate\Http\Response
     */
    public function createForCurso(\App\Models\Curso $curso) {
        $weekdays = config('wiseabc.weekdays');
        $profes = User::where('status', 'active')->where('user_type',3)->orderBy('name')->get();
        return view('admin.classroom.cforcurso', [
            'curso'=>$curso,
            'profes'=>$profes,
            'weekdays'=>$weekdays
        ]);
    }

    /**
     * Crear un classroom para profesor
     *
     * @return \Illuminate\Http\Response
     */
    public function createForTeacher(User $teacher) {
        $arrHorarios = $teacher->horariosDisponibles();
        $arrHorariosDias = array_keys($arrHorarios);
        $weekdays = config('wiseabc.weekdays');
        $cursos = Curso::where('status', 'active')->orderBy('edad')->orderBy('nivel')->get();
        return view('admin.classroom.cforteacher', [
            'cursos'=>$cursos,
            'profe'=>$teacher,
            'arrHorarios'=>$arrHorarios,
            'arrHorariosDias'=>$arrHorariosDias,
            'weekdays'=>$weekdays
        ]);
    }

    public function createForTeacher2(Request $request, User $teacher) {
        $valid = $request->validate([
            'teacher_id'=>'required|integer',
            'curso_id'=>'required|integer',
            'tipo'=>'required|integer',
            'ritmo'=>'required|integer',
            'start'=>'required|date',
        ]);
        $horarios = $request->input('horarios');

        $classr = Classroom::create($valid);

        $res2 = $classr->saveHorarios($horarios);

        return $request->wantsJson() ? response()->json(['redirect'=> route('admin.teacher.index'), 'message'=>'Clase creada con éxito', 'classroom'=>$classr, 'res2'=>$res2 ])
            : redirect()->route('admin.teacher.index')->with('success','Clase creada con éxito');
    }
}
