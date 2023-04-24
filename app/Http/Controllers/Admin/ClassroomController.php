<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $getEdad = $request->input('edad');
        $getNivel = $request->input('nivel');
        $getTipo = $request->input('tipo');
        $getRitmo = $request->input('ritmo');
        $getCursos = $request->input('cursos');
        $getSeachFor = $request->input('searchfor');

        $clases = Classroom::query()->join('cursos', 'cursos.id', '=', 'classrooms.curso_id')
            ->when( $getEdad, function($query, $getEdad) {
                $query->where('cursos.edad', $getEdad);
            })->when( $getNivel, function($query, $getNivel) {
                $query->where('cursos.nivel', $getNivel);
            })->when( $getTipo, function($query, $getTipo) {
                $query->where('classrooms.tipo', $getTipo);
            })->when( $getRitmo, function($query, $getRitmo) {
                $query->where('classrooms.ritmo', $getRitmo);
            })->when( $getCursos, function($query, $getCursos) {
                $query->where('classrooms.curso_id', $getCursos);
            })->when($getSeachFor, function($query, $getSeachFor) {
            $query->join('users', 'users.id', '=', 'classrooms.teacher_id')
            ->where('users.user_type', 3)
            ->where('users.name', 'like', '%'.$getSeachFor.'%')
            ->orWhere('users.email', 'like', '%'.$getSeachFor.'%');

            })->with(['curso','teacher','horarios'])->withCount('students')->orderBy('tipo')->orderBy('ritmo')->orderBy('start')->get();

        $getCursos = Curso::all();
        $cursos = [];
        foreach ($getCursos as $curso) {
            $cursos[$curso->id] = $curso->name;
        }

        $weekdays[] = config('wiseabc.weekdays');
        return view('admin.classroom.index', compact('clases', 'weekdays','request' ,'cursos'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $valid = $request->validate([
            'teacher_id'=>'required|integer',
            'curso_id'=>'required|integer',
            'tipo'=>'required|integer',
            'ritmo'=>'required|integer',
            'start'=>'required|date',
        ]);
        $horarios = $request->input('horarios');

        $classroom = Classroom::create($valid);

        $res2 = $classroom->saveHorarios($horarios);

        return $request->wantsJson() ? response()->json(['message'=>'Clase creada con éxito', 'classroom'=>$classroom, 'res2'=>$res2 ])
            : redirect()->route('admin.classroom.assignStudents', $classroom->id)->with('success','Clase creada con éxito');
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
              : back()->withInput()->withErrors(['alert'=>'Ocurrio un error al guardar']);
        }

        if ( !empty($valid['horarios']) ) {
            $clase->saveHorarios( $valid['horarios'] );
        }

        return $request->wantsJson() ? response()->json(['message'=>'Clase actualizada con éxito' ])
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
        $BillPlans = \App\Models\BillingPlan::where('status','ACTIVE')->get();
        $profes = User::where('user_type',3)->where('status', 'active')->orderBy('name')->get();

        ob_start();
        $profes = $profes->filter(function($profe){
            $disp = $profe->horariosDisponibles();
            return !empty($disp);
        });

        $curso->alumnosSinClase();
        $arrStudentsToJS = collect();
        foreach($curso->alumnos_sin_clase AS $student) {
            $obj = [
                'id'=>$student->id,
                'tipo'=>$student->clase_tipo,
                'ritmo'=>$student->ritmo,
                'horarios'=>$student->getHorarioArray(1)
            ];
            $arrStudentsToJS->push($obj);
        }

        $debug = ob_get_clean();
        //dd($debug);
        return view('admin.classroom.cforcurso', [
            'curso'=>$curso,
            'profes'=>$profes,
            'weekdays'=>$weekdays,
            'ritmo_labels'=>config('wiseabc.ritmo_labels'),
            'BillPlans'=>$BillPlans,
            'arrStudentsToJS'=> $arrStudentsToJS
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

    public function assignStudents($id) {
        ob_start();
        $Classroom = Classroom::find($id);
        $weekdays = config('wiseabc.weekdays');

        $querySinClase = DB::table('users')
            ->leftJoin('class_student','class_student.user_id','=','users.id')
            ->select('users.id')
            ->where('users.user_type','=',2)
            ->whereNull('class_student.class_id');
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $querySinClase->toSql()), $querySinClase->getBindings()); dd($sql);

        $edad = $Classroom->curso->edad;
        $nivel = $Classroom->curso->nivel;
        $clase_tipo = $Classroom->tipo;
        $ritmo = $Classroom->ritmo;

        $arrClassHoras = collect($Classroom->getHorarioArray())->flatten()->all();

        $usersQuery = DB::table('usermetas')
            ->join('usermetas AS um2', function($join) use ($edad){
                $join->on('usermetas.user_id','=','um2.user_id')
                ->where('um2.metakey','=','edad')
                ->where('um2.metaval','=',$edad);
            })
            ->join('usermetas AS um3', function($join) use ($clase_tipo){
                $join->on('usermetas.user_id','=','um3.user_id')
                ->where('um3.metakey','=','clase_tipo')
                ->where('um3.metaval','=',$clase_tipo);
            })
            ->join('usermetas AS um4', function($join) use ($ritmo){
                $join->on('usermetas.user_id','=','um4.user_id')
                ->where('um4.metakey','=','ritmo')
                ->where('um4.metaval','=',$ritmo);
            })
            ->join('user_horarios', function($join) use ($arrClassHoras) {
                $join->on('usermetas.user_id','=','user_horarios.user_id')
                ->whereIn('user_horarios.hr', $arrClassHoras);
            })
            ->joinSub($querySinClase, 'sinclases', function($join) {
                $join->on('usermetas.user_id','=','sinclases.id');
            })
            ->select('usermetas.user_id')
            ->where('usermetas.metakey','=','nivel')
            ->where('usermetas.metaval','=',$nivel);
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $usersQuery->toSql()), $usersQuery->getBindings()); dd($sql);
        $userResult = $usersQuery->get();
        $StudentSinClase = \App\Models\User::whereIn('id', $userResult->pluck('user_id')->all())->get();

        $debug = ob_get_clean();
        //dd($debug);
        return view('admin.classroom.assign-students', [
            'weekdays'=>$weekdays,
            'Classroom'=>$Classroom,
            'StudentSinClase'=>$StudentSinClase
        ]);
    }
    public function assignStudents2($id, Request $request) {
        $input = $request->input();

        $Classroom = Classroom::find($id);
        if ( empty($Classroom) || $Classroom===null ) {
            return $request->wantsJson()
                ? response()->json(['message'=>'No se encontró la clase #'.$id], 400)
                : back()->withInput()->withErrors(['alert'=>'No se encontró la clase #'.$id]);
        }

        if ( empty($input['students']) ) {
            $input['students'] = array();
        }

        $Classroom->students()->sync($input['students']);
        $Classroom->refresh();

        /*
        ddd([
            'id'=>$id,
            'input'=>$input,
            'class'=>$Classroom,
            'students'=>$Students,
            'class_students'=>$Classroom->students
        ]);*/
        return $request->wantsJson()
            ? response()->json(['message'=>'Alumnos actualizados'])
            : redirect()->route('admin.classroom.index')->with('success','Alumnos actualizados');
    }
}
