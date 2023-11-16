<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BillingPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class StripeController extends Controller
{
    /**
     * Guarda el identificador Customer de Stripe asociado al usuario actual
     *
     * @param  object  $checkoutSession
     * @return \App\Models\User
     */
    public static function saveCustomerIdFromCheckoutSession(object $checkoutSession = null): User
    {
        if (! is_object($checkoutSession)) {
            Log::error(__METHOD__, ['error' => '!is_object($checkoutSession)']);

            return false;
        }
        if (empty($checkoutSession->customer)) {
            Log::error(__METHOD__, ['error' => 'checkoutSession sin customer', 'checkoutSession' => $checkoutSession]);

            return false;
        }

        $student = null;
        if (! empty($checkoutSession->client_reference_id)) {
            $student = \App\Models\User::find($checkoutSession->client_reference_id);
        }
        if (! is_object($student)) {
            $student = \App\Models\User::with(['stripe'])->whereHas('usermetas', function ($query) use ($checkoutSession) {
                $query->where('metaval', $checkoutSession->id);
            })->first();
        }
        if (! is_object($student)) {
            $student = request()->user();
        }
        if (! is_object($student)) {
            $student = \Illuminate\Support\Facades\Auth::user();
        }
        if (! is_object($student)) {
            Log::error(__METHOD__, ['error' => '!is_object($student)']);

            return false;
        }

        if (! is_object($student->stripe)) {// && $checkoutSession->client_reference_id==$student->id
            $student->stripe()->create([
                    'api' => 'stripe',
                    'api_id' => $checkoutSession->customer,
                    'api_object' => '{}',
                ]);
        } else {
            $student->stripe->api_id = $checkoutSession->customer;
            $student->stripe->save();
        }

        return $student;
    }

    /**
     * Crea subscripción de estudiante con base en CheckoutSession de Stripe
     *
     * @param  object  $checkoutSession
     * @return \App\Models\User
     */
    public static function saveSubscriptionFromCheckoutSession(object $checkoutSession = null): User
    {
        if (! is_object($checkoutSession)) {
            Log::error(__METHOD__, ['error' => '!is_object($checkoutSession)']);

            return false;
        }
        if ($checkoutSession->mode != 'subscription' || empty($checkoutSession->subscription)) {
            Log::error(__METHOD__, ['error' => 'checkoutSession sin subscription', 'checkoutSession' => $checkoutSession]);

            return false;
        }

        $student = self::saveCustomerIdFromCheckoutSession($checkoutSession);
        if (! is_object($student)) {
            return false;
        }

        $bplan = null;
        if (is_object($checkoutSession->metadata) && ! empty($checkoutSession->metadata->billing_plan_id)) {
            $bplan = \App\Models\BillingPlan::find($checkoutSession->metadata->billing_plan_id);
        } else {
            //buscar por Subscription->stripe->api_id
        }

        if (! is_object($bplan)) {
            Log::error(__METHOD__, ['error' => 'No se encontró el Billing Plan ID ', 'checkoutsession' => $checkoutSession]);

            return false;
        }

        $stripeSubscriptionId = $checkoutSession->subscription;

        $subscription = $student->subscriptions()->with(['stripe', 'billingPlan'])
            ->where('billing_plan_id', $bplan->id)
            ->whereHas('stripe', function ($query) use ($stripeSubscriptionId) {
                $query->where('api_id', $stripeSubscriptionId);
            })->first();

        if (is_object($subscription)) {
            $subscription->status = $checkoutSession->payment_status != 'unpaid' ? 'ACTIVE' : 'CHECKOUT';
        } else {
            $subscription = $student->subscriptions()->create([
                'billing_plan_id' => $bplan->id,
                'status' => $checkoutSession->payment_status != 'unpaid' ? 'ACTIVE' : 'CHECKOUT',
                'start' => now(),
                'next_billing' => now()->addWeeks(4),
            ]);

            $subscription->stripe()->create([
                'api' => 'stripe',
                'api_id' => $checkoutSession->subscription,
                'api_object' => '{}',
            ]);
        }

        return $subscription;
    }

    public static function updCustomerData($customerData)
    {
        if (! is_object($customerData) || empty($customerData->id)) {
            return false;
        }
        $student = \App\Models\User::with(['stripe'])->whereHas('stripe', function ($query) use ($customerData) {
            $query->where('api', 'stripe')->where('api_id', $customerData->id);
        })->first();
        if (! is_object($student)) {
            return false;
        }
        $student->stripe->api_object = $customerData;
        $student->stripe->save();

        return $student;
    }

    public static function updSubscriptionData($stripeData)
    {
        if (! is_object($stripeData) || empty($stripeData->id)) {
            return false;
        }
        $stripeID = $stripeData->id;
        $subscription = \App\Models\Subscription::with(['stripe', 'user', 'billingPlan'])
            ->whereHas('stripe', function ($query) use ($stripeID) {
                $query->where('api', 'stripe')->where('api_id', $stripeID);
            })->first();

        if (! is_object($subscription)) {
            return false;
        }
        $subscription->stripe->api_object = $stripeData;
        $subscription->stripe->save();

        //billing_cycle_anchor
        //trial_end
        //start_date

        $subscription->status = strtoupper($stripeData->status);
        $subscription->next_billing = Carbon::parse($stripeData->current_period_end);
        $subscription->save();

        return $subscription;
    }

    //  \Stripe\Stripe::setApiKey( env('STRIPE_SECRET') );
    public function subscriptionCheckoutSession(Request $request)
    {
        $valid = $request->validate([
            'billing_plan_stripe_id' => 'required',
        ]);
        $stripeID = $valid['billing_plan_stripe_id'];

        $bplan = BillingPlan::with('stripe')->whereHas('stripe', function (Builder $query) use ($stripeID) {
            $query->where('api', 'stripe')->where('api_id', $stripeID);
        })->first();

        if (empty($bplan) || ! is_object($bplan)) {
            $errMsg = "No se encontró plan de subscripción {$stripeID}";

            return $request->wantsJson() ? response(['alert' => $errMsg], 400)
                : back()->withError($errMsg);
        }

        $input = $request->input();

        $startDate = $request->input('start_date');
        $maxStartDate = now()->addWeeks(4)->subHour();
        if (is_string($startDate)) {
            if ($maxStartDate->lessThan($startDate)) {
                $startDate = $maxStartDate->getTimestamp();
            } else {
                $startDate = Carbon::parse($startDate)->getTimestamp();
            }

        }

        $student = $request->user();

        $checkoutSessionData = [
            'line_items' => [[
                'price' => $bplan->stripe->api_id,
                'quantity' => ! empty($input['subscription_qty']) ? $input['subscription_qty'] : 1,
            ]],
            'subscription_data' => [
                //'default_tax_rates' => ['txr_1N2TLPKYG3qD2MystfTjOq4s']
                'trial_end' => $startDate,
            ],
            'mode' => 'subscription',
            'success_url' => route('student.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('student.home'),
            'client_reference_id' => $student->id,
            'customer_email' => empty($student->stripe) ? $student->email : null,
            'customer' => ! empty($student->stripe) ? $student->stripe->api_id : null,
            'metadata' => [
                'billing_plan_id' => $bplan->id,
            ],
            //'allow_promotion_codes'=>true,
            'discounts' => [[
                'coupon' => 'AqH3o0Fl',
            ]],
            /*
            'subscription_data' => [
                'billing_cycle_anchor' => 1672531200,
            ],
            */
        ];

        if (! empty($input['coupon'])) {
            $checkoutSessionData['discounts'] = [[
                'coupon' => $input['coupon'],
            ]];
        } elseif (! empty($input['promotion_code'])) {
            $checkoutSessionData['discounts'] = [[
                'promotion_code' => $input['promotion_code'],
            ]];
        } else {
            $checkoutSessionData['allow_promotion_codes'] = true;
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {

            $checkoutSession = \Stripe\Checkout\Session::create($checkoutSessionData);
            Log::debug(__METHOD__, ['checkoutSession' => $checkoutSession]);

            $student->saveMetas([
                'stripe_checkoutSession' => $checkoutSession->id,
                'stripe_checkoutSession_expires_at' => $checkoutSession->expires_at,
            ]);

            return redirect($checkoutSession->url, 303);
        } catch (Throwable $e) {
            return $request->wantsJson() ? response(['error' => $e->getMessage()], 500)
                : back()->withErrors(['alert' => $e->getMessage()]);
        }

        return $bplan;
    }
    /*
    checkoutSession =
    {"id":"cs_test_a1pTb9QVE1rBYPOEYTWuWYQwgikZWdSkFBLV7wGF2aWvrClQwGdZwM7tZC","object":"checkout.session","after_expiration":null,"allow_promotion_codes":null,"amount_subtotal":300,"amount_total":348,"automatic_tax":{"enabled":false,"status":null},"billing_address_collection":null,"cancel_url":"http://wiseabc.test/student","client_reference_id":"7","consent":null,"consent_collection":null,"created":1682909401,"currency":"usd","currency_conversion":null,"custom_fields":[],"custom_text":{"shipping_address":null,"submit":null},"customer":"cus_NoPhIjAI6Ft86r","customer_creation":"always","customer_details":{"address":{"city":null,"country":"MX","line1":null,"line2":null,"postal_code":null,"state":null},"email":"ritchie.devon@example.com","name":"Ritchie Devon","phone":null,"tax_exempt":"none","tax_ids":[]},"customer_email":"ritchie.devon@example.com","expires_at":1682995800,"invoice":"in_1N2mrpKYG3qD2MyssDee37dL","invoice_creation":null,"livemode":false,"locale":null,"metadata":{"billing_plan_id":"10"},"mode":"subscription","payment_intent":null,"payment_link":null,"payment_method_collection":"always","payment_method_options":null,"payment_method_types":["card"],"payment_status":"paid","phone_number_collection":{"enabled":false},"recovered_from":null,"setup_intent":null,"shipping_address_collection":null,"shipping_cost":null,"shipping_details":null,"shipping_options":[],"status":"complete","submit_type":null,"subscription":"sub_1N2mrpKYG3qD2MysMTaxS9Xz","success_url":"http://wiseabc.test/student/subscriptions/stripe/success?session_id={checkoutSession_ID}","total_details":{"amount_discount":0,"amount_shipping":0,"amount_tax":48},"url":null}
    */

    public function subscriptionCheckoutSuccess(Request $request)
    {
        //cs_test_a1pTb9QVE1rBYPOEYTWuWYQwgikZWdSkFBLV7wGF2aWvrClQwGdZwM7tZC
        if (empty($request->input('session_id'))) {
            return back(302, [], route('student.home'))->withErrors(['alert' => 'Falta identificador de sesión']);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $checkoutSession = \Stripe\Checkout\Session::retrieve($request->input('session_id'));
        if (! is_object($checkoutSession)) {
            return back(302, [], route('student.home'))->withErrors(['alert' => 'Ocurrio un problema al consultar StripeCheckout']);
        }

        $subscription = self::saveSubscriptionFromCheckoutSession($checkoutSession);

        return view('student.subscriptions.stripe.success', [
            'input' => $request->input(),
            'checkoutSession' => $checkoutSession,
            'subscription' => $subscription,
        ]);
    }

    public function customerPortalSession(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->input('session_id');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        if (! empty($sessionId)) {
            try {
                $checkoutSession = \Stripe\Checkout\Session::retrieve($request->input('session_id'));
            } catch (Throwable $e) {
                report($e);
                $errMsg = 'Ocurrio un problema al obtener la sesión de pago.';

                return $request->wantsJson() ? response(['error' => $errMsg], 400)
                    : back()->withErrors(['alert' => $errMsg]);
            }

            $user = self::saveCustomerIdFromCheckoutSession($checkoutSession);
        }
        if (empty($user->stripe) && empty($user->stripe->api_id)) {
            $errMsg = 'El usuario no está asociado con un ID de Stripe.';

            return $request->wantsJson() ? response(['error' => $errMsg], 400)
                : back()->withErrors(['alert' => $errMsg]);
        }

        // Authenticate your user.
        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $user->stripe->api_id,
            'return_url' => route('student.home'),
        ]);

        return redirect($session->url, 303);
    }

    public function webhooks(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $endpoint_secret = env('STRIPE_ENDPOINT_SECRET');
        //signing secret is whsec_c3937d791409c9275a0134e89e68565245242a9361ba62fbfe87176f9ba09e36

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (Throwable $e) {//\UnexpectedValueException $err
            $errMsg = '⚠️  Webhook error while parsing basic request.';

            return $request->wantsJson() ? response(['error' => $errMsg], 400)
                : back()->withError($errMsg);
        }

        Log::channel('stripe')->debug('Stripe Webhook', [
            'event_type' => $event->type,
            'event_data_object' => $event->data->object,
        ]);

        $subscription = null;
        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                $charge = $event->data->object;
                break;

            case 'checkout.session.completed':
                $session = $event->data->object;
                if ($session->mode == 'subscription' && ! empty($session->subscription)) {
                    $subscription = self::saveSubscriptionFromCheckoutSession($session);
                }
                //cs_test_a1nkYpWMSWi8Xj7OyLUdH60mp4p9jn4eDpfLygTjEHjRU5SSiQVBFOzzie
                break;

            case 'checkout.session.expired':
                $session = $event->data->object;
                break;

            case 'customer.created':
            case 'customer.updated':
                $customer = $event->data->object;
                self::updCustomerData($customer);
                break;

            case 'customer.subscription.created':
            case 'customer.subscription.deleted':
            case 'customer.subscription.paused':
            case 'customer.subscription.resumed':
            case 'customer.subscription.trial_will_end':
            case 'customer.subscription.updated':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                // Then define and call a method to handle the subscription being created.
                $subscription = self::updSubscriptionData($event->data->object);
                break;

            case 'invoice.created':
                $invoice = $event->data->object;
            case 'invoice.finalized':
                $invoice = $event->data->object;
            case 'invoice.paid':
                $invoice = $event->data->object;
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
            case 'invoice.updated':
                $invoice = $event->data->object;
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object;
            default:
                // Unexpected event type
                Log::channel('stripe')->info('Stripe Webhook: Unexpected event type', ['request' => $request]);
        }

        return response([], 200);
    }
}
