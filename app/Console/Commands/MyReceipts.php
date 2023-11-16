<?php

namespace App\Console\Commands;

use App\Models\PaymentConcept;
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
     */
    public function handle(): int
    {
        $hoy = now();

        $Attendances = \App\Models\Attendance::with(['user', 'classroom'])
            ->whereDoesntHave('pconcepts')
            ->get();

        $base = PaymentConcept::where('concept', 'LIKE', 'Base')->first();
        $puntualidad = PaymentConcept::where('concept', 'LIKE', 'Puntualidad')->first();
        $grupal = PaymentConcept::where('concept', 'LIKE', 'Grupal')->first();
        $lealtad = PaymentConcept::where('concept', 'LIKE', 'Lealtad')->first();

        foreach ($Attendances as $attendance) {
            $attendance->classroom->loadCount('students');

            $hasLoyalty = $attendance->user->created_at->diffInMonths($hoy) >= 3;

            $attendance->pconcepts()->attach($base->id, ['amount' => $base->amount]);

            if ($attendance->puntual) {
                $attendance->pconcepts()->attach($puntualidad->id, ['amount' => $puntualidad->amount]);
            }
            if ($attendance->classroom->students_count > 1) {
                $attendance->conceptos()->attach($grupal->id, ['amount' => $grupal->amount]);
            }
            if ($hasLoyalty) {
                $attendance->pconcepts()->attach($lealtad->id, ['amount' => $lealtad->amount]);
            }
        }
    }
}
