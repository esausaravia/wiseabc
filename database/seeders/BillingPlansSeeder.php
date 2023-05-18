<?php

namespace Database\Seeders;

use App\Models\BillingPlan;
use App\Models\billRegion;
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
        $billRegion1 = billRegion::create([
            'name' => 'General',
            'countries' => 'US'
        ]);
        $billRegion2 = billRegion::create([
            'name' => 'Mex',
            'countries' => 'MX'
        ]);
        /*
        $bplan = BillingPlan::create([
            'name'=>'',
            'tipo'=>1,
            'ritmo'=>1,
            'price'=>100
        ]);
        */
        $bplan = BillingPlan::create([
            'name' => 'Groupal Relax US',
            'tipo'=>1,
            'ritmo'=>1,
            'price'=>4396
        ]);
        $billRegion1->billingPlans()->save($bplan);


        $bplan = BillingPlan::create([
            'name' => 'Groupal Medium US',
            'tipo'=>1,
            'ritmo'=>2,
            'price'=>8792
        ]);
        $billRegion1->billingPlans()->save($bplan);


        $bplan = BillingPlan::create([
            'name' => 'Groupal Intense US',
            'tipo'=>1,
            'ritmo'=>3,
            'price'=>11988
        ]);
        $billRegion1->billingPlans()->save($bplan);


        $bplan = BillingPlan::create([
            'name' => 'Groupal Intense+ US',
            'tipo'=>1,
            'ritmo'=>5,
            'price'=>18000
        ]);
        $billRegion1->billingPlans()->save($bplan);

        /*
        $bplan = BillingPlan::create([
            'name'=>'',
            'tipo'=>1,
            'ritmo'=>1,
            'price'=>100
        ]);
        */
        $bplan = BillingPlan::create([
            'name' => 'Individual Relax US',//1 clase por semana, clase individual (sin compañeros)
            'tipo'=>2,
            'ritmo'=>1,
            'price'=>9996
        ]);
        $billRegion1->billingPlans()->save($bplan);


        $bplan = BillingPlan::create([
            'name' => 'Individual Medium US',
            'tipo'=>2,
            'ritmo'=>2,
            'price'=>19992
        ]);
        $billRegion1->billingPlans()->save($bplan);


        $bplan = BillingPlan::create([
            'name' => 'Individual Intense US',
            'tipo'=>2,
            'ritmo'=>3,
            'price'=>26400
        ]);
        $billRegion1->billingPlans()->save($bplan);


        $bplan = BillingPlan::create([
            'name' => 'Individual Intense+ US',
            'tipo'=>2,
            'ritmo'=>5,
            'price'=>40000
        ]);
        $billRegion1->billingPlans()->save($bplan);

        /**
         * Mexico
         */
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
            'api_id'=>'P-9GW89184CA640501YMRGHN5Y',
            'api_object' => '{}'
        ]);
        $billRegion2->billingPlans()->save($bplan);


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
        $billRegion2->billingPlans()->save($bplan);


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
        $billRegion2->billingPlans()->save($bplan);


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
        $billRegion2->billingPlans()->save($bplan);

        /*
        $bplan = BillingPlan::create([
            'name'=>'',
            'tipo'=>1,
            'ritmo'=>1,
            'price'=>100
        ]);
        */
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
        $billRegion2->billingPlans()->save($bplan);


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
        $billRegion2->billingPlans()->save($bplan);


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
        $billRegion2->billingPlans()->save($bplan);


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
        $billRegion2->billingPlans()->save($bplan);
    }
}
