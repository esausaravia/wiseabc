<?php

namespace Database\Seeders;

use App\Models\BillingPlan;
use App\Models\billRegion;
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
            'countries' => 'US',
        ]);
        $billRegion2 = billRegion::create([
            'name' => 'Mex',
            'countries' => 'MX',
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
            'tipo' => 1,
            'ritmo' => 1,
            'price' => 4396,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_1_1', 'price_1N9vPNJI1wpouYiddDiivGpj'),
            'api_object' => '{}',
        ]);
        $billRegion1->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Groupal Medium US',
            'tipo' => 1,
            'ritmo' => 2,
            'price' => 8792,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_1_2', 'price_1N9vPNJI1wpouYidisn4jvfx'),
            'api_object' => '{}',
        ]);
        $billRegion1->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Groupal Intense US',
            'tipo' => 1,
            'ritmo' => 3,
            'price' => 11988,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_1_3', 'price_1N9vPNJI1wpouYidtznl5ZQz'),
            'api_object' => '{}',
        ]);
        $billRegion1->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Groupal Intense+ US',
            'tipo' => 1,
            'ritmo' => 5,
            'price' => 18000,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_1_5', 'price_1N9vPNJI1wpouYidRze7n78d'),
            'api_object' => '{}',
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
            'name' => 'Individual Relax US', //1 clase por semana, clase individual (sin compañeros)
            'tipo' => 2,
            'ritmo' => 1,
            'price' => 9996,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_2_1', 'price_1NA0qvJI1wpouYidCdnuJGdn'),
            'api_object' => '{}',
        ]);
        $billRegion1->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Individual Medium US',
            'tipo' => 2,
            'ritmo' => 2,
            'price' => 19992,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_2_2', 'price_1NA0qvJI1wpouYidZ6myM6yL'),
            'api_object' => '{}',
        ]);
        $billRegion1->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Individual Intense US',
            'tipo' => 2,
            'ritmo' => 3,
            'price' => 26400,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_2_3', 'price_1NA0qvJI1wpouYidzHsjcxDJ'),
            'api_object' => '{}',
        ]);
        $billRegion1->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Individual Intense+ US',
            'tipo' => 2,
            'ritmo' => 5,
            'price' => 40000,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_US_2_5', 'price_1NA0qvJI1wpouYidGUgKWTZx'),
            'api_object' => '{}',
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
            'name' => 'Grupo Relax',
            'tipo' => 1,
            'ritmo' => 1,
            'price' => 3600,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_1_1', 'price_1N9dX5JI1wpouYidFe6cH9os'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Grupo Medio',
            'tipo' => 1,
            'ritmo' => 2,
            'price' => 7200,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_1_2', 'price_1N9dacJI1wpouYidfAH8WnVT'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Grupo Intenso',
            'tipo' => 1,
            'ritmo' => 3,
            'price' => 9600,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_1_3', 'price_1N9dbbJI1wpouYidpZsMaycb'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Grupo Intenso+',
            'tipo' => 1,
            'ritmo' => 5,
            'price' => 14000,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_1_5', 'price_1N9dfbJI1wpouYidNtIWfX51'),
            'api_object' => '{}',
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
            'name' => 'Particular Relax', //1 clase por semana, clase individual (sin compañeros)
            'tipo' => 2,
            'ritmo' => 1,
            'price' => 8000,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_2_1', 'price_1NA0llJI1wpouYid3kHm26aY'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Particular Medio',
            'tipo' => 2,
            'ritmo' => 2,
            'price' => 16000,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_2_2', 'price_1NA0llJI1wpouYidDiCg5TFS'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Particular Intenso',
            'tipo' => 2,
            'ritmo' => 3,
            'price' => 21600,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_2_3', 'price_1NA0llJI1wpouYidGguII77g'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);

        $bplan = BillingPlan::create([
            'name' => 'Particular Intenso+',
            'tipo' => 2,
            'ritmo' => 5,
            'price' => 32000,
        ]);
        $bplan->stripe()->create([
            'api' => 'stripe',
            'api_id' => env('STRIPE_BillingPlan_MX_2_5', 'price_1NA0llJI1wpouYidajSfdYXr'),
            'api_object' => '{}',
        ]);
        $billRegion2->billingPlans()->save($bplan);
    }
}
