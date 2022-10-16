<?php

use App\Models\User;
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

Route::get('/', function () {
	$user = Auth::user();

	if ($user->user_type===2 && $user->status===1) {
		return redirect()->route('elegir-suscripcion');
	}

	return view('home',['user'=>$user]);
})->middleware('auth')->name('home');

Route::get('/elegir-suscripcion', function(){
	$user = Auth::user();
	if ( $user->user_type!=2 ) {
		return redirect()->route('home');
	}
	return view('student.elegir-suscripcion');
})->middleware('auth')->name('elegir-suscripcion');

Route::post('/elegir-suscripcion', function(Request $request){

	$user = Auth::user();

	$user->status = 2;
	$user->usermetas()->create([
		'metakey' => 'suscripcion',
		'metaval' => $request->suscripcion
	]);
	$user->save();

	return redirect()->route('home');

})->middleware('auth')->name('elegir-suscripcion');

Route::get('/registro-profesor', function () {
	return view('teacher.registro');
})->name('regprof');

Route::post('/registro-profesor', [TeacherController::class, 'registro'])->name('regprof');

Route::get('/gracias-profesor', function(){
	return view('teacher.gracias-registro');
})->name('gracias-profesor');

Route::get('/salir', function(){
	\Illuminate\Support\Facades\Auth::logout();
	return redirect('/login');
})->name('logout');

/**
 * ADMINISTRADOR
 */
Route::group(['prefix'=>'admin','as'=>'admin.','middleware' => ['auth','admin']], function(){

	Route::get('dashboard', function(){
		return view('admin.dashboard');
	})->name('home');

});