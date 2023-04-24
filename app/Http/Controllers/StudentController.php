<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
  public function home(Request $request){
    $user = $request->user();

    if ( $user->clase_tipo===null ) {
      return redirect()->route('student.elegir-subscripcion');
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

  public function elegirSubscripcion(Request $request) {
    return view('student.elegir-subscripcion');
  }

  public function suscribe(Request $request) {
    if ( empty($request->clase_tipo) ) {
      return redirect()->route('student.elegir-subscripcion');
    }

    $billPlan = \App\Models\BillingPlan::where('tipo',$request->clase_tipo)->where('ritmo',$request->ritmo)->first();

    if ( empty($billPlan) ) {
      return redirect()->route('student.elegir-subscripcion');
    }

    $user = $request->user();

    $user->saveMetas([
      'clase_tipo'=>$billPlan->tipo,
      'ritmo'=>$billPlan->ritmo
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
