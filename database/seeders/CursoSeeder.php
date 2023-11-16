<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Curso::create(['edad' => 6, 'nivel' => 1, 'name' => 'Básico para juniors (RH1)']);
        Curso::create(['edad' => 6, 'nivel' => 2, 'name' => 'Básico para juniors (RH2)']);
        Curso::create(['edad' => 6, 'nivel' => 3, 'name' => 'Intermedio para juniors (RH3)']);
        Curso::create(['edad' => 6, 'nivel' => 4, 'name' => 'Intermedio para juniors (RH4)']);
        Curso::create(['edad' => 6, 'nivel' => 5, 'name' => 'Avanzado para juniors (RH5)']);
        Curso::create(['edad' => 6, 'nivel' => 6, 'name' => 'Avanzado para juniors (RH6)']);
    }
}
