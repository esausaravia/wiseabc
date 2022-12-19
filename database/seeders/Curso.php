<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Curso extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      //create seed for courses table
      DB::table('cursos')->insert(
        [
          'edad'=>6,
          'nivel'=>1,
          'name'=>'Básico para juniors (RH1)'
        ],
        [
          'edad'=>6,
          'nivel'=>3,
          'name'=>'Intermedio para juniors (RH3)'
        ],
        [
          'edad'=>6,
          'nivel'=>4,
          'name'=>'Intermedio para juniors (RH4)'
        ],
        [
          'edad'=>6,
          'nivel'=>5,
          'name'=>'Avanzado para juniors (RH5)'
        ],[
          'edad'=>6,
          'nivel'=>6,
          'name'=>'Avanzado para juniors (RH6)'
        ]
      );


    }
}
