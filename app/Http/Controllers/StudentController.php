<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
    $hoy = now();
    $nextSchedule = null;
    $subscriptionQty = 4;
    $subscriptionStartDate = null;
    $subscripcion = $user->activeSubscription();

    $countryCode = 'MX';//US
    $ipApi = session('ip-api');

    if ( is_array($ipApi) && !empty($ipApi['countryCode']) )
    {
      $countryCode = $ipApi['countryCode'];
    }
    else if( !empty($request->input('countryCode')) )
    {
      $countryCode = $request->input('countryCode');
    }

    $classroom = $user->currentClassroom;

    if ( is_object($classroom) )
    {
      $nextSchedule = $classroom->nextSchedule();
    }

    if ( is_object($nextSchedule) )
    {
      if ( is_object($subscripcion) && $hoy->lessThan( $nextSchedule->ends_at ) )
      {
        $activarSigClaseBtn = true;
      }
      $nextSchedule->fechahora->setTimezone('-0600');
    }

    if ( !is_object($subscripcion) )
    {

      $billPlan = \App\Models\BillingPlan::where('status','ACTIVE')
          ->where('tipo', $user->clase_tipo)
          ->where('ritmo', $user->ritmo)
          ->whereHas('region', function($query) use ($countryCode){
            $query->where('countries','LIKE',"%MX%");  //('countries','LIKE',"%{$countryCode}%");
          })
          ->orderBy('created_at','desc')->first();

      if ( is_object($billPlan) )
      {
        $subscriptionQty = $billPlan->ritmo *4;

        if ( is_object($classroom) && $hoy->lessThan($classroom->start) )
        {
          $subscriptionStartDate = $classroom->start->format('Y-m-d\TH:00:00\Z');
        }
      }//endif billPlan
    }//endif SIN subscripcion

    return view('student.home', [
      'activarSigClaseBtn'=>$activarSigClaseBtn,
      'billPlan'=>$billPlan,
      'classroom'=>$classroom,
      'hoy'=>$hoy->setTimezone('-0600')->locale('es'),
      'nextSchedule'=>$nextSchedule,
      'subscriptionQty'=> $subscriptionQty,
      'subscriptionStartDate' => $subscriptionStartDate,
      'subscripcion'=>$subscripcion,
      'user'=>$user,
      'weekdays'=>config('wiseabc.weekdays'),
    ]);
  }

  public function elegirRitmo(Request $request)
  {

    $countryCode = 'MX';//US

    $ipApi = session('ip-api');

    if( !empty($request->input('countryCode')) )
    {
      $countryCode = $request->input('countryCode');
    }
    else if ( is_array($ipApi) && !empty($ipApi['countryCode']) )
    {
      $countryCode = $ipApi['countryCode'];
    }

    $region = \App\Models\billRegion::where('countries','LIKE',"%MX%")->first();//where('countries','LIKE',"%{$countryCode}%")->first();
    if ( !is_object($region) )
    {
      $region = \App\Models\billRegion::find(1);
    }

    /*
    $billingPlans = \App\Models\BillingPlan::whereHas()
    */
    return view('student.elegir-ritmo',[
      'region' => $region,
    ]);
  }

  public function postElegirRitmo(Request $request)
  {
    $valid = $request->validate([
      'clase_tipo' => 'required|integer',
      'ritmo' => 'required|integer',
    ]);

    $billPlan = \App\Models\BillingPlan::where('status','ACTIVE')
        ->where('tipo', $valid['clase_tipo'])
        ->where('ritmo', $valid['ritmo'])
        ->first();

    if ( empty($billPlan) )
    {
      return back()->withError("No se encontró la suscripción compatible.");
    }

    $user = $request->user();

    $user->saveMetas([
      'clase_tipo'=>$valid['clase_tipo'],
      'ritmo'=>$valid['ritmo']
    ]);

    return redirect()->route('student.home');
  }

  public function pagos(Request $request)
  {
    $user = $request->user();

    $billPlan = null;
    $classroom = null;
    $paypalSubscriptionQty = 4;
    $paypalSubscriptionStartDate = Carbon::parse('2024-06-05 06:00:00');
    $subscripcion = $user->activeSubscription();

    $countryCode = 'US';
    $ipApi = session('ip-api');

    if ( is_array($ipApi) && !empty($ipApi['countryCode']) )
    {
      $countryCode = $ipApi['countryCode'];
    }
    else if( !empty($request->input('countryCode')) )
    {
      $countryCode = $request->input('countryCode');
    }

    if ( is_object($subscripcion) )
    {
      $billPlan = &$subscripcion->billingPlan;
    }
    else {
      $billPlan = \App\Models\BillingPlan::where('status','ACTIVE')
          ->where('tipo', $user->clase_tipo)
          ->where('ritmo', $user->ritmo)
          ->whereHas('region', function($query) use ($countryCode){
            $query->where('countries','LIKE',"%{$countryCode}%");
          })
          ->orderBy('created_at','desc')->first();
    }

    if ( is_object($billPlan) )
    {
      $classroom = $user->currentClassroom;

      $paypalSubscriptionQty = $billPlan->ritmo *4;

      if ( is_object($classroom) && now()->lessThan($classroom->start) )
      {
        $paypalSubscriptionStartDate = $classroom->start->format('Y-m-d\TH:00:00\Z');
      }
      else {
        //$paypalSubscriptionStartDate = now()->addWeeks(4)->format('Y-m-d\TH:00:00\Z');
        $paypalSubscriptionStartDate = $paypalSubscriptionStartDate->format('Y-m-d\TH:00:00\Z');
      }
    }//endif billPlan

    return view('student.pagos',[
      'billPlan'=>$billPlan,
      'classroom'=>$classroom,
      'hoy'=>now('-0600')->locale('es'),
      'paypalSubscriptionQty'=> $paypalSubscriptionQty,
      'paypalSubscriptionStartDate' => $paypalSubscriptionStartDate,
      'subscripcion'=>$subscripcion,
      'user'=>$user,
      'weekdays'=>config('wiseabc.weekdays'),
    ]);
  }

  public function perfil(Request $request)
  {
    return view('student.perfil',[
      'user'=>$request->user()
    ]);
  }
  public function update(Request $request)
  {
    $student = $request->user();

    $valid = $request->validate([
      'fname' => 'required',
      'lname' => 'required',
      'email' => [
        'required',
        'email',
        'max:255',
        Rule::unique(User::class)->ignore($student->id),
      ],
      'tel' => [
        'required','min:10','max:30'
      ],
      'password' => [
        'sometimes','nullable', Password::defaults()
      ],
      'password_confirmation' => [
        'sometimes','nullable','same:password'
      ]
    ]);//,

    $student->name = $valid['fname'].' '.$valid['lname'];
    $student->email = $valid['email'];

    if ( !empty($valid['password']) && $valid['password']===$valid['password_confirmation'] )
    {
      $student->password = Hash::make($valid['password']);
    }
    $student->save();

    $input = $request->input();

    $inputColl = collect($input);

    if ($student->currentClassroom!==null && $inputColl->hasAny(['edad','nivel','ritmo','clase_tipo','horarios']) )
    {
      $errorMsg = 'Ya tiene una clase asignada, contáctenos para actualizar su perfil.';
      return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
        : back()->withError($errorMsg);
    }

    $student->saveMetas($input);

    if ( !empty($input['horarios']) && is_array($input['horarios']) && !empty($input['horarios'][1]) )
    {
      $student->saveHorarios($input['horarios']);
    }


    return $request->wantsJson() ? response(['message'=>'Información actualizada con éxtio'])
      : back()->with('success','Información actualizada con éxtio');
  }
  public function updateMetas(Request $request)
  {
    $student = $request->user();

    $input = $request->all();

    $inputColl = collect($input);

    if ($student->currentClassroom!==null && $inputColl->hasAny(['edad','nivel','ritmo','clase_tipo','horarios']) )
    {
      $errorMsg = 'Ya tiene una clase asignada, contáctenos para actualizar su perfil.';
      return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
        : back()->withError($errorMsg);
    }

    $student->saveMetas($input);

    if ( !empty($input['horarios']) && is_array($input['horarios']) && !empty($input['horarios'][1]) )
    {
      $student->saveHorarios($input['horarios']);
    }


    return $request->wantsJson() ? response(['message'=>'Información actualizada con éxtio'])
      : back()->with('success','Información actualizada con éxtio');
  }
}
