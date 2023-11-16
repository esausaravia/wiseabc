<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function home(Request $request)
    {

        $hoy = now()->locale('es');
        $cortePasado = now()->subMonth()->endOfMonth();

        /**
         * Ingresos acumulados
         */
        $ingresosAcumulados = DB::table('payments')->where('status', 'like', 'paid')->where('created_at', '>', $cortePasado)->selectRaw('SUM(payments.amount) AS `subtotal`')->get()->first()->subtotal;

        /**
         * Egresos estimados a fin de mes
         */
        $egresoEstimadoMes = 0;
        $Classrooms = \App\Models\Classroom::where('status', 'active')->get();
        foreach ($Classrooms as $classroom) {

            $costo_clase = 0;

            $result = DB::table('payment_concepts')->select('amount')->where('concept', 'like', 'base')->first();
            //echo "result ".print_r($result,true ).PHP_EOL;
            if (! empty($result) && is_object($result)) {
                $costo_clase += $result->amount;
            }

            $result = DB::table('payment_concepts')->select('amount')->where('concept', 'like', 'Puntualidad')->first();
            //echo "result ".print_r($result,true ).PHP_EOL;
            if (! empty($result) && is_object($result)) {
                $costo_clase += $result->amount;
            }

            if ($classroom->tipo == 1) {
                $result = DB::table('payment_concepts')->select('amount')->where('concept', 'like', 'grupal')->first();
                if (! empty($result) && is_object($result)) {
                    $costo_clase += $result->amount * ($classroom->students->count() - 1);
                }
            }

            if ($hoy->diffInMonths($classroom->teacher->created_at) >= 3) {
                $result = DB::table('payment_concepts')->select('amount')->where('concept', 'like', 'Lealtad')->first();
                //echo "result ".print_r($result,true ).PHP_EOL;
                if (! empty($result) && is_object($result)) {
                    $costo_clase += $result->amount;
                }
            }

            $clasesporsemana = $classroom->ritmo * 4;

            $egresoEstimadoMes += $costo_clase * $clasesporsemana;
        }

        /**
         * Alumnos sin classroom
         */
        $sinClase = \App\Models\user::where('user_type', '2')->doesntHave('classrooms')->count();

        /**
         * Recibos pendientes mes pasado
         */
        $egresoAcumuladoMes = 0;

        $TeachersNotPaid = \App\Models\User::withWhereHas('attendances', function ($query) use ($cortePasado) {
            $query->withSum('pconcepts as recibo_subtotal', 'attendance_pconcept.amount')
                ->where('fechahora', '<', $cortePasado)
                ->whereNull('payout_id');
        });
        //echo vsprintf(str_replace(array('?'), array('\'%s\''), $TeachersNotPaid->toSql()), $TeachersNotPaid->getBindings());

        $TeachersNotPaid = $TeachersNotPaid->get();
        foreach ($TeachersNotPaid as $teacher) {
            //echo "{$teacher->name} \n";
            $teacher->saldo_pendiente = 0;
            foreach ($teacher->attendances as $attendance) {
                //echo "  #{$attendance->id} : {$attendance->fechahora} : $ {$attendance->recibo_subtotal}\n";
                $teacher->saldo_pendiente += floor($attendance->recibo_subtotal / 100);
            }
            //echo "  saldo pendiente: $ {$teacher->saldo_pendiente}\n";
            //echo PHP_EOL;
            $egresoAcumuladoMes += $teacher->saldo_pendiente;
        }

        $cursos = \App\Models\Curso::all();

        return view('admin.dashboard', [
            'ritmo_labels' => config('wiseabc.ritmo_labels'),
            'arrCursos' => $cursos,
            'sinClase' => $sinClase,
            'finmes' => now('-0600')->endOfMonth()->isoFormat('ddd DD MMMM'),
            'cortePasado' => $cortePasado->isoFormat('DD MMMM'),
            'ingresosAcumulados' => $ingresosAcumulados,
            'egresoAcumuladoMes' => $egresoAcumuladoMes,
            'egresoEstimadoMes' => floor($egresoEstimadoMes / 100),
            'TeachersNotPaid' => $TeachersNotPaid,
        ]);
    }
}
