<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class Classrooms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //generate seeder relate table cursos and users
        DB::table('classrooms')->insert(
            [
                'teacher_id'=>2,
                'curso_id'=>1,
                'status'=>'activo',
                'tipo' => 1,
                'ritmo' => 2,
                'start' => '2022-12-15 22:00:00',
                'ends_at' => '2022-12-15 22:00:00'
            ],
            [
                'teacher_id'=>2,
                'curso_id'=>1,
                'status'=>'activo',
                'tipo' => 2,
                'ritmo' => 1,
                'start' => '2022-12-15 22:00:00',
                'ends_at' => '2022-12-15 22:00:00',

            ],
        );
    }
}
