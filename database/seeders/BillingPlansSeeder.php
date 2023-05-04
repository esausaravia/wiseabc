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
            'price'=>3600,
            'status'=>'PAUSED'
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-9GW89184CA640501YMRGHN5Y',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Grupo Medio',
            'tipo'=>1,
            'ritmo'=>2,
            'price'=>7200
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-5UR330633P879011DMRCWHWY',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Grupo Intenso',
            'tipo'=>1,
            'ritmo'=>3,
            'price'=>9600
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-05S84175CY603144AMRCWJPI',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Grupo Intenso+',
            'tipo'=>1,
            'ritmo'=>5,
            'price'=>14000
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-123PAYPALID456',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Relax',//1 clase por semana, clase individual (sin compañeros)
            'tipo'=>2,
            'ritmo'=>1,
            'price'=>8000
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-9AA85697EP853700NMRCWMEQ',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Medio',
            'tipo'=>2,
            'ritmo'=>2,
            'price'=>16000
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-24X8949020932182VMRCWNIQ',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Intenso',
            'tipo'=>2,
            'ritmo'=>3,
            'price'=>21600
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-15241040HF231240CMRCWODA',
            'api_object' => '{}'
        ]);


        $bplan = BillingPlan::create([
            'name'=>'Particular Intenso+',
            'tipo'=>2,
            'ritmo'=>5,
            'price'=>32000
        ]);
        $bplan->paypal()->create([
            'api_id'=>'P-123PAYPALID456',
            'api_object' => '{}'
        ]);


        $bplan = \App\Models\BillingPlan::create([
            'tipo'=>1,
            'ritmo'=>1,
            'price' => 300,
            'status'=>'ACTIVE',
            'name' => 'GrupoRelax 3 días'
        ]);
        $bplan->paypal()->create([
            'api_id' => 'P-48D69303M08841545MRGYN4I',
            'api_object' => '{}'
        ]);
        $bplan->stripe()->create([
            'api'=>'stripe',
            'api_id' => 'price_1N2T7fKYG3qD2MysWHM4Gig9',
            'api_object' => '{}'
        ]);
    }
}
