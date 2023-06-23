<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WiseabcController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
Route::get('/', function (Request $request)
{
	$user = $request->user();

	if ($user->user_type===1 ) {
		return redirect()->route('admin.home');
	}
	elseif ($user->user_type===2 && ($user->clase_tipo===null || $user->ritmo===null) ) {
		return redirect()->route('student.elegir-ritmo');
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

/*
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
	$request->fulfill();

	return redirect()->route('home');
})->middleware(['signed'])->name('verification.verify');
*/

Route::get('salir', function(){
	\Illuminate\Support\Facades\Auth::logout();
	return redirect('login');
})->name('salir');

/**
 * Estudante
 */
Route::group(['prefix'=>'student','as'=>'student.','middleware' => ['auth','student']], function() {

	Route::get('', [StudentController::class, 'home'])->name('home');

	Route::get('elegir-ritmo', [StudentController::class, 'elegirRitmo'])->name('elegir-ritmo');

	Route::post('elegir-ritmo', [StudentController::class, 'postElegirRitmo'])->name('post-elegir-ritmo');

	//Route::post('subscribe', [StudentController::class, 'subscribe']);

	Route::get('pagos', [StudentController::class, 'pagos'])->name('pagos');

	Route::get('perfil', [StudentController::class, 'perfil'])->name('perfil');

	Route::match(['PUT','PATCH'], 'perfil', [StudentController::class, 'update'])->name('update');

	Route::patch('perfil/metas', [StudentController::class, 'updateMetas'])->name('update-metas');

	Route::resource('subscriptions', SubscriptionController::class);

	Route::post('stripe/create-checkout-session', [StripeController::class, 'subscriptionCheckoutSession'])->name('stripe.create-checkout-session');

	Route::get('stripe/success', [StripeController::class, 'subscriptionCheckoutSuccess'])->name('stripe.success');

	Route::any('stripe/create-portal-session', [StripeController::class,'customerPortalSession'])->name('stripe.create-portal-session');
});

/**
 * Teacher
 */
Route::group(['prefix'=>'teacher','as'=>'teacher.','middleware' => ['auth','teacher']], function() {

	Route::get('', [TeacherController::class, 'home'])->name('home');

	Route::get('pagos', [TeacherController::class, 'pagos'])->name('pagos');

	Route::get('perfil', [TeacherController::class, 'perfil'])->name('perfil');

	Route::post('perfil', [TeacherController::class, 'update'])->name('update');

	Route::post('perfil-updreq', [TeacherController::class, 'profileUpdateRequest'])->name('perfil-updreq');
});

/**
 * ADMINISTRADOR
 */
Route::group(['prefix'=>'admin','as'=>'admin.','middleware' => ['auth','admin']], function() {

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

/**
 * test routes
 */
Route::get('carbon', function(Request $request){
	ob_start();

	$ritmo = 3;
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
})->name('test');

Route::get('colors', function(Request $request) {
	return view('colors');
});

Route::get('msapi', function(Request $request) {
	$msapi = new \App\Http\Controllers\Admin\MsApiController();

	$userMsId = 'fba3c228-3a07-42f1-8722-63958f8a81e9';
	/**
	 *
	 */
	return $msapi->getUserId('mariojimenez@wiseabcenglish.com');

	$userMsId = 'fba3c228-3a07-42f1-8722-63958f8a81e9';
	$meetingId = 'MSpmYmEzYzIyOC0zYTA3LTQyZjEtODcyMi02Mzk1OGY4YTgxZTkqMCoqMTk6bWVldGluZ19ZVFl3WWpJeVl6WXRPR1l6TXkwMFl6TXpMV0l5TlRFdE56QXlNR000WmpNelpHSmtAdGhyZWFkLnYy';

	return $msapi->getAttendanceReportsList($userMsId, $meetingId);

	$attendanceReportId = "80e65d43-4180-4723-98eb-a115e5ed150a";

	return $msapi->getAttendanceReport( $userMsId, $meetingId, $attendanceReportId );

	return [];
});