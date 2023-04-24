<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PayPalController extends Controller
{
    public static $token = null;

    public static function getFileToken(){

        $fileToken = Storage::get('PayPalToken.txt');

        if ( empty($fileToken) ) {
            return null;
        }

        $fileToken = json_decode($fileToken);

        if ( empty($fileToken) || !is_object($fileToken) || empty($fileToken->expires_at) ) {
            return null;
        }

        $expires_at = \Carbon\Carbon::parse($fileToken->expires_at);

        if ( !is_object($expires_at) ) {
            return null;
        }

        if ( $expires_at->subSeconds(30)->lessThan( now() ) ) {
            return null;
        }

        self::$token = $fileToken;
        return $fileToken;
    }

    /**
     *
     */
    public static function reqBearerToken(){
        Log::info('PayPalController::reqBearerToken');

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
        catch(\Exception $ex ) {
            Log::error($ex->__toString());
            return false;
        }

        /**
         * Response example:
         * {"scope": "...",
         * "access_token": "A21AAFEpH4PsADK7qSS7pSRsgzfENtu-Q1ysgEDVDESseMHBYXVJYE8ovjj68elIDy8nF26AwPhfXTIeWAZHSLIsQkSYz9ifg",
         * "token_type": "Bearer",
         * "app_id": "APP-80W284485P519543T",
         * "expires_in": 31668,
         * "nonce": "2020-04-03T15:35:36ZaYZlGvEkV4yVSz8g6bAKFoGSEzuy3CQcz3ljhibkOHg"}
         */
        $filecontent = $response->body();
        Storage::put('PayPalToken.txt', $filecontent );

        self::$token = $response->object();
        self::$token->expires_at = now()->addSeconds( self::$token->expires_in )->format('Y-m-d H:i:s');

        return self::$token;
    }


    public static function getAccessToken(){

        if ( empty(self::$token) || !is_object(self::$token) || empty(self::$token->access_token) )
        {
            if ( self::getFileToken()==null ) {
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
        catch(\Exception $ex ) {
            Log::error($ex->__toString());
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
        catch(\Exception $ex ) {
            Log::error($ex->__toString());
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
        catch(\Exception $ex ) {
            Log::error($ex->__toString());
            return false;
        }
        return $returnBody ? $response->body() : $response->object() ;
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
        catch(\Exception $ex ) {
            Log::error($ex->__toString());
            return false;
        }
        return $returnBody ? $response->body() : $response->object() ;
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
