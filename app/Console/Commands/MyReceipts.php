<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\PaymentConcept;
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
      $hoy = now('-0600');

      $Attendances = \App\Models\Attendance::with(['user','classroom'])
			    ->whereDoesntHave('pconcepts')
          ->where('fechahora', '<', $hoy->copy()->subHour() )
          ->get();

      $base = PaymentConcept::where('concept', 'LIKE', 'base')->first();
      $puntualidad = PaymentConcept::where('concept', 'LIKE', 'Puntualidad')->first();
      $grupal = PaymentConcept::where('concept', 'LIKE', 'Grupal')->first();
      $lealtad = PaymentConcept::where('concept', 'LIKE', 'Lealtad')->first();

      foreach ($Attendances as $attendance) {
        $hasGroup = $attendance->classroom->students->count() > 1;
        $hasLoyalty = $attendance->user->created_at->diffInMonths( $hoy ) >= 3;

        $attendance->pconcepts()->attach( $base->id, ['amount' => $base->amount] );

        if ($attendance->puntual) {
          $attendance->pconcepts()->attach($puntualidad->id, ['amount' => $puntualidad->amount]);
        }
        if ($hasGroup) {
          $attendance->conceptos()->attach($grupal->id, ['amount' => $grupal->amount ]);
        }
        if ($hasLoyalty) {
          $attendance->pconcepts()->attach($lealtad->id, ['amount' => $lealtad->amount]);
        }
      }
    }
}
