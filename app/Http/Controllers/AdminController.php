<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
  public function home(Request $request) {

		$Suscripciones = collect( config('wiseabc.suscripciones') );

		$hoy = now('America/Mexico_City')->locale('es');
		$finmes = $hoy->copy()->endOfMonth();
		$cortePasado = $hoy->copy()->subMonth()->endOfMonth();

		/**
		 * Ingresos acumulados
		 */
		$ingresosAcumulados = 0;
		$Students = \App\Models\User::where('user_type','2')->has('classrooms')->get();
		foreach($Students AS $student) {
			$susc = $Suscripciones->firstWhere('id', $student->suscripcion);

			if ( empty($susc) )  continue;

			$ingresosAcumulados += $susc['precio'];
		}

		/**
		 * Egresos acumulados al corte
		 */
		$builder = DB::table('attendances')
		->join('receipts', function($join){
			$join->on('attendances.id','=','receipts.attendance_id')
					->whereNull('receipts.payment_id');
		})
		->selectRaw('SUM(receipts.`amount`) as amount')
		->where('fechahora','>',$cortePasado);
		//$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $builder->toSql()), $builder->getBindings()); dd($sql);
		$egresoAcumuladoMes = $builder->first()->amount;

		/**
		 * Egresos estimados a fin de mes
		 */
		$egresoEstimadoMes = 0;
		$Classrooms = \App\Models\Classroom::where('status','active')->get();
		foreach($Classrooms as $classroom) {

			$costo_clase = 0;

			$result = DB::table('payment_concepts')->select('amount')->where('concept','like','base')->first();
			//echo "result ".print_r($result,true ).PHP_EOL;
			if ( !empty($result) && is_object($result) ) {
				$costo_clase += $result->amount;
			}

			$result = DB::table('payment_concepts')->select('amount')->where('concept','like','Puntualidad')->first();
			//echo "result ".print_r($result,true ).PHP_EOL;
			if ( !empty($result) && is_object($result) ) {
				$costo_clase += $result->amount;
			}

			if ($classroom->tipo==1) {
				$result = DB::table('payment_concepts')->select('amount')->where('concept','like','grupal')->first();
				if ( !empty($result) && is_object($result) ) {
					$costo_clase += $result->amount * ( $classroom->students->count() -1 );
				}
			}

			if ( $hoy->diffInMonths( $classroom->teacher->created_at )>=3 ) {
				$result = DB::table('payment_concepts')->select('amount')->where('concept','like','Lealtad')->first();
				//echo "result ".print_r($result,true ).PHP_EOL;
				if ( !empty($result) && is_object($result) ) {
					$costo_clase += $result->amount;
				}
			}

			$clasesporsemana = $classroom->ritmo * 4;

			$egresoEstimadoMes += $costo_clase * $clasesporsemana;
		}

		/**
		 * Alumnos sin classroom
		 */
		$sinClase = \App\Models\user::where('user_type','2')->doesntHave('classrooms')->count();

		/**
		 * Recibos pendientes mes pasado
		 */
		$builder = DB::table('attendances')
		->join('receipts', function($join){
			$join->on('attendances.id','=','receipts.attendance_id')
					->whereNull('receipts.payment_id');
		})
		->selectRaw('user_id, SUM(receipts.`amount`) as amount')
		->where('fechahora','<',$cortePasado)
		->groupBy('user_id')->orderByDesc('amount');
		//$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $builder->toSql()), $builder->getBindings()); dd($sql);
		$RecibosPendientes = $builder->get();

		$cursos = \App\Models\Curso::all();
		return view('admin.dashboard',[
			'arrCursos'=>$cursos,
			'sinClase'=>$sinClase,
			'finmes'=>$finmes->isoFormat('ddd DD MMMM'),
			'cortePasado'=>$cortePasado->isoFormat('DD MMMM'),
			'ingresosAcumulados'=>$ingresosAcumulados,
			'egresoAcumuladoMes'=>$egresoAcumuladoMes,
			'egresoEstimadoMes'=>$egresoEstimadoMes,
			'RecibosPendientes'=>$RecibosPendientes
		]);
  }
}
