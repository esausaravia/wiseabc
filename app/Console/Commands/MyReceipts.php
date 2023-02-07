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
        if ($attendance->duracion >= 1800) {
          $base = PaymentConcept::where('concept', 'base')->first()->amount;
          $amount = $base;

          if ($attendance->puntual) {
            //se multiplica por 0.1 para convertir de minutos a horas
            $amount += $base * 0.1;
          }

          $receipt = Receipt::create([
            'attendance_id' => $attendance->id,
            'status' => 'generated',
            'amount' => $amount,
          ]);

          $receipt->conceptos()->attach(PaymentConcept::where('concept', 'Base')->first()->id, [
            'amount' => $base,
          ]);

          if ($attendance->puntual) {
            $receipt->conceptos()->attach(PaymentConcept::where('concept', 'Asistencia')->first()->id, [
              'amount' => $base * 0.1,
            ]);
          }
        }
      }
    }
}
