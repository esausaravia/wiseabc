<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Edades = collect(config('wiseabc.edad_labels'));
        $Edades = $Edades->slice(0, 2);

        $arrBillingPlans = [
            2 => ['tipo' => 1, 'ritmo' => 2],
            5 => ['tipo' => 2, 'ritmo' => 1],
        ];

        $arrHorarios = [12, 17];

        $students_count = 0;

        for ($nivel = 1; $nivel < 7; $nivel++) {
            $Edades = [6 => '6 años'];
            foreach ($Edades as $edad => $edad_label) {

                foreach ($arrBillingPlans as $sid => $billPlan) {

                    foreach ($arrHorarios as $___hr) {

                        $students = \App\Models\User::factory()->count(2)->create([
                            'user_type' => 2,
                            'password' => bcrypt('qwerasdf'),
                        ]);
                        foreach ($students as $student) {

                            $arrName = explode(' ', $student->name);

                            $student->saveMetas([
                                'lname' => array_pop($arrName),
                                'fname' => implode(' ', $arrName),
                                'nivel' => $nivel,
                                'edad' => $edad,
                                'clase_tipo' => $billPlan['tipo'],
                                'ritmo' => $billPlan['ritmo'],
                                'timezone' => '-0600',
                            ]);
                            $student->saveHorarios([1 => [$___hr]]);
                            $students_count++;

                        }//END Students
                    }//END arrHorarios
                }//END arrBillingPlans
            }//END Edades
        }//END nivel

        echo "  Estudiantes creados: {$students_count}".PHP_EOL;
    }
}
