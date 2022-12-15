<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentConceptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //create payment concepts 5
        $paymentConcept = PaymentConcept::create([
            'concept' => 'Puntualidad',
            'monto' => 10,

        ],[
            'concept' => 'Asistencia',
            'monto' => 10,

        ],[
            'concept' => 'Participación',
            'monto' => 10,

        ]);
    }
}
