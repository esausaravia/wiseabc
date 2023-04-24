<?php

namespace Database\Seeders;

use App\Models\BillingPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $bplan = BillingPlan::create([
            'name'=>'',
            'tipo'=>1,
            'ritmo'=>1,
            'price'=>100
        ]);
        */
        $bplan = BillingPlan::create([
            'name'=>'Grupo Relax',
            'tipo'=>1,
            'ritmo'=>1,
            'price'=>3600
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-5KC55244YB7673813MRCYHTQ',
            'object'=>'{"id":"P-5KC55244YB7673813MRCYHTQ"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Grupo Medio',
            'tipo'=>1,
            'ritmo'=>2,
            'price'=>7200
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-5UR330633P879011DMRCWHWY',
            'object'=>'{"id":"P-5UR330633P879011DMRCWHWY"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Grupo Intenso',
            'tipo'=>1,
            'ritmo'=>3,
            'price'=>9600
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-05S84175CY603144AMRCWJPI',
            'object'=>'{"id":"P-05S84175CY603144AMRCWJPI"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Grupo Intenso+',
            'tipo'=>1,
            'ritmo'=>5,
            'price'=>14000
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-123PAYPALID456',
            'object'=>'{"id":"P-123PAYPALID456"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Relax',//1 clase por semana, clase individual (sin compañeros)
            'tipo'=>2,
            'ritmo'=>1,
            'price'=>8000
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-9AA85697EP853700NMRCWMEQ',
            'object'=>'{"id":"P-9AA85697EP853700NMRCWMEQ"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Medio',
            'tipo'=>2,
            'ritmo'=>2,
            'price'=>16000
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-24X8949020932182VMRCWNIQ',
            'object'=>'{"id":"P-24X8949020932182VMRCWNIQ"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Intenso',
            'tipo'=>2,
            'ritmo'=>3,
            'price'=>21600
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-15241040HF231240CMRCWODA',
            'object'=>'{"id":"P-15241040HF231240CMRCWODA"}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Intenso+',
            'tipo'=>2,
            'ritmo'=>5,
            'price'=>32000
        ]);
        $bplan->paypal()->create([
            'paypal_id'=>'P-123PAYPALID456',
            'object'=>'{"id":"P-123PAYPALID456"}'
        ]);
    }
}
