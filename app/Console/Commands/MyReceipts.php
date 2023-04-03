<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\PaymentConcept;
use App\Models\Receipt;
use App\Models\User;
use Carbon\Carbon;
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
    protected $description = 'Transicion de asistencia a recibo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $hoy = now('America/Mexico_City')->locale('es');

      $Attendances = Attendance::whereDoesntHave('receipt')
        ->where('fechahora', '<', $hoy->copy()->subMinutes(45) )
        ->get();

      $base = PaymentConcept::where('concept', 'LIKE', 'base')->first();
      $pasistencia = PaymentConcept::where('concept', 'LIKE', 'Asistencia')->first();
      $grupal = PaymentConcept::where('concept', 'LIKE', 'Grupal')->first();
      $lealtad = PaymentConcept::where('concept', 'LIKE', 'Lealtad')->first();

      foreach ($Attendances as $attendance) {
        $hasGroup = $attendance->classroom->students()->count() > 1;
        $profeCreacion = $attendance->classroom->teacher;

        $hasLoyalty = $profeCreacion->created_at->diffInMonths( $hoy ) > 3;

        $amount = $base->amount;
        $amount += $attendance->puntual ? $pasistencia->amount : 0;
        $amount += $hasGroup ? ( $grupal->amount ) : 0;
        $amount += $hasLoyalty ? $lealtad->amount : 0;

        $receipt = Receipt::create([
          'attendance_id' => $attendance->id,
          'status' => 'pending',
          'amount' => $amount,
        ]);

        $receipt->conceptos()->attach($base->id, ['amount' => $base->amount]);

        if ($attendance->puntual) {
          $receipt->conceptos()->attach($pasistencia->id, ['amount' => $pasistencia->amount]);
        }

        if ($hasLoyalty) {
          $receipt->conceptos()->attach($lealtad->id, ['amount' => $lealtad->amount]);
        }

        if ($hasGroup) {
          $receipt->conceptos()->attach($grupal->id, ['amount' => $grupal->amount]);
        }
      }
    }
}
