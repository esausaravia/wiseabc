<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Attendance;
use Carbon\Carbon;
use Faker\Core\Number;
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
		$hoy = now('-0600')->locale('es');
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
        ob_start();
        $valid = $request->validate([
            'user_id' => 'required|integer',
            'reference' => 'required',
            'amount' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required'
        ]);
        $input = $request->input();

        $amount = preg_replace('/[^0-9\.]/i','', $valid['amount']);
        $amount = (float)$amount;

        if ( !preg_match('/([0-9]{2})\:([0-9]{2})(\:[0-9]{2})?/',$valid['hora']) ) {
            $valid['hora'] = '00:00:00';
        }

        $fechahora = new Carbon($valid['fecha'].' '.$valid['hora'], $input['timezone']);

        /*
        dd([
            'Payment'=>[
                'user_id'=>$valid['user_id'],
                'reference'=>$valid['reference'],
                'amount'=>$amount
            ],
            'valid'=>$valid,
            'input'=>$input
        ]);
        */

        $Payment = Payment::create([
            'user_id'=>$valid['user_id'],
            'reference'=>$valid['reference'],
            'amount'=>$amount
        ]);
        $Payment->created_at = $fechahora;
        $Payment->save();

        foreach($input['attendance_id'] AS $attendance_id) {
            $Attendance = Attendance::find( $attendance_id );

            if (empty($Attendance) || !is_object($Attendance)) {
                continue;
            }

            $Attendance->payment_id = $Payment->id;
            $Attendance->save();

            if ( empty($input['attendance_pconcept'])
                || empty($input['attendance_pconcept'][($attendance_id)])
                || !in_array(1, $input['attendance_pconcept'][($attendance_id)]) )
            {
                $affected = DB::table('attendance_pconcept')->where('attendance_id',$attendance_id)->update(['amount'=>0]);
            }
            else
            {
                $affected = DB::table('attendance_pconcept')->where('attendance_id',$attendance_id)->whereNotIn('pconcept_id',$input['attendance_pconcept'][($attendance_id)])->update(['amount'=>0]);
            }

        }
        $debug = ob_get_clean();

        return $request->wantsJson() ? response()->json(['message'=>'Pago registrado con éxito', 'Payment'=>$Payment ])
            : redirect()->route('admin.pagos.show', $Payment->id)->with('success','Pago registrado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Payment = Payment::with(['user','attendances','attendances.pconcepts','attendances.classroom','attendances.classroom.curso'])->find($id);

        //ob_start();
        $arrPagosPorConcepto = array();
        $arrDias = array();
        foreach( $Payment->attendances AS $attendance ) {

            $_dia = $attendance->fechahora->format('Y-m-d');
            if ( !in_array($_dia, $arrDias) ) {
                array_push($arrDias, $_dia);
            }
            $attendance->subtotal = 0;

            foreach($attendance->pconcepts AS $pconcept) {
                if ( !isset($arrPagosPorConcepto[( $pconcept->concept )]) ) {
                    $arrPagosPorConcepto[( $pconcept->concept )] = $pconcept->recibo->amount;
                }
                else {
                    $arrPagosPorConcepto[( $pconcept->concept )] += $pconcept->recibo->amount;
                }
                $attendance->subtotal += $pconcept->recibo->amount;
            }
        }
        $Payment->attendances = $Payment->attendances->sortBy('fechahora');
        //dd( ob_get_clean() );

        return view('admin.payments.show',[
            'Payment'=>$Payment,
            'num_clases'=>$Payment->attendances->count(),
            'arrDias'=>$arrDias,
            'arrPagosPorConcepto'=>$arrPagosPorConcepto,
        ]);
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

    public function paraprofe(Request $request, $id) {
        ob_start();

        $Teacher = \App\Models\User::find($id);

		$hoy = now('-0600')->locale('es');
		$cortePasado = $hoy->copy()->subMonth()->endOfMonth();

        $PaymentConcepts = \App\Models\PaymentConcept::all();

        $Teacher->antiguedad = $Teacher->created_at->diffInMonths( $hoy );

        $Attendances = \App\Models\Attendance::with(['pconcepts','classroom','classroom.curso'])
            ->withSum('pconcepts as recibo_subtotal','attendance_pconcept.amount')
            ->where('user_id','=',$id)
            ->where('fechahora','<',$cortePasado)
            ->whereNull('payment_id')
            ->orderBy('fechahora')->orderBy('id');
        $sql = vsprintf(str_replace(array('?'), array('\'%s\''), $Attendances->toSql()), $Attendances->getBindings());
        echo $sql.PHP_EOL;

        $Attendances = $Attendances->get();

        $payment_amount = 0;
        $arrPagosPorConcepto = array();
        $arrDias = array();
        foreach( $Attendances AS $attendance ) {

            $_dia = $attendance->fechahora->format('Y-m-d') ;
            if ( !in_array($_dia, $arrDias) ) {
                array_push($arrDias, $_dia);
            }

            foreach($attendance->pconcepts AS $pconcept) {
                if ( !isset($arrPagosPorConcepto[( $pconcept->id )]) ) {
                    $arrPagosPorConcepto[( $pconcept->id )] = $pconcept->recibo->amount;
                }
                else {
                    $arrPagosPorConcepto[( $pconcept->id )] += $pconcept->recibo->amount;
                }
                $attendance->subtotal += $pconcept->recibo->amount;
            }
            $payment_amount += $attendance->subtotal;
        }
        echo "payment_amount: {$payment_amount}".PHP_EOL;
        echo "arrDias: ".print_r($arrDias,true).PHP_EOL;
        echo "arrPagosPorConcepto: ".print_r($arrPagosPorConcepto,true).PHP_EOL;

        $arrDias2 = array();
        $AttendanceCollectionsByDay = collect();
        foreach($arrDias AS $_dia) {
            echo 'dia: '.$_dia.PHP_EOL;
            $check1 = new Carbon($_dia.' 00:00:00', '-0600');
            $check2 = new Carbon($_dia.' 23:59:59', '-0600');
            echo "  check1: ".$check1->format('Y-m-d H:i:s').PHP_EOL;
            echo "  check2: ".$check2->format('Y-m-d H:i:s').PHP_EOL;

            $subcollection = $Attendances->filter(function($item,$key) use ($check1,$check2){
                echo "    #{$key} : ".$item->fechahora->format('Y-m-d H:i:s').PHP_EOL;
                return $check1->lessThan($item->fechahora) && $check2->greaterThan($item->fechahora);
            });

            $AttendanceCollectionsByDay->put( $_dia, $subcollection );
            $arrDias2[($_dia)] = $check1;
        }

        ob_get_clean();
        //dd( ob_get_clean() );
        return view('admin.payments.paraprofe', [
            'Teacher'=>$Teacher,
            'cortePasado'=>$cortePasado->isoFormat('DD MMM'),
            'num_clases'=>$Attendances->count(),
            'PaymentConcepts'=>$PaymentConcepts,
            'arrPagosPorConcepto'=>$arrPagosPorConcepto,
            'payment_amount'=>$payment_amount,
            'Attendances'=>$Attendances,
            'arrDias'=>$arrDias2,
            'AttendanceCollectionsByDay'=>$AttendanceCollectionsByDay,

        ]);
    }
}
