<?php

use App\Http\Controllers\PayPalController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
    /*
    $str = json_encode(['id'=>1,'name'=>"exa"]);
    return ($json = json_decode($str) )!==null ? ['json3'=>$json] : ['str'=>$str];
    */
    ob_start();

    $ritmo = 2;
    echo "Ritmo: {$ritmo} clases por semana\n";
    echo "  curso de ".(48 / $ritmo)."semanas\n";


    $inicio = Carbon::parse('2023-06-05','-0600');
    echo "inicio: ".$inicio->isoFormat("ddd DD MMMM Y")."\n";

    $fin = $inicio->copy()->addWeeks(48 / $ritmo);
    echo "fin: ".$fin->isoFormat("ddd DD MMMM Y")."\n\n";

    echo "ingreso: ".$inicio->isoFormat("DD MMMM Y")."\n";

    $period = \Carbon\CarbonPeriod::create( $inicio , '4 weeks', $fin->format("Y-m-d"));
    $period->excludeEndDate();

    $billings = $period->count();
    echo "billings: {$billings}\n";

    $lastBillingDate = $period->last();
    echo "last biling: ".$lastBillingDate->isoFormat("DD MMMM Y")."\n";


    echo PHP_EOL.PHP_EOL."**** IRREGULAR ****".PHP_EOL;

    $ingreso = Carbon::parse('2023-07-23','-0600');
    echo "ingreso: ".$ingreso->isoFormat("DD MMMM Y")."\n";

    $period = \Carbon\CarbonPeriod::create( $ingreso , '4 weeks', $fin->format("Y-m-d"));
    $period->excludeEndDate();

    $billings = $period->count();
    echo "billings: {$billings}\n";

    $lastBillingDate = $period->last();
    echo "last biling: ".$lastBillingDate->isoFormat("DD MMMM Y")."\n";

    $includedEndDate = $period->getIncludedEndDate();
    echo "endDate: ".$includedEndDate->isoFormat("DD MMMM Y")."\n";

    $endDatesDiff = $includedEndDate->diffInDays( $lastBillingDate );
    echo "diff days {$endDatesDiff}\n";

    ddd( ob_get_clean() );
    return true;
});

Route::get('/cursos', function(Request $request){
    return \App\Models\Curso::all();
});

Route::group(['prefix'=>'stripe','as'=>'api.stripe.'], function(){
    Route::any('/webhooks',[StripeController::class, 'webhooks'])->name('webhooks');
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


    Route::get('/paypal/order-details/{id}', [PayPalController::class, 'getOrderDetails']);

    Route::resource('subscriptions', SubscriptionController::class);
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