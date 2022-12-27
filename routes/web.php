<?php
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
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

Route::get('/', function (Request $request) {
	$user = $request->user();

	if ($user->user_type===2 ) {
		return redirect()->route('student.home');
	}
	else if ( $user->user_type==3 ) {
		return redirect()->route('teacher.home');
	}
})->middleware('auth')->name('home');

Route::get('/registro-profesor', function () {
	return view('teacher.registro');
})->name('regprof');

Route::post('/registro-profesor', [TeacherController::class, 'registro'])->name('regprof');

Route::get('/gracias-profesor', function(){
	return view('teacher.gracias-registro');
})->name('gracias-profesor');

Route::get('salir', function(){
	\Illuminate\Support\Facades\Auth::logout();
	return redirect('login');
})->name('logout');

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

	Route::get('dashboard', function(){
		return view('admin.dashboard');
	})->name('home');

	Route::get('pago/profesor/{profeid}', function(){
		return view('admin.payments.paraprofe');
	})->name('pagoparaprofe');

	Route::get('classroom/createforcurso/{curso}', [\App\Http\Controllers\Admin\ClassroomController::class, 'createForCurso'])->name('classroom.createforcurso');

	Route::get('classroom/createforteacher/{teacher}', [\App\Http\Controllers\Admin\ClassroomController::class, 'createForTeacher'])->name('classroom.createforteacher');

	Route::post('classroom/createforteacher/{teacher}', [\App\Http\Controllers\Admin\ClassroomController::class, 'createForTeacher2'])->name('classroom.createforteacher');

	Route::get('student/{student}/assignclass', [\App\Http\Controllers\Admin\StudentController::class, 'assignClassroom'])->name('student.assignclass');

	Route::post('student/{student}/assignclass', [\App\Http\Controllers\Admin\StudentController::class, 'assignClassroom2'])->name('student.assignclass');


	Route::resource('cursos', \App\Http\Controllers\Admin\CursoController::class);

	Route::resource('teacher', \App\Http\Controllers\Admin\TeacherController::class);

	Route::resource('classroom', \App\Http\Controllers\Admin\ClassroomController::class);

	Route::resource('student', \App\Http\Controllers\Admin\StudentController::class);

});