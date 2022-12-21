<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

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
        DB::table('payment_concepts')->insert(array([
            'concept' => 'Base',
            'amount' => 5,

        ],[
            'concept' => 'Asistencia',
            'amount' => 6,

        ],[
            'concept' => 'Lealtad',
            'amount' => 3,

        ]));

    }
}
