<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$hoy = now('America/Mexico_City')->locale('es');
		$cortePasado = $hoy->copy()->subMonth()->endOfMonth();

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

        /**
		 * Recibos pagados mes pasado
		 */
        $Payments = Payment::with(['user'])->whereHas('attendances',function($query) use ($cortePasado){
            $query->where('fechahora','<', $cortePasado);
        })->get();
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $Payments->toSql()), $Payments->getBindings()); dd($sql);

        return view('admin.payments.index',[
            'cortePasado'=>$cortePasado->format('d M'),
            'TeachersNotPaid'=>$TeachersNotPaid,
            'Payments'=>$Payments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
