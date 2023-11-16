<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $hoy = now('-0600');
        $cortePasado = now()->subMonth()->endOfMonth();

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
                $teacher->saldo_pendiente += $attendance->recibo_subtotal;
            }
            //echo "  saldo pendiente: $ {$teacher->saldo_pendiente}\n";
            //echo PHP_EOL;
            $egresoAcumuladoMes += $teacher->saldo_pendiente;
        }

        /**
         * Recibos pagados mes pasado
         */
        $Payouts = Payout::with(['user'])->whereHas('attendances', function ($query) use ($cortePasado) {
            $query->where('fechahora', '<', $cortePasado);
        })->get();
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $Payouts->toSql()), $Payouts->getBindings()); dd($sql);

        return view('admin.payouts.index', [
            'hoy' => $hoy->setTimezone('-0600')->locale('es'),
            'cortePasado' => $cortePasado->setTimezone('-0600')->format('d M'),
            'TeachersNotPaid' => $TeachersNotPaid,
            'Payouts' => $Payouts,
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
            'hora' => 'required',
        ]);
        $input = $request->input();

        $amount = preg_replace('/[^0-9\.]/i', '', $valid['amount']);
        $amount = (float) $amount;

        if (! preg_match('/([0-9]{2})\:([0-9]{2})(\:[0-9]{2})?/', $valid['hora'])) {
            $valid['hora'] = '00:00:00';
        }

        $fechahora = new Carbon($valid['fecha'].' '.$valid['hora'], $input['timezone']);

        /*
        dd([
            'Payout'=>[
                'user_id'=>$valid['user_id'],
                'reference'=>$valid['reference'],
                'amount'=>$amount
            ],
            'valid'=>$valid,
            'input'=>$input
        ]);
        */

        $Payout = Payout::create([
            'user_id' => $valid['user_id'],
            'reference' => $valid['reference'],
            'amount' => $amount,
        ]);
        $Payout->created_at = $fechahora;
        $Payout->status = 'paid';
        $Payout->save();

        foreach ($input['attendance_id'] as $attendance_id) {
            $Attendance = Attendance::find($attendance_id);

            if (empty($Attendance) || ! is_object($Attendance)) {
                continue;
            }

            $Attendance->payout_id = $Payout->id;
            $Attendance->save();

            if (empty($input['attendance_pconcept'])
                || empty($input['attendance_pconcept'][($attendance_id)])
                || ! in_array(1, $input['attendance_pconcept'][($attendance_id)])) {
                $affected = DB::table('attendance_pconcept')->where('attendance_id', $attendance_id)->update(['amount' => 0]);
            } else {
                $affected = DB::table('attendance_pconcept')->where('attendance_id', $attendance_id)->whereNotIn('pconcept_id', $input['attendance_pconcept'][($attendance_id)])->update(['amount' => 0]);
            }

        }
        $debug = ob_get_clean();

        return $request->wantsJson() ? response()->json(['message' => 'Pago registrado con éxito', 'Payout' => $Payout])
            : redirect()->route('admin.pagos.show', $Payout->id)->with('success', 'Pago registrado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $Payout = Payout::with(['user', 'attendances', 'attendances.pconcepts', 'attendances.classroom', 'attendances.classroom.curso'])->find($id);

        //ob_start();
        $arrPagosPorConcepto = [];
        $arrDias = [];
        foreach ($Payout->attendances as $attendance) {

            $_dia = $attendance->fechahora->format('Y-m-d'); //copy()->setTimezone('-0600')->
            if (! in_array($_dia, $arrDias)) {
                array_push($arrDias, $_dia);
            }
            $attendance->subtotal = 0;

            foreach ($attendance->pconcepts as $pconcept) {
                if (! isset($arrPagosPorConcepto[($pconcept->concept)])) {
                    $arrPagosPorConcepto[($pconcept->concept)] = $pconcept->recibo->amount;
                } else {
                    $arrPagosPorConcepto[($pconcept->concept)] += $pconcept->recibo->amount;
                }
                $attendance->subtotal += $pconcept->recibo->amount;
            }
        }
        $Payout->attendances = $Payout->attendances->sortBy('fechahora');
        //dd( ob_get_clean() );

        return view('admin.payouts.show', [
            'Payout' => $Payout,
            'num_clases' => $Payout->attendances->count(),
            'arrDias' => $arrDias,
            'arrPagosPorConcepto' => $arrPagosPorConcepto,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }

    public function paraprofe(Request $request, $id)
    {
        ob_start();

        $Teacher = \App\Models\User::find($id);

        if (empty($Teacher)) {
            return back()->with('error', 'No se encontró al profesor');
        }

        $hoy = now('-0600');
        $cortePasado = now('-0600')->subMonth()->endOfMonth();

        $PaymentConcepts = \App\Models\PaymentConcept::all();

        $Teacher->antiguedad = $Teacher->created_at->diffInMonths($hoy);

        $Attendances = \App\Models\Attendance::with(['pconcepts', 'classroom', 'classroom.curso'])
            ->withSum('pconcepts as recibo_subtotal', 'attendance_pconcept.amount')
            ->where('user_id', '=', $id)
            ->where('fechahora', '<', $cortePasado->setTimezone('UTC'))
            ->whereNull('payout_id')
            ->orderBy('fechahora')->orderBy('id');
        $sql = vsprintf(str_replace(['?'], ['\'%s\''], $Attendances->toSql()), $Attendances->getBindings());
        echo $sql.PHP_EOL;

        $Attendances = $Attendances->get();

        $payout_amount = 0;
        $arrPagosPorConcepto = [];
        $arrDias = [];
        foreach ($Attendances as $attendance) {

            $_dia = $attendance->fechahora->setTimezone('-0600')->format('Y-m-d');
            if (! in_array($_dia, $arrDias)) {
                array_push($arrDias, $_dia);
            }

            foreach ($attendance->pconcepts as $pconcept) {
                $pconcept->recibo->amount = $pconcept->recibo->amount / 100;
                if (! isset($arrPagosPorConcepto[($pconcept->id)])) {
                    $arrPagosPorConcepto[($pconcept->id)] = $pconcept->recibo->amount;
                } else {
                    $arrPagosPorConcepto[($pconcept->id)] += $pconcept->recibo->amount;
                }
                $attendance->subtotal += $pconcept->recibo->amount;
            }
            $payout_amount += $attendance->subtotal;
        }
        echo "payout_amount: {$payout_amount}".PHP_EOL;
        echo 'arrDias: '.print_r($arrDias, true).PHP_EOL;
        echo 'arrPagosPorConcepto: '.print_r($arrPagosPorConcepto, true).PHP_EOL;

        $arrDias2 = [];
        $AttendanceCollectionsByDay = collect();
        foreach ($arrDias as $_dia) {
            echo 'dia: '.$_dia.PHP_EOL;
            $check1 = \Carbon\Carbon::parse($_dia.' 00:00:00', '-0600');
            $check2 = \Carbon\Carbon::parse($_dia.' 23:59:59', '-0600');
            echo '  check1: '.$check1->format('Y-m-d H:i:s O').PHP_EOL;
            echo '  check2: '.$check2->format('Y-m-d H:i:s O').PHP_EOL;

            $subcollection = $Attendances->filter(function ($item, $key) use ($check1, $check2) {
                echo "    #{$key} : ".$item->fechahora->format('Y-m-d H:i:s').PHP_EOL;

                return $check1->lessThan($item->fechahora) && $check2->greaterThan($item->fechahora);
            });

            $AttendanceCollectionsByDay->put($_dia, $subcollection);
            $arrDias2[($_dia)] = $check1;
        }
        echo 'arrDias2: '.print_r($arrDias2, true).PHP_EOL;

        $debug = ob_get_clean();
        //dd( $debug );
        return view('admin.payouts.paraprofe', [
            'hoy' => $hoy->setTimezone('-0600')->locale('es'),
            'cortePasado' => $cortePasado->setTimezone('-0600')->locale('es')->isoFormat('DD MMM'),
            'Teacher' => $Teacher,
            'num_clases' => $Attendances->count(),
            'PaymentConcepts' => $PaymentConcepts,
            'arrPagosPorConcepto' => $arrPagosPorConcepto,
            'payout_amount' => $payout_amount,
            'Attendances' => $Attendances,
            'arrDias' => $arrDias2,
            'AttendanceCollectionsByDay' => $AttendanceCollectionsByDay,

        ]);
    }
}
