<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $arrEdades = config('wiseabc.edad_labels');

        for( $nivel=1; $nivel<7; $nivel++ ) {

            $edad = 6;
            //foreach($arrEdades AS $edad=>$edad_label) {

                $students = \App\Models\User::factory()->count(2)->create(['user_type'=>2]);
                foreach($students AS $student) {
                    $student->saveMetas([
                      'edad'=>$edad,
                      'nivel'=>$nivel
                    ]);

                    //disponibilidad 10am
                    $student->saveHorarios([1=>[10]]);
                }

                $students = \App\Models\User::factory()->count(2)->create(['user_type'=>2]);
                foreach($students AS $student) {
                    $student->saveMetas([
                      'edad'=>$edad,
                      'nivel'=>$nivel
                    ]);

                    //disponibilidad 12 mediodia
                    $student->saveHorarios([1=>[12]]);
                }

            //}//END edades
        }//END nivel
    }
}
