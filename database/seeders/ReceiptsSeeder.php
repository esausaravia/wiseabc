<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class ReceiptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //create seed for receipts related to attendances
        DB::table('receipts')->insert(
            [
                'attendance_id'=>2,
                'payment_id'=>2,
                'status'=>'pagado',
                'amount'=>1000
            ],
            [
                'attendance_id'=>3,
                'payment_id'=>3,
                'status'=>'pagado',
                'amount'=>1000
            ],
        );
    }
}
