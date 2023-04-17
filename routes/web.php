<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::get('/', function (Request $request) {
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
})->middleware('auth')->name('home');

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

Route::get('clases/disponibles', [ClassroomController::class, 'disponibles'])->name('clases.disponibles');

Route::resource('clases', ClassroomController::class);

/**
 * Estudante
 */
Route::group(['prefix'=>'student','as'=>'student.','middleware' => ['auth','student']], function(){

	Route::get('home', [StudentController::class, 'home'])->name('home');

	Route::get('elegir-suscripcion', [StudentController::class, 'elegirSuscripcion'])->name('elegir-suscripcion');

	Route::post('elegir-suscripcion', [StudentController::class, 'suscribe'])->name('suscribe');

	Route::get('pagos', [StudentController::class, 'pagos'])->name('pagos');

	Route::get('perfil', [StudentController::class, 'perfil'])->name('perfil');
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

	Route::get('pagos/profesor/{id}', [\App\Http\Controllers\Admin\PaymentController::class, 'paraprofe'])->name('pagoparaprofe');

	Route::get('student/{student}/assignclass', [\App\Http\Controllers\Admin\StudentController::class, 'assignClassroom'])->name('student.assignclass');

	Route::post('student/{student}/assignclass', [\App\Http\Controllers\Admin\StudentController::class, 'assignClassroom2']);

	Route::resource('classroom', \App\Http\Controllers\Admin\ClassroomController::class);

	Route::resource('cursos', \App\Http\Controllers\Admin\CursoController::class);

	Route::resource('pagos', \App\Http\Controllers\Admin\PaymentController::class);

	Route::resource('student', \App\Http\Controllers\Admin\StudentController::class);

	Route::resource('teacher', \App\Http\Controllers\Admin\TeacherController::class);

});
