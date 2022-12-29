<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Classroom;
use App\Models\Curso;
use Illuminate\Database\Seeder;
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
    //  'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);
    DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas

    DB::table('cursos')->truncate();
    DB::table('classrooms')->truncate();
    DB::table('attendances')->truncate();
    DB::table('receipts')->truncate();
    DB::table('payments')->truncate();
    DB::table('payment_concepts')->truncate();


    $cursos = [
      [ 'edad' => 6, 'nivel' => 1, 'name' => 'Básico para juniors (RH1)'],
      [ 'edad' => 6, 'nivel' => 2, 'name' => 'Básico para juniors (RH2)'],
      [ 'edad' => 6, 'nivel' => 3, 'name' => 'Intermedio para juniors (RH3)',  ],
      [ 'edad' => 6, 'nivel' => 4, 'name' => 'Intermedio para juniors (RH4)',  ],
      [ 'edad' => 6, 'nivel' => 5, 'name' => 'Avanzado para juniors (RH5)',  ],
      [ 'edad' => 6, 'nivel' => 6, 'name' => 'Avanzado para juniors (RH6)',  ],
    ];

    $attendances = [  [    'user_id' => 3,    'class_id' => 9,    'fechahora' => '2022-12-15 22:00:00',    'duracion' => 2400,    'puntual' => true,  ],
      [    'user_id' => 3,    'class_id' => 9,    'fechahora' => '2022-12-13 22:00:00',    'duracion' => 2400,    'puntual' => true,  ],
    ];

    $paymentConcepts = [  [    'concept' => 'Base',    'amount' => 5,  ],
      [    'concept' => 'Asistencia',    'amount' => 6,  ],
      [    'concept' => 'Lealtad',    'amount' => 3,  ],
    ];

    $receipts = [  [    'attendance_id' => 2,    'payment_id' => 1,    'status' => 'pagado',    'amount' => 1000,  ],
      [    'attendance_id' => 3,    'payment_id' => 2,    'status' => 'pagado',    'amount' => 1000,  ], [    'attendance_id' => 3,    'payment_id' => 3,    'status' => 'pagado',    'amount' => 1000,  ],
      [    'attendance_id' => 3,    'payment_id' => 4,    'status' => 'pagado',    'amount' => 1000,  ], [    'attendance_id' => 5,    'payment_id' => 2,    'status' => 'pagado',    'amount' => 1000,  ]
    ];

    $payments = [  [    'user_id' => 3,    'reference' => '12312',    'amount' => 1000,  ],[    'user_id' => 3,    'reference' => '123121',    'amount' => 1000,  ]

    ];

    for ($i = 0; $i < 3; $i++) {
      $profe = \App\Models\User::create([
        'status' => 'active',
        'user_type' => 3,
        'name' => "Profe{$i}",
        'email' => "profe{$i}@wiseabc.net",
        'password' => bcrypt('password')
      ]);
      /**
       * FALTA ASIGNAR HORARIO PARA PROFESOR
       * $profe->saveHorarios([1=>[13,14]])
       */
    }
    $profesores = \App\Models\User::where('user_type',3)->get();

    foreach ($paymentConcepts as $paymentConcept) {
      DB::table('payment_concepts')->insert([
        'concept' => $paymentConcept['concept'],
        'amount' => $paymentConcept['amount'],
      ]);
    }


    foreach ($cursos as $curso) {
      // Inserta el curso y obtiene el ID
      $cursoId = DB::table('cursos')->insertGetId($curso);

      // Inserta el classroom utilizando el ID obtenido
      $classroom = Classroom::create([
        'teacher_id' => 2,
        'curso_id' => $cursoId,
        'status' => 'activo',
        'tipo' => 1,
        'ritmo' => 2,
        'start' => '2022-12-15 22:00:00',
        'ends_at' => '2022-12-15 22:00:00'
      ]);
      $classroomId = $classroom->id;
      /**
       * FALTA ASIGNAR HORAIRO A CLASSROOM
       * $classroom->saveHorarios([1=>[13,14]])
       */

      /**
       * CREAR AL MENOS 1 ROW DE TEAMSINFO
       */

      /**
       * CREAR SCHEDULE CON HORARIO DE CLASE
       * $fechahora = $classroom->nextSchedule()
       *
       */

      // Inserta las asistencias utilizando el ID del classroom
      $attendanceIds = [];
      foreach ($attendances as $attendance) {
        $attendanceIds[] = DB::table('attendances')->insertGetId([
          'user_id' => $attendance['user_id'],
          'class_id' => $classroom->id,
          'fechahora' => $attendance['fechahora'],
          'duracion' => $attendance['duracion'],
          'puntual' => $attendance['puntual'],
        ]);
      }

      // Inserta las facturas utilizando los IDs de las asistencias
      $receiptIds = [];
      foreach ($receipts as $receipt) {
        if ($receipt['attendance_id'] > count($attendanceIds)) {
          continue;
        }
        $receiptIds[] = DB::table('receipts')->insertGetId([
          'attendance_id' => $attendanceIds[$receipt['attendance_id'] - 1],
          'payment_id' => $receipt['payment_id'],
          'status' => $receipt['status'],
          'amount' => $receipt['amount'],
        ]);
      }
      foreach ($payments as $payment) {
        $reference = uniqid();
        DB::table('payments')->insert([
          'user_id' => $payment['user_id'],
          'reference' => $reference,
          'amount' => $payment['amount'],
        ]);
      }
      // Inserta los pagos utilizando los IDs de las facturas
    }
  }
}
