<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      //create seed for payments related to receipts, and users
      DB::table('payments')->insert(
        [
          'user_id'=>2,
          'amount'=>1000,
          'reference'=>'1234567890'
        ],
      );


    }
}
