<?php

use Illuminate\Http\Request;
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


});

Route::group(['prefix'=>'admin','middleware' => ['auth:sanctum','admin']], function(){
    Route::get('/curso/{id}/alumnos-sin-clase', function(Request $request, $id){
        $curso = App\Models\Curso::find($id);

        return response()->json( $curso->alumnosSinClase() );
    });
    Route::get('/curso/{id}/alumnos-sin-clase-nums', function(Request $request, $id){
        $curso = App\Models\Curso::find($id);

        return response()->json( $curso->alumnosSinClaseNums() );
    });
});