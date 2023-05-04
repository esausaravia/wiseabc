<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StripeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function(Request $request){
	$authuser = Auth::user();
	return [
			'auth_user'=>$authuser,
			'request_user'=>$request->user(),
			'now'=>now()
	];

	ob_start();
	//$hoy = now('-0600')->locale('es');

	ddd( ob_get_clean() );
})->name('test');

Route::get('/', function (Request $request)
{
	$user = $request->user();

	if ($user->user_type===1 ) {
		return redirect()->route('admin.home');
	}
	elseif ($user->user_type===2 ) {
		return redirect()->route('student.home');
	}
	else if ( $user->user_type==3 ) {
		return redirect()->route('teacher.home');
	}
})->middleware(['auth'])->name('home');

Route::get('registro-profesor', function () {
	return view('teacher.registro');
})->name('regprof');

Route::post('registro-profesor', [TeacherController::class, 'registro']);

Route::get('/gracias-profesor', function(){
	return view('teacher.gracias-registro');
})->name('gracias-profesor');

Route::get('salir', function(){
	\Illuminate\Support\Facades\Auth::logout();
	return redirect('login');
})->name('salir');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
	$request->fulfill();

	return redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::get('clases/disponibles', [ClassroomController::class, 'disponibles'])->name('clases.disponibles');

Route::resource('clases', ClassroomController::class);

Route::get('/testerror', function(Request $request)
{
	$errMsg = "⚠️  Webhook error while parsing basic request.";

	return $request->wantsJson() ? response(['error'=>$errMsg], 400)
                : back()->withErrors(['message'=>$errMsg ]);
});

/**
 * Estudante
 */
Route::group(['prefix'=>'student','as'=>'student.','middleware' => ['auth','student']], function(){

	Route::get('home', [StudentController::class, 'home'])->name('home');

	Route::get('elegir-ritmo', [StudentController::class, 'elegirRitmo'])->name('elegir-ritmo');

	Route::post('elegir-ritmo', [StudentController::class, 'postElegirRitmo'])->name('post-elegir-ritmo');

	//Route::post('subscribe', [StudentController::class, 'subscribe']);

	Route::get('pagos', [StudentController::class, 'pagos'])->name('pagos');

	Route::get('perfil', [StudentController::class, 'perfil'])->name('perfil');

	Route::post('/subscriptions/stripe/create-checkout-session', [StripeController::class, 'subscriptionCheckoutSession'])->name('subscriptions.stripe.create-checkout-session');

	Route::get('/subscriptions/stripe/success', [StripeController::class, 'subscriptionCheckoutSuccess'])->name('subscriptions.stripe.success');

	Route::post('/stripe/create-portal-session', [StripeController::class,'customerPortalSession'])->name('stripe.create-portal-session');

	Route::resource('subscriptions', SubscriptionController::class);
});

/**
 * Teacher
 */
Route::group(['prefix'=>'teacher','as'=>'teacher.','middleware' => ['auth','teacher']], function(){

	Route::get('home', [TeacherController::class, 'home'])->name('home');

	Route::get('pagos', [TeacherController::class, 'pagos'])->name('pagos');

	Route::get('perfil', [TeacherController::class, 'perfil'])->name('perfil');

	Route::post('perfil', [TeacherController::class, 'update'])->name('update');

	Route::post('perfil-updreq', [TeacherController::class, 'profileUpdateRequest'])->name('perfil-updreq');
});

/**
 * ADMINISTRADOR
 */
Route::group(['prefix'=>'admin','as'=>'admin.','middleware' => ['auth','admin']], function(){

	Route::get('/', function(){
		return redirect()->route('admin.home');
	});

	Route::get('dashboard', [AdminController::class, 'home'])->name('home');

	Route::get('classroom/createforcurso/{curso}', [\App\Http\Controllers\Admin\ClassroomController::class, 'createForCurso'])->name('classroom.createforcurso');

	Route::get('classroom/createforteacher/{teacher}', [\App\Http\Controllers\Admin\ClassroomController::class, 'createForTeacher'])->name('classroom.createforteacher');

	Route::get('classroom/{id}/assignStudents', [\App\Http\Controllers\Admin\ClassroomController::class, 'assignStudents'])->name('classroom.assignStudents');

	Route::post('classroom/{id}/assignStudents', [\App\Http\Controllers\Admin\ClassroomController::class, 'assignStudents2']);

	Route::get('pagos/profesor/{id}', [\App\Http\Controllers\Admin\PayoutController::class, 'paraprofe'])->name('pagoparaprofe');

	Route::get('student/{student}/assignclass', [\App\Http\Controllers\Admin\StudentController::class, 'assignClassroom'])->name('student.assignclass');

	Route::post('student/{student}/assignclass', [\App\Http\Controllers\Admin\StudentController::class, 'assignClassroom2']);

	Route::resources([
		'classroom' => \App\Http\Controllers\Admin\ClassroomController::class,
		'cursos' => \App\Http\Controllers\Admin\CursoController::class,
		'pagos' => \App\Http\Controllers\Admin\PayoutController::class,
		'student' => \App\Http\Controllers\Admin\StudentController::class,
		'teacher' => \App\Http\Controllers\Admin\TeacherController::class
	]);

});
