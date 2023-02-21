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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $attendances = Attendance::whereDoesntHave('receipt')
        ->where('duracion', '>=', 1800)
        ->get();

      $base = PaymentConcept::where('concept', 'LIKE', 'base')->first();
      $asistencia = PaymentConcept::where('concept', 'LIKE', 'Asistencia')->first();
      $grupal = PaymentConcept::where('concept', 'LIKE', 'Grupal')->first();
      $lealtad = PaymentConcept::where('concept', 'LIKE', 'Lealtad')->first();

      foreach ($attendances as $attendance) {
        $hasGroup = $attendance->class->students()->count() > 1;
        $profeCreacion = User::find($attendance->class->teacher_id);

        $hasLoyalty = $profeCreacion->created_at->diffInMonths(now()) <= 3;

        $amount = $base->amount;
        $amount += $attendance->puntual ? $asistencia->amount : 0;
        $amount += $hasGroup ? $grupal->amount : 0;
        $amount += $hasLoyalty ? $lealtad->amount : 0;

        $receipt = Receipt::create([
          'attendance_id' => $attendance->id,
          'status' => 'generated',
          'amount' => $amount,
        ]);

        $receipt->conceptos()->attach($base->id, ['amount' => $base->amount]);

        if ($attendance->puntual) {
          $receipt->conceptos()->attach($asistencia->id, ['amount' => $asistencia->amount]);
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
