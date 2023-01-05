<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Classroom;
use App\Models\Curso;
use App\Models\Schedule;
use App\Models\TeamsInfo;
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

    /**
     * Truncar tablas de info demo.
     * NUNCA truncar users
     */
    DB::table('cursos')->truncate();
    DB::table('classrooms')->truncate();
    DB::table('class_schedules')->truncate();
    DB::table('teams_infos')->truncate();
    DB::table('attendances')->truncate();
    DB::table('receipts')->truncate();
    DB::table('payments')->truncate();
    DB::table('payment_concepts')->truncate();
    DB::table('users')->truncate();
    DB::table('user_horarios')->truncate();
    DB::table('usermetas')->truncate();

    $cursos = [
      ['edad' => 6, 'nivel' => 1, 'name' => 'Básico para juniors (RH1)'],
      ['edad' => 6, 'nivel' => 2, 'name' => 'Básico para juniors (RH2)'],
      ['edad' => 6, 'nivel' => 3, 'name' => 'Intermedio para juniors (RH3)',],
      ['edad' => 6, 'nivel' => 4, 'name' => 'Intermedio para juniors (RH4)',],
      ['edad' => 6, 'nivel' => 5, 'name' => 'Avanzado para juniors (RH5)',],
      ['edad' => 6, 'nivel' => 6, 'name' => 'Avanzado para juniors (RH6)',],
    ];

    $attendances = [['user_id' => 3, 'class_id' => 9, 'fechahora' => '2022-12-15 22:00:00', 'duracion' => 2400, 'puntual' => true,],
      ['user_id' => 3, 'class_id' => 9, 'fechahora' => '2022-12-13 22:00:00', 'duracion' => 2400, 'puntual' => true,],
    ];

    $paymentConcepts = [['concept' => 'Base', 'amount' => 5,],
      ['concept' => 'Asistencia', 'amount' => 6,],
      ['concept' => 'Lealtad', 'amount' => 3,],
    ];

    $receipts = [['attendance_id' => 2, 'payment_id' => 1, 'status' => 'pagado', 'amount' => 1000,],
      ['attendance_id' => 3, 'payment_id' => 2, 'status' => 'pagado', 'amount' => 1000,], ['attendance_id' => 3, 'payment_id' => 3, 'status' => 'pagado', 'amount' => 1000,],
      ['attendance_id' => 3, 'payment_id' => 4, 'status' => 'pagado', 'amount' => 1000,], ['attendance_id' => 5, 'payment_id' => 2, 'status' => 'pagado', 'amount' => 1000,]
    ];

    $payments = [['user_id' => 3, 'reference' => '12312', 'amount' => 1000,], ['user_id' => 3, 'reference' => '123121', 'amount' => 1000,]];

    $horarios = [
      [1 => [13, 14], 3 => [13, 14], 5 => [13, 14]],
      [1 => [15, 16], 3 => [15, 16], 5 => [15, 16]],
      [1 => [17, 18], 3 => [17, 18], 5 => [17, 18]],
      [1 => [19, 20], 3 => [19, 20], 5 => [19, 20]],
      [1 => [21, 22], 3 => [21, 22], 5 => [21, 22]],
      [1 => [23, 24], 3 => [23, 24], 5 => [23, 24]]
    ];

    /**
     * CREAR AL MENOS 1 ROW DE TEAMSINFO
     */
    $teamsInfo = TeamsInfo::create([
      'msid' => 'MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZiMi04ZdFpHRTNaR1F6WGhyZWFkLnYy',
      'link' => 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_M2IzYzczNTItYmY3OC00MDlmLWJjMzUtYmFiMjNlOTY4MGEz%40thread.skype/0?context=%7b%22Tid%22%3a%2272f988bf-86f1-41af-91ab-2d7cd011db47%22%2c%22Oid%22%3a%22550fae72-d251-43ec-868c-373732c2704f%22%7d',
      'info' => '{"@odata.type":"#microsoft.graph.onlineMeeting","@odata.context":"https://graph.microsoft.com/v1.0/#users(\'f4053f86-17cc-42e7-85f4-f0389ac980d6\')/onlineMeetings/","audioConferencing":{"tollNumber":"+12525634478","tollFreeNumber":"+18666390588","ConferenceId":"2425999","dialinUrl":"https://dialin.teams.microsoft.com/22f12fa0-499f-435b-bc69-b8de580ba330?id=2425999"},"chatInfo":{"threadId":"19:meeting_M2IzYzczNTItYmY3OC00MDlmLWJjMzUtYmFiMjNlOTY4MGEz@thread.skype","messageId":"0","replyChainMessageId":"0"},"creationDateTime":"2019-07-11T02:17:17.6491364Z","startDateTime":"2019-07-11T02:17:17.6491364Z","endDateTime":"2019-07-11T02:47:17.651138Z","id":"MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZiMi04ZdFpHRTNaR1F6WGhyZWFkLnYy","joinWebUrl":"https://teams.microsoft.com/l/meetup-join/19%3ameeting_M2IzYzczNTItYmY3OC00MDlmLWJjMzUtYmFiMjNlOTY4MGEz%40thread.skype/0?context=%7b%22Tid%22%3a%2272f988bf-86f1-41af-91ab-2d7cd011db47%22%2c%22Oid%22%3a%22550fae72-d251-43ec-868c-373732c2704f%22%7d","participants":{"organizer":{"identity":{"user":{"id":"550fae72-d251-43ec-868c-373732c2704f","displayName":"Heidi Steen"}},"upn":"upn-value"}},"subject":"User Token Meeting"}',
      'report' => '{"@odata.context":"https://graph.microsoft.com/v1.0/#users(\'16664f75-11dc-4870-bec6-38c1aaa81431\')/onlineMeetings(\'MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZ\')/attendanceReports(\'c9b6db1c-d5eb-427d-a5c0-20088d9b22d7\')","id":"c9b6db1c-d5eb-427d-a5c0-20088d9b22d7","totalParticipantCount":1,"meetingStartDateTime":"2021-10-05T04:38:23.945Z","meetingEndDateTime":"2021-10-05T04:43:49.77Z","attendanceRecords":[{"emailAddress":"frederick.cormier@contoso.com","totalAttendanceInSeconds":1152,"role":"Presenter","identity":{"id":"dc17674c-81d9-4adb-bfb2-8f6a442e4623","displayName":"Frederick Cormier","tenantId":null},"attendanceIntervals":[{"joinDateTime":"2021-03-16T18:59:52.2782182Z","leaveDateTime":"2021-03-16T19:06:47.7218491Z","durationInSeconds":415},{"joinDateTime":"2021-03-16T19:09:23.9834702Z","leaveDateTime":"2021-03-16T19:16:31.1381195Z","durationInSeconds":427},{"joinDateTime":"2021-03-16T19:20:27.7094382Z","leaveDateTime":"2021-03-16T19:25:37.7121956Z","durationInSeconds":310}]}]}'
    ]);
    \App\Models\User::create([
      'status' => 'active',
      'user_type' => 1,
      'name' => 'Esau Saravia',
      'email' => 'esau@mediaurea.com',
      'password' => bcrypt('dye2159')
    ]);
    for ($i = 0; $i < 3; $i++) {
      $profe = \App\Models\User::create([
        'status' => 'active',
        'user_type' => 3,
        'name' => "Profe{$i}",
        'email' => "profe{$i}@wiseabc.net",
        'password' => bcrypt('password')
      ]);
      $profe->saveHorarios($horarios[$i]);
      /**
       * FALTA ASIGNAR HORARIO PARA PROFESOR
       * $profe->saveHorarios([1=>[13,14]])
       */
    }

    foreach ($paymentConcepts as $paymentConcept) {
      DB::table('payment_concepts')->insert([
        'concept' => $paymentConcept['concept'],
        'amount' => $paymentConcept['amount'],
      ]);
    }

    $profesores = \App\Models\User::where('user_type', 3)->get();
    foreach ($cursos as $key => $curso) {
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

      /**
       * ASIGNAR HORAIRO A CLASSROOM
       * $classroom->saveHorarios([1=>[13,14]])
       */
      $classroom->saveHorarios($horarios[$key]);

      /**
       * CREAR SCHEDULE CON HORARIO DE CLASE
       * $fechahora = $classroom->nextSchedule()
       *
       */
      $schedule = Schedule::create([
        'class_id' => $classroom->id,
        'teams_id' => $teamsInfo->id,
        'fechahora' => $classroom->nextSchedule(),
      ]);

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
