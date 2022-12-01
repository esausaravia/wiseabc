<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;

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

    $curso = Curso::create([
      'edad'=>6,
      'nivel'=>1,
      'name'=>'Básico para juniors (RH1)'
    ]);

    $curso = Curso::create([
      'edad'=>6,
      'nivel'=>2,
      'name'=>'Básico para juniors (RH2)'
    ]);

    $curso = Curso::create([
      'edad'=>6,
      'nivel'=>3,
      'name'=>'Intermedio para juniors (RH3)'
    ]);

    $curso = Curso::create([
      'edad'=>6,
      'nivel'=>4,
      'name'=>'Intermedio para juniors (RH4)'
    ]);

    $curso = Curso::create([
      'edad'=>6,
      'nivel'=>5,
      'name'=>'Avanzado para juniors (RH5)'
    ]);

    $curso = Curso::create([
      'edad'=>6,
      'nivel'=>6,
      'name'=>'Avanzado para juniors (RH6)'
    ]);
  }
}
