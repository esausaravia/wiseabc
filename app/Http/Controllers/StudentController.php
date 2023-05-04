<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
  public function home(Request $request)
  {
    $user = $request->user();

    if ( $user->clase_tipo===null ) {
      return redirect()->route('student.elegir-ritmo');
    }

    $activarSigClaseBtn = false;
    $billPlan = null;
    $classroom = null;
    $nextSchedule = null;
    $subscripcion = null;

    $classroom = $user->currentClassroom;

    if ( $classroom && is_object($classroom) )
    {
      $nextSchedule = $classroom->schedules()->where('fechahora', '>', now()->subMinutes(6) )->first();

      $subscripcion = $user->activeSubscription();
    }

    if ( is_object($nextSchedule) && is_object($subscripcion) && $nextSchedule->fechahora->lessThan( now()->setMinutes(40) ) )
    {
      $activarSigClaseBtn = true;
    }

    if ( !is_object($subscripcion) )
    {
      $billPlan = \App\Models\BillingPlan::where('status','ACTIVE')->where('tipo', $user->clase_tipo)->where('ritmo',$user->ritmo)->orderBy('created_at','desc')->first();
    }

    if ( is_object($nextSchedule) )
    {
      $nextSchedule->fechahora = $nextSchedule->fechahora->setTimezone('-0600');
    }

    return view('student.home', [
      'weekdays'=>config('wiseabc.weekdays'),
      'user'=>$user,
      'classroom'=>$classroom,
      'hoy'=>now('-0600')->locale('es'),
      'activarSigClaseBtn'=>$activarSigClaseBtn,
      'subscripcion'=>$subscripcion,
      'billPlan'=>$billPlan,
      'nextSchedule'=>$nextSchedule
    ]);
  }

  public function elegirRitmo(Request $request) {
    return view('student.elegir-ritmo');
  }

  public function postElegirRitmo(Request $request)
  {
    if ( empty($request->clase_tipo) || empty($request->ritmo) )
    {
      return redirect()->route('student.elegir-ritmo');
    }

    $billPlan = \App\Models\BillingPlan::where('status','ACTIVE')->where('tipo',$request->clase_tipo)->where('ritmo',$request->ritmo)->first();

    if ( empty($billPlan) )
    {
      return back()->withErrors(['message'=>"No se encontró la suscripción compatible."]);
    }

    $user = $request->user();

    $user->saveMetas([
      'clase_tipo'=>$request->clase_tipo,
      'ritmo'=>$request->ritmo
    ]);

    return redirect()->route('student.home');
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
