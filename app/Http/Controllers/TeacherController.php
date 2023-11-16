<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function registro(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'tel' => ['required', 'regex:/[0-9()#&+*-=.]+/i'],
            'pais' => 'required',
            'city' => 'required',
            'experiencia' => 'required',
        ]);

        $input = $request->all();

        $input['personal_email'] = $validated['email'];
        $input['email'] = strtolower(substr($validated['fname'], 0, 1).$validated['lname']).time().'@wiseabc.net';
        $input['password'] = $input['email'];

        $user = User::create([
            'name' => ucwords($validated['fname'].' '.$validated['lname']),
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        $user->user_type = 3;
        $user->status = 'registred';
        $user->save();

        $user->saveMetas($input);

        $user->saveHorarios($input['horarios']);

        return redirect()->route('gracias-profesor');
    }

    public function home(Request $request)
    {
        $user = $request->user();

        $hoy = now();

        $clases = $user->teachclasses()->withCount('students')->where('status', 'ACTIVE')->where('ends_at', '>=', $hoy)->get();

        $clases_para_hoy = 0;

        foreach ($clases as $clase) {
            $clase->next = $clase->sigFechaHora();

            if ($clase->next->greaterThanOrEqualTo($hoy) && $clase->next->lessThan(now()->tomorrow())) {
                $clases_para_hoy++;
            }
        }
        $clases = $clases->sortBy('next');

        $clase = null;
        if ($clases->count() > 0 && $clases->first()->next->isoFormat('d') === $hoy->isoFormat('d')) {
            $clase = $clases->shift();
        }

        $sigClase = null;
        $sigClaseFin = null;
        if ($clase != null) {
            $sigClase = $clase->sigFechaHora();

            $sigClaseFin = $sigClase->copy()->addMinutes(40);
        }

        return view('teacher.home', [
            'user' => $user,
            'clase' => $clase,
            'clases' => $clases,
            'clases_para_hoy' => $clases_para_hoy,
            'hoy' => $hoy->setTimezone('-0600')->locale('es'),
            'sigClase' => $sigClase,
            'sigClaseFin' => $sigClaseFin,
            'weekdays' => config('wiseabc.weekdays'),
        ]);
    }

    public function pagos(Request $request)
    {
        return view('teacher.pagos', [
            'user' => $request->user(),
        ]);
    }

    public function perfil(Request $request)
    {
        $profe = $request->user();

        return view('teacher.perfil', [
            'profe' => $profe,

        ]);
    }

    public function update(Request $request)
    {

        $valid = $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'personal_email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'tel' => ['required', 'regex:/[0-9()#&+*-=.]+/i'],
            'pais' => 'required',
            'city' => 'required',
        ]);

        $input = $request->all();

        if (! empty($input['password']) && $input['password'] !== $input['password_confirmation']) {
            return back()->withInput()->withErrors(['password_confirmation' => 'las contraseñas deben ser iguales']);
        }

        $teacher = $request->user();
        $teacher->name = ucwords($valid['fname'].' '.$valid['lname']);
        if (! empty($input['password'])) {
            $teacher->password = Hash::make($input['password']);
        }
        $teacher->save();

        if ($request->hasFile('profilepic')) {
            $input['profilepic'] = $teacher->saveProfilePic($request->file('profilepic'));
        }
        $teacher->saveMetas($input);

        $arrOcupados = $teacher->horariosOcupados();
        foreach ($arrOcupados as $_dia => $_arrHr) {
            if (! is_array($input['horarios'][($_dia)])) {
                $input['horarios'][($_dia)] = [];
            }
            $input['horarios'][($_dia)] = array_merge($input['horarios'][($_dia)], $_arrHr);
            sort($input['horarios'][($_dia)]);
        }
        $teacher->saveHorarios($input['horarios']);

        return back();
    }

    public function profileUpdateRequest(Request $request)
    {
        return redirect()->route('teacher.home')->with('success', 'Solicitud recibda');
    }
}
