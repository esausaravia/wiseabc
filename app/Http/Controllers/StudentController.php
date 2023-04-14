<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
  public function home(Request $request){
    $user = $request->user();

    if ( $user->suscripcion===null ) {
      return redirect()->route('student.elegir-suscripcion');
    }
    $clase = null;
    $sigClase = null;
    $sigClaseFin = null;
    $activarSigClaseBtn = false;
    $hoy = now('-0600')->locale('es');

    $clase = $user->currentClassroom;

    if ($clase && is_object($clase) ) {
      $sigClase = $clase->sigFechaHora();
      $sigClaseFin = $sigClase->copy()->addMinutes(40);

      if ( $hoy->lessThan( $sigClaseFin ) ) {
        $activarSigClaseBtn = true;
      }
    }

    return view('student.home', [
      'user'=>$user,
      'clase'=>$clase,
      'hoy'=>$hoy,
      'sigClase'=>$sigClase,
      'sigClaseFin'=>$sigClaseFin,
      'activarSigClaseBtn'=>$activarSigClaseBtn,
      'weekdays'=>config('wiseabc.weekdays')
    ]);
  }

  public function elegirSuscripcion(Request $request) {
    return view('student.elegir-suscripcion');
  }

  public function suscribe(Request $request) {
    if ( empty($request->suscripcion) ) {
      return redirect()->route('student.elegir-suscripcion');
    }

    $arrSuscripciones = config('wiseabc.suscripciones');
    $susc = $arrSuscripciones[( $request->suscripcion )];
    if ( empty($susc) ) {
      return redirect()->route('student.elegir-suscripcion');
    }

    $user = $request->user();

    $user->status = 'suscribed';
    $user->saveMetas([
      'suscripcion'=>$request->suscripcion,
      'clase_tipo'=>$susc['tipo'],
      'ritmo'=>$susc['ritmo']
    ]);

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
