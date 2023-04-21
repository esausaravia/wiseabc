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
        $Edades = collect( config('wiseabc.edad_labels') );
        $Edades = $Edades->slice(0,2);

        $Suscripciones = collect( config('wiseabc.suscripciones') );
        $arrSuscripciones = [2,5];

        $arrHorarios = [10,12];

        $students_count = 0;

        for( $nivel=1; $nivel<7; $nivel++ ) {
            $Edades = array(6=>'6 años');
            foreach($Edades AS $edad=>$edad_label) {

                foreach($arrSuscripciones AS $sid) {
                    $Suscripcion = (object)$Suscripciones->first(function($item, $key) use ($sid){
                        return $item['id']===$sid;
                    });

                    foreach( $arrHorarios AS $___hr ) {

                        $students = \App\Models\User::factory()->count(2)->create([
                            'user_type'=>2,
                            'password' => bcrypt('qwerasdf')
                        ]);
                        foreach($students AS $student) {

                            $arrName = explode(' ', $student->name);

                            $student->saveMetas([
                                'lname' => array_pop($arrName),
                                'fname' => implode(' ', $arrName),
                                'nivel'=>$nivel,
                                'edad'=>$edad,
                                'suscripcion'=>$sid,
                                'clase_tipo' => $Suscripcion->tipo,
                                'ritmo' => $Suscripcion->ritmo,
                                'timezone' => '-0600'
                            ]);
                            $student->saveHorarios([1=>[$___hr]]);
                            $students_count++;

                        }//END Students
                    }//END arrHorarios
                }//END arrSuscripciones
            }//END Edades
        }//END nivel

        echo "  Estudiantes creados: {$students_count}".PHP_EOL;
    }
}
