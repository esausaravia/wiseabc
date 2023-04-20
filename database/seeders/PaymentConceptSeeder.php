<?php

namespace Database\Seeders;

use App\Models\PaymentConcept;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PaymentConcept::create(['concept' => 'Base', 'amount' => 500]);
        PaymentConcept::create(['concept' => 'Puntualidad', 'amount' => 600]);
        PaymentConcept::create(['concept' => 'Lealtad', 'amount' => 300]);
        PaymentConcept::create(['concept' => 'Grupal', 'amount' => 100]);
    }
}
