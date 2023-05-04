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

        $student = $request->user();
        if ( empty($student ) || !is_object($student) )
        {
            return $request->wantsJson() ? response(['message'=>'sin sesión de usuario'], 400) : redirect()->back()->withErrors(['message'=>'sin sesión de usuario']);
        }

        $valid = $request->validate([
            'subscriptionID'=>'required'
        ]);

        $paypal_subscription = PayPalController::getSubscriptionDetails($valid['subscriptionID']);

        if ( empty($paypal_subscription) || !is_object($paypal_subscription) )
        {
            $errorMsg = 'Ocurrio un problema verificando la subscripción con PayPal. Por favor, contáctenos con su ID de Subscripción: '.$valid['subscriptionID'];

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['error'=>$errorMsg]);
        }

        $bplan = \App\Models\BillingPlan::whereHas('paypal',function($query){
            $query->where('api_id','P-48D69303M08841545MRGYN4I');
        })->first();

        if ( empty($bplan) || !is_object($bplan) )
        {
            $errorMsg = 'Ocurrio un problema verificando la subscripción con PayPal. Por favor, contáctenos con su ID de Subscripción: '.$valid['subscriptionID'];

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['error'=>$errorMsg]);
        }

        $subscription = $student->subscriptions()->create([
            'billing_plan_id' => $bplan->id,
            'status' => $paypal_subscription->status,
            'start' => now(),
            'next_billing' => $bplan->id==10 ? now()->addDays(3) : now()->addWeeks(4)
        ]);

        $subscription->paypal()->create([
            'api_id'=>$valid['subscriptionID'],
            'api_object'=>json_encode( $paypal_subscription )
        ]);

        return $request->wantsJson() ? response()->json(['id'=>$subscription->id,"status"=>$subscription->status,"paypal"=>$paypal_subscription]) : back()->with('success','Suscrpción registrada con éxito');
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

        $paypal_subscription = PayPalController::getSubscriptionDetails($subscription->paypal->api_id);
        if ( empty($paypal_subscription) || !is_object($paypal_subscription) )
        {
            $errorMsg = 'Ocurrio un problema consultando la información de subscripción #'.$subscription->paypal->api_id;

            return $request->wantsJson() ? response(['error'=>$errorMsg], 400)
                : back()->withErrors(['error'=>$errorMsg]);
        }

        if ( !empty($paypal_subscription->billing_info) && is_object($paypal_subscription->billing_info) && !empty($paypal_subscription->billing_info->next_billing_time) )
        {
            $subscription->next_billing = Carbon::parse($paypal_subscription->billing_info->next_billing_time) ;
        }

        $subscription->status = $paypal_subscription->status;
        $subscription->save();

        $subscription->paypal->api_object = $paypal_subscription;
        $subscription->paypal->save();

        return $paypal_subscription;
    }
}
