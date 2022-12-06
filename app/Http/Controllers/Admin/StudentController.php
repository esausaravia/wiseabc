<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    //
    $search = $request->get('searchfor');
    $nivel = $request->get('nivel');
    $edad = $request->get('edad');
    $estatus = $request->get('estatus');

    $students = User::where('user_type', 2)->when($search, function($query) use ($search) {
      $query->where('name', 'like', '%'.$search.'%')
        ->orWhere('email', 'like', '%'.$search.'%');
    })->when($estatus, function($query) use ($estatus) {
      $query->where('status', $estatus);
    })->when($edad, function($query) use ($edad) {
      $query->join('usermetas', 'usermetas.user_id', '=', 'users.id')
        ->where('usermetas.metakey', 'edad')
        ->where('usermetas.metaval', $edad)
        ->select('users.*');
    })->when($nivel, function($query) use ($nivel) {
      $query->join('usermetas', 'usermetas.user_id', '=', 'users.id')
        ->where('usermetas.metakey', 'nivel')
        ->where('usermetas.metaval', $nivel)
        ->select('users.*');
    })->with(['usermetas','classrooms'])->orderBy('name')->get();

    $nivel = DB::table('cursos')->get();
    return view('admin.students', compact('students','search', 'nivel'));
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
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $student = User::with(['usermetas','horarios','classrooms'])->find($id);
    return view('admin.student.show', [
      'student'=>$student
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $student = User::with(['usermetas','classrooms','horarios'])->find($id);

    return view('admin.student.form', [
      'student'=>$student
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $student)
  {
    $valid = $request->validate([
      'email' => [
        'string',
        'email',
        'max:255'
      ],
      'tel' => ['regex:/[0-9()#&+*-=.]+/i'],
    ]);

    if (!empty($valid['email']) ) {

      $existing_email = \Illuminate\Support\Facades\DB::table('users')->select('id')->where('email', 'like', $valid['email'])->where('id','<>', $student->id)->get();
      if ( $existing_email->count()>0 )
      {
        return $request->wantsJson() ? response()->json(['email'=>'Este correo ya lo tiene otro usuario'], 400)
          : back()->withInput()->withErrors(['email'=>'Este correo ya lo tiene otro usuario']);
      }

      $student->email = strtolower($valid['email']);

    }//END email upd

    $input = $request->all();

    if (!empty($input['status']) ) {
      $student->status = $input['status'];
    }

    if ( !empty($input['fname']) && !empty($input['lname']) ) {
      $student->name = ucwords($valid['fname'].' '.$valid['lname']);
    }

    if ( !empty($input['password']) ) {
      $student->password = Hash::make($input['password']);
    }
    $student->save();

    if ( !empty($input['horarios']) && is_array($input['horarios']) )
    {
      $student->saveHorarios($input['horarios']);
    }

    $student->saveMetas($input);

    return $request->wantsJson() ? response()->json(['message'=>"Guardado con éxito"])
      : redirect()->route('admin.student.index')->with('success','Guardado con éxito');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $student)
  {
    //
  }

  public function assignClassroom(Request $request, User $student) {

    $suscr = $student->getMeta('suscripcion');

    if ( empty($suscr) ) {

      dd([
        'test_null' => $student->suscripcion===NULL,
        'empty' => empty($student->suscripcion),
        'type' => gettype( $student->suscripcion ),
        'suscripcion'=> $student->suscripcion,
        'empty2' => empty($suscr),
        'type2' => gettype($suscr),
        'val' => $suscr
      ]);
      return back()->withErrors(['message'=>'Estudiante sin suscripción']);
    }

    $sus_tipo = 1;
    $sus_ritmo = $suscr;

    if ( $suscr>4 ) {
      $sus_tipo = 2;
      $sus_ritmo = $suscr -4;
    }

    $result = DB::table('classrooms')
      ->join('cursos', 'classrooms.curso_id', '=', 'cursos.id')
      ->join('class_horarios', 'classrooms.id','=','class_horarios.class_id')
      ->select('classrooms.id')
      ->where('cursos.edad', $student->edad) // 3,6,12,16,18
      ->where('cursos.nivel', $student->nivel) //A1 B1 C1
      ->where('classrooms.tipo', $sus_tipo) //grupal o individual
      ->where('classrooms.ritmo', $sus_ritmo) //relax, medio, intenso
      ->whereIn('class_horarios.hr', $student->getHorarioArray(1) )
      ->get();
    //select `classrooms`.`id` from `classrooms` inner join `cursos` on `classrooms`.`curso_id` = `cursos`.`id` inner join `class_horarios` on `classrooms`.`id` = `class_horarios`.`class_id` where (`cursos`.`edad` = 1 and `cursos`.`nivel` = 1) and `class_horarios`.`hr` in (9,10,11,12)

    $arrClassId = array();
    foreach( $result AS $row ) {
      $arrClassId[] = $row->id;
    }

    $clases = \App\Models\Classroom::with(['horarios'])->withCount('students')->whereIn('id', $arrClassId)->orderBy('tipo')->orderBy('ritmo')->get();

    $otrasClases = false;
    if ( $clases->count()<1 ) {
      $result = DB::table('classrooms')
        ->join('cursos', 'classrooms.curso_id', '=', 'cursos.id')
        ->join('class_horarios', 'classrooms.id','=','class_horarios.class_id')
        ->select('classrooms.id')
        ->when( $student->edad, function($query, $getEdad) {
          $query->where('cursos.edad', $getEdad);
        })
        ->when( $student->nivel, function($query, $getNivel) {
          $query->where('cursos.nivel',$getNivel);
        })
        ->where('tipo', $sus_tipo)
        ->where('ritmo', $sus_ritmo)
        ->get();

      $arrClassId = array();
      foreach( $result AS $row ) {
        $arrClassId[] = $row->id;
      }

      $otrasClases = \App\Models\Classroom::with(['horarios'])->withCount('students')->whereIn('id', $arrClassId)->orderBy('tipo')->orderBy('ritmo')->get();
    }//ENDif

    return view('admin.student.assignclass', [
      'student' => $student,
      'clases' => $clases,
      'otrasClases' => !empty($otrasClases) ? $otrasClases : collect([]),
      'weekdays' => config('wiseabc.weekdays')
    ]);
  }

  public function assignClassroom2(Request $request, User $student) {
    $valid = $request->validate([
      'user_id'=>'required|integer',
      'class_id'=>'required|integer'
    ]);

    $clase = \App\Models\Classroom::find($valid['class_id']);
    if ( $clase->students()->count()>3 ) {
      return back()->withErrors(['message'=>'Esta clase ya tiene 3 estudiantes']);
    }

    $student->classrooms()->attach($valid['class_id']);

    return redirect()->route('admin.student.index')->with('success','Se ha agregado a la clase');
  }
}
