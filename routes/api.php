<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

        $requser = $request->user();
        if ( empty($requser) ) {
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