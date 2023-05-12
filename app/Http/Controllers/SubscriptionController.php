<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PayPalController;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        /*
        {
            "orderID": "71X44664741365155",
            "subscriptionID": "I-9RC8P1B1PBVJ",
            "facilitatorAccessToken": "A21AALpirqsqznz1f1O8BpA4-M4FVG6xk3LarfRV86_bIWubLHvUKoSJ3viSs4aZXGA2WxYxXrwf2tS_4y2UVjlYKd7e9kzQw",
            "paymentSource": "paypal"
        }
        */
        $student = $request->user();
        if ( empty($student ) || !is_object($student) )
        {
            return $request->wantsJson() ? response(['message'=>'sin sesión de usuario'], 400)
                    : back()->withErrors(['alert'=>'sin sesión de usuario']);
        }

        $valid = $request->validate([
            'subscriptionID'=>'required'
        ]);

        $paypalSubscription = PayPalController::getSubscriptionDetails($valid['subscriptionID']);

        if ( !is_object($paypalSubscription) )
        {
            $errorMsg = 'Ocurrio un problema verificando la subscripción con PayPal. Por favor, contáctenos con su ID de Subscripción: '.$valid['subscriptionID'];

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['alert'=>$errorMsg]);
        }

        $bplan = \App\Models\BillingPlan::whereHas('paypal',function($query) use ($paypalSubscription){
            $query->where('api_id', $paypalSubscription->plan_id);
        })->first();

        if ( !is_object($bplan) )
        {
            $errorMsg = 'Ocurrio un problema verificando la subscripción con PayPal. Por favor, contáctenos con su ID de Subscripción: '.$paypalSubscription->id;

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['alert'=>$errorMsg]);
        }

        $student->saveMetas([
            'clase_tipo' => $bplan->tipo,
            'ritmo' => $bplan->ritmo
        ]);

        $subscription = $student->subscriptions()->create([
            'billing_plan_id' => $bplan->id,
            'status' => $paypalSubscription->status,
            'start' => Carbon::parse( $paypalSubscription->start_time ),
            'next_billing' => Carbon::parse( $paypalSubscription->billing_info->next_billing_time )
        ]);

        $subscription->paypal()->create([
            'api_id'=>$paypalSubscription->id,
            'api_object'=>$paypalSubscription
        ]);

        return $request->wantsJson() ? response()->json(['id'=>$subscription->id,"status"=>$subscription->status,"paypal"=>$paypalSubscription])
            : back()->with('success','Subscrpción registrada con éxito');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $subscription = Subscription::with(['billingPlan','paypal','stripe'])->find($id);
        if (empty($subscription) || !is_object($subscription))
        {
            $errorMsg = 'No se encontró la subscripción #'.$id;

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['error'=>$errorMsg]);
        }

        if ( !empty($subscription->paypal) && is_object($subscription->paypal) )
        {
            $this->getPaypalDetails($request, $subscription);
        }

        return $request->wantsJson() ? $subscription : back()->with('success','Subscripción actualizada con éxito.');
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

    public function getPaypalDetails(Request $request, Subscription $subscription)
    {
        if ( empty($subscription->paypal) || !is_object($subscription->paypal) || empty($subscription->paypal->api_id) )
        {
            return false;
        }

        $paypalSubscription = PayPalController::getSubscriptionDetails($subscription->paypal->api_id);
        if ( empty($paypalSubscription) || !is_object($paypalSubscription) )
        {
            $errorMsg = 'Ocurrio un problema consultando la información de subscripción #'.$subscription->paypal->api_id;

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['error'=>$errorMsg]);
        }

        if ( !empty($paypalSubscription->billing_info) && is_object($paypalSubscription->billing_info) && !empty($paypalSubscription->billing_info->next_billing_time) )
        {
            $subscription->next_billing = Carbon::parse($paypalSubscription->billing_info->next_billing_time) ;
        }

        $subscription->status = $paypalSubscription->status;
        $subscription->save();

        $subscription->paypal->api_object = $paypalSubscription;
        $subscription->paypal->save();

        return $paypalSubscription;
    }
}
