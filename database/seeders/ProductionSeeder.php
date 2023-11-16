<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * php artisan db:seed --force
         * php artisan migrate:fresh --seed --seeder=ProductionSeeder
         */

        /**
         * Admin Users
         */
        $admin = User::create(['status' => 'ACTIVE', 'user_type' => 1, 'name' => 'Esau Saravia', 'email' => 'esau@mediaurea.com', 'password' => bcrypt('dye2159')]);

        $admin = User::create(['status' => 'ACTIVE', 'user_type' => 1, 'name' => 'Arturo Dovalina', 'email' => 'adovalina@gmail.com', 'password' => bcrypt('Luc@dmin2023')]);

        /**
         * Teacher
         */
        $teacher = User::create(['user_type' => 3, 'email' => 'MarioJimenez@wiseabcenglish.com', 'name' => 'Mario Jimenez', 'password' => bcrypt('qwerasdf')]);
        $teacher->created_at = now('UTC')->subMonths(3)->subDay();
        $teacher->save();
        $teacher->saveMetas(['lname' => 'Jimenez', 'fname' => 'Mario', 'personal_email' => 'wiseabcenglish@gmail.com', 'timezone' => '-0600']);

        /**
         * Planes de subscripcion
         */
        $this->call([
            BillingPlansSeeder::class,
        ]);

        /**
         * Cursos
         */
        $this->call([
            CursoSeeder::class,
        ]);

        /**
         * Conceptos de pago
         */
        $this->call([
            PaymentConceptSeeder::class,
        ]);
    }
}
