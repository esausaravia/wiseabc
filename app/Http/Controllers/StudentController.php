<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
    $paypalSubscriptionQty = 4;
    $paypalSubscriptionStartDate = Carbon::parse('2023-06-05 06:00:00');
    $subscripcion = $user->activeSubscription();

    $classroom = $user->currentClassroom;

    if ( is_object($classroom) )
    {
      $nextSchedule = $classroom->nextSchedule();
    }

    if ( !is_object($subscripcion) )
    {
      $billPlan = \App\Models\BillingPlan::where('status','ACTIVE')->where('tipo', $user->clase_tipo)->where('ritmo', $user->ritmo)->orderBy('created_at','desc')->first();

      if ( is_object($billPlan) )
      {
        $paypalSubscriptionQty = $billPlan->ritmo *4;

        if( !is_object($classroom) )
        {
          //$paypalSubscriptionStartDate = now()->addWeeks(4)->format('Y-m-d\TH:00:00\Z');
          $paypalSubscriptionStartDate = $paypalSubscriptionStartDate->format('Y-m-d\TH:00:00\Z');
        }
        elseif ( now()->lessThan($classroom->start) )
        {
          $paypalSubscriptionStartDate = $classroom->start->format('Y-m-d\TH:00:00\Z');
        }
      }//endif billPlan
    }//endif SIN subscripcion

    if ( is_object($nextSchedule) )
    {
      if ( is_object($subscripcion) && now()->lessThan( $nextSchedule->ends_at ) )
      {
        $activarSigClaseBtn = true;
      }
      $nextSchedule->fechahora = $nextSchedule->fechahora->setTimezone('-0600');
    }

    return view('student.home', [
      'activarSigClaseBtn'=>$activarSigClaseBtn,
      'billPlan'=>$billPlan,
      'classroom'=>$classroom,
      'hoy'=>now('-0600')->locale('es'),
      'nextSchedule'=>$nextSchedule,
      'paypalSubscriptionQty'=> $paypalSubscriptionQty,
      'paypalSubscriptionStartDate' => $paypalSubscriptionStartDate,
      'subscripcion'=>$subscripcion,
      'user'=>$user,
      'weekdays'=>config('wiseabc.weekdays'),
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
  public function update(Request $request)
  {
    $student = $request->user();

    if ($student->currentClassroom!==null)
    {
      $errorMsg = 'Ya tiene una clase asignada, contáctenos para actualizar su perfil.';
      return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
        : back()->withErrors(['alert'=>$errorMsg]);
    }

    $input = $request->input();

    $student->saveMetas($input);

    if ( is_array($input['horarios']) && !empty($input['horarios'][1]) )
    {
      $student->saveHorarios($input['horarios']);
    }


    return $request->wantsJson() ? response(['message'=>'Información actualizada con éxtio'])
      : back()->with('success','Información actualizada con éxtio');
  }
}
