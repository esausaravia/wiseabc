<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
  public function home(Request $request){
    $user = $request->user();

    if ( $user->suscripcion===null ) {
      return redirect()->route('student.elegir-suscripcion');
    }

    $enWeekdays = config('wiseabc.en_weekdays');
    $today = \Illuminate\Support\Carbon::now('America/Mexico_City')->locale('es');

    $clase = $user->classrooms()->with(['horarios'])->withCount('students')->where('ends_at','>=', $today->format('Y-m-d') )->get()->first();

    if ( !empty($clase) ) {
      $next_hr = $clase->horarios()->first();
      foreach( $clase->horarios AS $horario ) {
        if ( $horario->dia > (int)$today->format('N') ) {
          $next_hr = $horario;
        }
        else if ( $horario->dia==(int)$today->format('N') && $horario->hr >= (int)$today->format('G') ) {
          $next_hr = $horario;
        }
      }

      if ( $next_hr->dia==(int)$today->isoFormat('d') ) {
        $carbon_next = \Illuminate\Support\Carbon::createFromTime($next_hr->hr,0,0,'America/Mexico_City')->locale('es');
      }
      else {
        $carbon_next = \Illuminate\Support\Carbon::create('next '.$enWeekdays[($next_hr->dia)] )->locale('es');
        $carbon_next->addHours($next_hr->hr);
      }
    }

    return view('student.home', [
      'user'=>$user,
      'clase'=>$clase,
      'next'=> !empty($carbon_next) ? $carbon_next : null ,
      'today'=>$today,
      'weekdays'=>config('wiseabc.weekdays')
    ]);
  }

  public function elegirSuscripcion(Request $request) {
    return view('student.elegir-suscripcion');
  }

  public function suscribe(Request $request) {
    $user = $request->user();

    $user->status = 'suscribed';
    $user->usermetas()->create([
      'metakey' => 'suscripcion',
      'metaval' => $request->suscripcion
    ]);
    $user->save();

    return redirect()->route('home');
  }

  public function pagos(Request $request) {

    return view('student.pagos',[
      'user'=>$request->user()
    ]);
  }

  public function perfil(Request $request) {
    return view('student.perfil',[
      'user'=>$request->user()
    ]);
  }
}
