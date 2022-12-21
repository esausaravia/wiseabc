<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Classroom;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // \App\Models\User::factory(10)->create();

    // \App\Models\User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);
    DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas
    DB::table('cursos')->truncate();
    $this->call(CursoSeeder::class);
    DB::table('classrooms')->truncate();
    $this->call(ClassroomsSeeder::class);
    DB::table('attendances')->truncate();
    $this->call(AttendancesSeeder::class);
    DB::table('receipts')->truncate();
    $this->call(ReceiptsSeeder::class);
    DB::table('payments')->truncate();
    $this->call(PaymentsSeeder::class);
    DB::table('payment_concepts')->truncate();
    $this->call(PaymentConceptsSeeder::class);



  }
}
