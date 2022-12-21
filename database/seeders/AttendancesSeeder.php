<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      DB::table('attendances')->insert(array(
        [
          'user_id'=>3,
          'class_id'=>9,
          'fechahora'=>'2022-12-15 22:00:00',
          'duracion'=>2400,
          'puntual'=>true
        ],
        [
          'user_id'=>3,
          'class_id'=>9,
          'fechahora'=>'2022-12-13 22:00:00',
          'duracion'=>2400,
          'puntual'=>true
        ],
      ));
    }
}
