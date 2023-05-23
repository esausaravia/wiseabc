<?php

use App\Http\Controllers\PayPalController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function(Request $request){

    return base_path('public/img/wiseabc-logo-375x.png');
});

Route::get('client-ip', function(Request $request){

    /*
    $_SERVER['HTTP_CF_CONNECTING_IP'];
    $_SERVER['HTTP_X_FORWARDED_FOR'];
    $_SERVER['REMOTE_ADDR'];
    */

    $ip = !empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
    return ['ip' => $ip];
});

Route::get('/cursos', function(Request $request){
    return \App\Models\Curso::all();
});

Route::middleware('auth:sanctum')->group(function(){

    Route::get('/token/create', function(Request $request){

        if ( empty($request->user()) ) {
            return response()->json([
                'message'=>'Debe iniciar sesión'
            ],400);
        }

        $token_name = !empty($request->token_name) ? $request->token_name : 'localhost';

        return $request->user()->createToken($token_name);

        $token = $request->user()->createToken($token_name);
        return ['token' => $token->plainTextToken];
    });

    Route::get('/user',function(Request $request){
        return $request->user();
    });

    Route::resource('subscriptions', SubscriptionController::class);

    Route::get('/paypal/order-details/{id}', [PayPalController::class, 'getOrderDetails']);

});

/**
 * Estudante
 *
 */
Route::group(['prefix'=>'student','as'=>'api.student.','middleware' => ['auth','student']], function(){

    Route::get('/paypal/get-user-token', function(Request $request){
        return PayPalController::getUserToken();
    })->name('paypal.get-user-token');

    Route::post('/paypal/orders', [PayPalController::class, 'createStudentOrder'])->name('paypal.orders.create');

    Route::post('/paypal/orders/{id}/capture', [PayPalController::class, 'captureStudentOrder'])->name('paypal.orders.capture');

	//Route::get('home', [StudentController::class, 'home'])->name('home');
});

/**
 * Teacher
 */
Route::group(['prefix'=>'teacher','as'=>'api.teacher.','middleware' => ['auth','teacher']], function(){

	//Route::get('home', [TeacherController::class, 'home'])->name('home');
});

/**
 * Admin
 */
Route::group(['prefix'=>'admin','as'=>'api.admin.','middleware' => ['auth:sanctum','admin']], function(){
    Route::get('/curso/{id}/alumnos-sin-clase', function(Request $request, $id){
        $curso = App\Models\Curso::find($id);

        return response()->json( $curso->alumnosSinClase() );
    });
    Route::get('/curso/{id}/alumnos-sin-clase-nums', function(Request $request, $id){
        $curso = App\Models\Curso::find($id);

        return response()->json( $curso->alumnosSinClaseNums() );
    });
});

/**
 * Webhooks
 */
Route::group(['prefix'=>'stripe','as'=>'api.stripe.'], function(){
    Route::any('webhooks',[StripeController::class, 'webhooks'])->name('webhooks');
});

Route::group(['prefix'=>'webhooks','as'=>'webhooks.'],function(){
    Route::any('paypal', [PayPalController::class, 'webhooks'])->name('paypal');
});