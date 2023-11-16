<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->get('searchfor');
        $nivel = $request->get('nivel');
        $edad = $request->get('edad');
        $ritmo = $request->get('ritmo');
        $clase_tipo = $request->get('clase_tipo');
        $estatus = $request->get('estatus');

        $Students = User::with([
            'usermetas',
            'classrooms' => function ($query) {
                $query->where('status', 'active')->where('ends_at', '>=', now());
            },
        ])
            ->where('user_type', 2)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->when($estatus, function ($query) use ($estatus) {
                $query->where('status', $estatus);
            })
            ->when($edad, function ($query) use ($edad) {
                $query->whereHas('usermetas', function ($_query) use ($edad) {
                    $_query->where('metakey', 'edad')
                        ->where('metaval', $edad)->select('user_id');
                });
            })
            ->when($nivel, function ($query) use ($nivel) {
                $query->whereHas('usermetas', function ($_query) use ($nivel) {
                    $_query->where('metakey', 'nivel')
                        ->where('metaval', $nivel)->select('user_id');
                });
            })
            ->when($ritmo, function ($query) use ($ritmo) {
                $query->whereHas('usermetas', function ($_query) use ($ritmo) {
                    $_query->where('metakey', 'ritmo')
                        ->where('metaval', $ritmo)->select('user_id');
                });
            })
            ->orderBy('name');
        $sql = vsprintf(str_replace(['?'], ['\'%s\''], $Students->toSql()), $Students->getBindings());
        //Log::debug("admin StudentController index sql: \n".$sql);
        //dd($sql);

        $Students = $Students->get();

        $nivel = DB::table('cursos')->get();

        return view('admin.students', compact('Students', 'search', 'nivel', 'edad', 'ritmo', 'clase_tipo', 'request'));
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
     * @param  \App\Models\User  $user
     */
    public function show($id): View
    {
        $student = User::with([
            'usermetas',
            'horarios',
            'classrooms' => function ($query) {
                $query->where('status', 'active')->where('ends_at', '>=', now('-0600'));
            },
            'classrooms.curso',
            'classrooms.horarios',
        ])->find($id);

        $subscripcion = $student->subscriptions()->first();

        return view('admin.student.show', [
            'student' => $student,
            'subscripcion' => $subscripcion,
            'ritmo_labels' => config('wiseabc.ritmo_labels'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     */
    public function edit($id): View
    {
        $student = User::with([
            'usermetas',
            'horarios',
            'classrooms' => function ($query) {
                $query->where('status', 'active')->where('ends_at', '>=', now('-0600'));
            },
            'classrooms.curso',
            'classrooms.horarios',
        ])->find($id);

        if ($student->fname === null) {
            $arrName = explode(' ', $student->name);

            if (count($arrName) < 2) {
                $arrName = [$student->name, 'WiseABC'];
            }

            $student->saveMetas([
                'lname' => array_pop($arrName),
                'fname' => implode(' ', $arrName),
            ]);
        }

        $arrHorariosOcupados = [];
        if (! empty($student->currentClassroom)) {

            foreach ($student->currentClassroom->getHorariosArray() as $_dia => $_arrHrs) {
                $arrHorariosOcupados = array_merge($arrHorariosOcupados, $_arrHrs);
            }
        }

        return view('admin.student.form', [
            'student' => $student,
            'ritmo_labels' => config('wiseabc.ritmo_labels'),
            'arrHorariosOcupados' => $arrHorariosOcupados,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $student)
    {
        $valid = $request->validate([
            'email' => [
                'string',
                'email',
                'max:255',
            ],
            'tel' => ['regex:/[0-9()#&+*-=.]+/i'],
        ]);

        if (! empty($valid['email'])) {

            $existing_email = DB::table('users')->select('id')->where('email', 'like', $valid['email'])->where('id', '<>', $student->id)->get();
            if ($existing_email->count() > 0) {
                return $request->wantsJson() ? response()->json(['email' => 'Este correo ya lo tiene otro usuario'], 400)
                  : back()->withInput()->withErrors(['email' => 'Este correo ya lo tiene otro usuario']);
            }

            $student->email = strtolower($valid['email']);

        }//END email upd

        $input = $request->all();

        if (! empty($input['status'])) {
            $student->status = $input['status'];
        }

        if (! empty($input['fname']) && ! empty($input['lname'])) {
            $student->name = ucwords($input['fname'].' '.$input['lname']);
        }

        if (! empty($input['password'])) {
            $student->password = Hash::make($input['password']);
        }
        $student->save();

        $student->saveMetas($input);

        if (! empty($input['horarios']) && is_array($input['horarios'])) {
            $student->saveHorarios($input['horarios']);
        }

        return $request->wantsJson() ? response()->json(['message' => 'Guardado con éxito'])
          : redirect()->route('admin.student.index')->with('success', 'Guardado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     */
    public function destroy(User $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.student.index')->with('success', 'Eliminado con éxito');
    }

    public function assignClassroom(Request $request, User $student)
    {

        if ($student->clase_tipo == null || $student->ritmo == null) {
            return back()->withErrors(['alert' => 'Estudiante sin elegir tipo de clase']);
        }

        $billPlan = \App\Models\BillingPlan::where('status', 'ACTIVE')->where('tipo', $student->clase_tipo)->where('ritmo', $student->ritmo)->first();

        if (empty($billPlan)) {
            return back()->withErrors(['alert' => 'Estudiante sin suscripción']);
        }

        $result = DB::table('classrooms')
            ->join('cursos', 'classrooms.curso_id', '=', 'cursos.id')
            ->join('class_horarios', 'classrooms.id', '=', 'class_horarios.class_id')
            ->select('classrooms.id')
            ->where('cursos.edad', $student->edad) // 3,6,12,16,18
            ->where('cursos.nivel', $student->nivel) //A1 B1 C1
            ->where('classrooms.tipo', $billPlan->tipo) //grupal o individual
            ->where('classrooms.ritmo', $billPlan->ritmo) //relax, medio, intenso
            ->whereIn('class_horarios.hr', $student->getHorarioArray(1))
            ->get();
        //select `classrooms`.`id` from `classrooms` inner join `cursos` on `classrooms`.`curso_id` = `cursos`.`id` inner join `class_horarios` on `classrooms`.`id` = `class_horarios`.`class_id` where (`cursos`.`edad` = 1 and `cursos`.`nivel` = 1) and `class_horarios`.`hr` in (9,10,11,12)

        $arrClassId = [];
        foreach ($result as $row) {
            $arrClassId[] = $row->id;
        }

        $clases = \App\Models\Classroom::with(['horarios'])->withCount('students')->whereIn('id', $arrClassId)->orderBy('tipo')->orderBy('ritmo')->get();

        $otrasClases = false;
        if ($clases->count() < 1) {
            $result = DB::table('classrooms')
                ->join('cursos', 'classrooms.curso_id', '=', 'cursos.id')
                ->join('class_horarios', 'classrooms.id', '=', 'class_horarios.class_id')
                ->select('classrooms.id')
                ->when($student->edad, function ($query, $getEdad) {
                    $query->where('cursos.edad', $getEdad);
                })
                ->when($student->nivel, function ($query, $getNivel) {
                    $query->where('cursos.nivel', $getNivel);
                })
                ->where('tipo', $billPlan->tipo)
                ->where('ritmo', $billPlan->ritmo)
                ->get();

            $arrClassId = [];
            foreach ($result as $row) {
                $arrClassId[] = $row->id;
            }

            $otrasClases = \App\Models\Classroom::with(['horarios'])->withCount('students')->whereIn('id', $arrClassId)->orderBy('tipo')->orderBy('ritmo')->get();
        }//ENDif

        return view('admin.student.assignclass', [
            'weekdays' => config('wiseabc.weekdays'),
            'arrRitmos' => config('wiseabc.ritmo_labels'),
            'Student' => $student,
            'billPlan' => $billPlan,
            'clases' => $clases,
            'otrasClases' => ! empty($otrasClases) ? $otrasClases : collect([]),
        ]);
    }

    public function assignClassroom2(Request $request, User $student)
    {
        $valid = $request->validate([
            'user_id' => 'required|integer',
            'class_id' => 'required|integer',
        ]);

        $clase = \App\Models\Classroom::find($valid['class_id']);
        if (! is_object($clase) || class_basename($clase) !== 'Classroom') {
            $errMsg = 'No se encontró la clase #'.$valid['class_id'];

            return $request->wantsJson() ? response(['alert' => $errMsg], 400)
                : back()->withErrors(['alert' => $errMsg]);
        }
        if ($clase->students()->count() > 2) {
            return back()->withErrors(['alert' => 'Esta clase ya tiene 3 estudiantes']);
        }

        $student->classrooms()->attach($valid['class_id']);

        $mailable = new \App\Mail\Student\ClaseAsignada($student, $clase);
        $result = \Illuminate\Support\Facades\Mail::to($student)->send($mailable);

        return redirect()->route('admin.student.index')->with('success', 'Se ha agregado a la clase');
    }
}
