<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
  public function home(Request $request) {

		$Suscripciones = collect( config('wiseabc.suscripciones') );

		$hoy = now('-0600')->locale('es');
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
		$egresoAcumuladoMes = 0;

		$TeachersNotPaid = \App\Models\User::withWhereHas('attendances', function($query) use ($cortePasado) {
			$query->withSum('pconcepts as recibo_subtotal','attendance_pconcept.amount')->where('fechahora','<',$cortePasado)
			      ->whereNull('payment_id');
		});
		//echo vsprintf(str_replace(array('?'), array('\'%s\''), $TeachersNotPaid->toSql()), $TeachersNotPaid->getBindings());

		$TeachersNotPaid = $TeachersNotPaid->get();
		foreach($TeachersNotPaid AS $teacher) {
			//echo "{$teacher->name} \n";
			$teacher->saldo_pendiente = 0;
			foreach( $teacher->attendances AS $attendance ) {
				//echo "  #{$attendance->id} : {$attendance->fechahora} : $ {$attendance->recibo_subtotal}\n";
				$teacher->saldo_pendiente += $attendance->recibo_subtotal;
			}
			//echo "  saldo pendiente: $ {$teacher->saldo_pendiente}\n";
			//echo PHP_EOL;
			$egresoAcumuladoMes += $teacher->saldo_pendiente;
		}

		$cursos = \App\Models\Curso::all();
		return view('admin.dashboard',[
			'arrCursos'=>$cursos,
			'sinClase'=>$sinClase,
			'finmes'=>$finmes->isoFormat('ddd DD MMMM'),
			'cortePasado'=>$cortePasado->isoFormat('DD MMMM'),
			'ingresosAcumulados'=>$ingresosAcumulados,
			'egresoAcumuladoMes'=>$egresoAcumuladoMes,
			'egresoEstimadoMes'=>$egresoEstimadoMes,
			'TeachersNotPaid'=>$TeachersNotPaid
		]);
  }
}
