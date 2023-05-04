<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PayPalController extends Controller
{
    public static $token = null;

    public static function getFileToken()
    {
        $fileToken = Storage::get('PayPalToken.txt');

        if ( empty($fileToken) ) {
            return false;
        }

        $fileToken = json_decode($fileToken);

        if ( !is_object($fileToken) || empty($fileToken->expires_at) ) {
            return false;
        }

        $expires_at = \Carbon\Carbon::parse($fileToken->expires_at);

        if ( !is_object($expires_at) ) {
            return false;
        }

        if ( $expires_at->subSeconds(30)->lessThan( now() ) ) {
            return false;
        }

        self::$token = $fileToken;
        return $fileToken;
    }

    /**
     * Obtiene el App Access Token
     * @return Object|false
     */
    public static function reqBearerToken()
    {
        Log::debug(__METHOD__);
        $url = env('PAYPAL_API_BASE_URL').'/v1/oauth2/token';
        $authUser = env('PAYPAL_APP_CLIENT_ID');
        $authSecret = env('PAYPAL_APP_SECRET');

        /**
         * asForm = application/x-www-form-urlencoded
         */
        try {
            $response = Http::throw()->withBasicAuth( $authUser , $authSecret)->asForm()->post($url,[
                'grant_type' => 'client_credentials'
            ]);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        /*
        $response = Http::withBasicAuth( $authUser , $authSecret)->asForm()->post($url,[
            'grant_type' => 'client_credentials'
        ]);

        if ( $response->failed() )
        {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers' => $ex->response->headers(),
                'response_body' => $ex->response->body()
            ]);
            return false;
        }
        */

        /**
         * Response example:
         * {"scope": "...",
         * "access_token": "A21AAFEpH4PsADK7qSS7pSRsgzfENtu-Q1ysgEDVDESseMHBYXVJYE8ovjj68elIDy8nF26AwPhfXTIeWAZHSLIsQkSYz9ifg",
         * "token_type": "Bearer",
         * "app_id": "APP-80W284485P519543T",
         * "expires_in": 31668,
         * "nonce": "2020-04-03T15:35:36ZaYZlGvEkV4yVSz8g6bAKFoGSEzuy3CQcz3ljhibkOHg"}
         */
        //$filecontent = $response->body();

        self::$token = $response->object();
        self::$token->expires_at = now()->addSeconds( self::$token->expires_in )->format('Y-m-d H:i:s');
        Storage::put('PayPalToken.txt', json_encode(self::$token) );

        return self::$token;
    }


    public static function getAccessToken()
    {

        if ( !is_object(self::$token) || empty(self::$token->access_token) )
        {
            if( self::getFileToken()===false )
            {
                self::reqBearerToken();
            }
        }

        return self::$token->access_token;
    }

    public static function getListPlans($params=[],$returnBody=false)
    {
        $url = env('PAYPAL_API_BASE_URL').'/v1/billing/plans';

        $defaults = [
            'page_size'=>20
        ];
        $queryParams = array_merge($defaults, $params);

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->get($url, $queryParams);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        return $returnBody ? $response->body() : $response->object() ;
    }

    public static function getPlanDetails($id="",$returnBody=false)
    {
        $url = env('PAYPAL_API_BASE_URL').'/v1/billing/plans/'.$id;

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->get($url);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        return $returnBody ? $response->body() : $response->object() ;
    }

    public static function getSubscriptionDetails($id, $returnBody=false)
    {
        $url = env('PAYPAL_API_BASE_URL').'/v1/billing/subscriptions/'.$id;

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->get($url);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        $respBody = $response->body();
        return $returnBody ? $respBody : $response->object() ;
    }

    public static function getListSubscriptionTransactions($id, $returnBody=false)
    {
        $url = env('PAYPAL_API_BASE_URL')."/v1/billing/subscriptions/{$id}/transactions";

        //?start_time=2018-01-21T07:50:20.940Z&end_time=2018-08-21T07:50:20.940Z
        // pattern = ^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$
        $end_time = now();
        $data = [
            'start_time'=>$end_time->copy()->subYear()->format("Y-m-d\TH:i:s\Z"),
            'end_time'=>$end_time->format("Y-m-d\TH:i:s\Z")
        ];

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->get($url, $data);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        return $returnBody ? $response->body() : $response->object() ;
    }

    public static function getOrderDetails($id, $returnBody=false)
    {
        $url = env('PAYPAL_API_BASE_URL')."/v2/checkout/orders/{$id}";

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->get($url);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        return $returnBody ? $response->body() : $response->object() ;
    }

    public static function updSubscription($id="I-6E1X6ADN8HY6", $returnBody=false)
    {
        //I-6E1X6ADN8HY6
        $url = env('PAYPAL_API_BASE_URL').'/v1/billing/subscriptions/'.$id;

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->patch($url, [
                [
                    "op"=>"replace",
                ]
            ]);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        $respBody = $response->body();
        return $returnBody ? $respBody : $response->object() ;
    }

    public static function getUserToken() {

        $url = env('PAYPAL_API_BASE_URL').'/v1/oauth2/token';
        $authUser = env('PAYPAL_APP_CLIENT_ID');
        $authSecret = env('PAYPAL_APP_SECRET');

        /**
         * asForm = application/x-www-form-urlencoded
         */
        try {
            $response = Http::throw()->withBasicAuth( $authUser , $authSecret)->asForm()->post($url,[
                'grant_type' => 'client_credentials',
                'response_type' => 'id_token'
            ]);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers'=>$ex->response->headers(),
                'response_body'=>$ex->response->body()
            ]);
            return false;
        }
        $respBody = $response->body();
        Log::debug(__METHOD__,[
            'response_status' => $response->status(),
            'response_headers' => $response->headers(),
            'response_body' => $respBody
        ]);

        /**
         * Response example:
         * {"scope": "...",
         * "access_token": "A21AAFEpH4PsADK7qSS7pSRsgzfENtu-Q1ysgEDVDESseMHBYXVJYE8ovjj68elIDy8nF26AwPhfXTIeWAZHSLIsQkSYz9ifg",
         * "token_type": "Bearer",
         * "app_id": "APP-80W284485P519543T",
         * "expires_in": 31668,
         * "nonce": "2020-04-03T15:35:36ZaYZlGvEkV4yVSz8g6bAKFoGSEzuy3CQcz3ljhibkOHg"}
         */
        //$filecontent = $response->body();
        $return = $response->object();

        return [
            'token'=> $return->id_token,
            'expires_in' => $return->expires_in
        ];
    }

    public function createStudentOrder(Request $request)
    {
        $valid = $request->validate([
            'source'=>'required'
        ]);
        $user = $request->user();

        $url = env('PAYPAL_API_BASE_URL').'/v2/checkout/orders';

        try {
            $response = Http::throw()->withHeaders([
                'PayPal-Request-Id' => uuid_create()
                ])->withToken( self::getAccessToken() )->post($url,[
                "intent" => "CAPTURE",
                "application_context" => [
                    "shipping_preference" => "NO_SHIPPING"
                ],
                "purchase_units"=> [[
                    "description" => "Wise ABC English clases en linea",
                    "items" => [[
                        "name" => "Grupo relax",
                        "quantity" => "4",
                        "description" => "1 clase por semana",
                        "category" => "DIGITAL_GOODS",
                        "unit_amount" => [
                            "currency_code"=> "USD",
                            "value"=> "9.00"
                        ],
                        "tax" => [
                            "currency_code"=> "USD",
                            "value"=> "1.44"
                        ]
                    ]],
                    "amount"=> [
                        "currency_code"=> "USD",
                        "value"=> "41.76",
                        "breakdown" => [
                            "item_total" => [
                                "currency_code"=> "USD",
                                "value"=> "36.00"
                            ],
                            "tax_total" => [
                                "currency_code"=> "USD",
                                "value"=> "5.76"
                            ]
                        ]
                    ]
                ]],
                "payment_source"=> [
                    "paypal" => [
                        "attributes"=> [
                            "vault"=> [
                                "store_in_vault"=> "ON_SUCCESS",
                                "usage_type"=> "MERCHANT",
                                "customer_type"=> "CONSUMER"
                            ]
                        ],
                        "experience_context"=> [
                            "return_url"=> "https://wiseabcenglish.com//student/home",//https://example.com/cancelUrl?token=30D69261CE576650R
                            "cancel_url"=> "https://wiseabcenglish.com//student/home" //https://example.com/cancelUrl?token=30D69261CE576650R
                        ]
                    ]
                ]
            ]);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers' => $ex->response->headers(),
                'response_body' => $ex->response->body()
            ]);
            return false;
        }
        return $response->object();
    }

    public function captureStudentOrder(Request $request, $id)
    {
        Log::debug(__METHOD__);
        $user = $request->user();

        $url = env('PAYPAL_API_BASE_URL')."/v2/checkout/orders/{$id}/capture";

        try {
            $response = Http::throw()->withToken( self::getAccessToken() )->withBody('{}','application/json')->post($url);
        }
        catch(RequestException $ex ) {
            Log::error(__METHOD__.' Http RequestException:', [
                'response_stauts' => $ex->response->status(),
                'response_headers' => $ex->response->headers(),
                'response_body' => $ex->response->body()
            ]);
            return false;
        }
        return $response->object();
    }
    /**
     * POST with headers
     *
     * $response = Http::withHeaders([
     *     'X-First' => 'foo',
     *     'X-Second' => 'bar'
     * ])->post('http://example.com/users', [
     *     'name' => 'Taylor',
     * ]);
     */

    /**
     * POST with Bearer Tokens
     * $response = Http::withToken('token')->post([...]);
     */
}
