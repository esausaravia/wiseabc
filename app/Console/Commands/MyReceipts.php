<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\PaymentConcept;
use App\Models\Receipt;
use Illuminate\Console\Command;

class MyReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:receipts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $attendances = Attendance::whereDoesntHave('receipt')->get();

      foreach ($attendances as $attendance) {
        if ($attendance->duracion < 1800) {
          continue;
        }
        $base = PaymentConcept::where('concept', 'LIKE', 'base')->first();
        $asistencia = PaymentConcept::where('concept', 'LIKE', 'Asistencia')->first();
        //FALTA Lealtad
        //FALTA Grupal

        $amount = $base->amount;

        if ($attendance->puntual) {
          //se multiplica por 0.1 para convertir de minutos a horas
          $amount += $asistencia->amount;//?????????
        }
        /*
        Falta obtener fecha de registro de profe y comparar contra fecha actual, si es mayor a 3 meses, se da el bono de lealtad
        if ($profe->created_at < $hoy->menos('3 meses') ) {
          $tiene_lealtad = true;
        }
        */

        /* FALTA sumar cantidad grupal
        if ( $clase->students->count() > 1 ) {
          $tiene_grupal = true;
        }
        */

        $receipt = Receipt::create([
          'attendance_id' => $attendance->id,
          'status' => 'generated',
          'amount' => $amount,
        ]);

        $receipt->conceptos()->attach($base->id, ['amount' => $base->amount]);

        //Asistencia = Puntualidad
        if ($attendance->puntual) {
          $receipt->conceptos()->attach($asistencia->id, ['amount' => $asistencia->amount]);
        }
        if ( $tiene_lealtad) {
          //$receipt->conceptos()->attach($asistencia->id, ['amount' => $asistencia->amount]);
        }
        if ( $tiene_grupal ) {
          //$receipt->conceptos()->attach($asistencia->id, ['amount' => $asistencia->amount]);
        }

      }//END foreach
    }
}
