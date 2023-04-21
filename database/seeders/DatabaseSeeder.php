<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Classroom;
use App\Models\Curso;
use App\Models\Schedule;
use App\Models\TeamsInfo;
use App\Models\Attendance;
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
    DB::table('attendances')->truncate();
    DB::table('attendance_pconcept')->truncate();
    DB::table('classrooms')->truncate();
    DB::table('class_horarios')->truncate();
    DB::table('class_student')->truncate();
    DB::table('cursos')->truncate();
    DB::table('payments')->truncate();
    DB::table('payment_concepts')->truncate();
    DB::table('schedules')->truncate();
    DB::table('teams_infos')->truncate();
    DB::table('usermetas')->truncate();
    DB::table('users')->truncate();
    DB::table('user_horarios')->truncate();

    /**
     * Admin User
     */
    \App\Models\User::create([
      'status' => 'active',
      'user_type' => 1,
      'name' => 'Esau Saravia',
      'email' => 'esau@mediaurea.com',
      'password' => bcrypt('dye2159')
    ]);

    /**
     * Cursos
     */
    $this->call([
      CursoSeeder::class
    ]);

    /**
     * Profesores
     */
    $this->call([
      TeacherSeeder::class
    ]);

    /**
     * Conceptos de pago
     */
    $this->call([
      PaymentConceptSeeder::class
    ]);
    $conceptosDePago = \App\Models\PaymentConcept::all();

    /**
     * CREAR AL MENOS 1 ROW DE TEAMSINFO
     */
    $teamsInfo = TeamsInfo::create([
      'msid' => 'MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZiMi04ZdFpHRTNaR1F6WGhyZWFkLnYy',
      'link' => 'https://teams.microsoft.com/l/meetup-join/19%3ameeting_M2IzYzczNTItYmY3OC00MDlmLWJjMzUtYmFiMjNlOTY4MGEz%40thread.skype/0?context=%7b%22Tid%22%3a%2272f988bf-86f1-41af-91ab-2d7cd011db47%22%2c%22Oid%22%3a%22550fae72-d251-43ec-868c-373732c2704f%22%7d',
      'info' => '{"@odata.type":"#microsoft.graph.onlineMeeting","@odata.context":"https://graph.microsoft.com/v1.0/#users(\'f4053f86-17cc-42e7-85f4-f0389ac980d6\')/onlineMeetings/","audioConferencing":{"tollNumber":"+12525634478","tollFreeNumber":"+18666390588","ConferenceId":"2425999","dialinUrl":"https://dialin.teams.microsoft.com/22f12fa0-499f-435b-bc69-b8de580ba330?id=2425999"},"chatInfo":{"threadId":"19:meeting_M2IzYzczNTItYmY3OC00MDlmLWJjMzUtYmFiMjNlOTY4MGEz@thread.skype","messageId":"0","replyChainMessageId":"0"},"creationDateTime":"2019-07-11T02:17:17.6491364Z","startDateTime":"2019-07-11T02:17:17.6491364Z","endDateTime":"2019-07-11T02:47:17.651138Z","id":"MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZiMi04ZdFpHRTNaR1F6WGhyZWFkLnYy","joinWebUrl":"https://teams.microsoft.com/l/meetup-join/19%3ameeting_M2IzYzczNTItYmY3OC00MDlmLWJjMzUtYmFiMjNlOTY4MGEz%40thread.skype/0?context=%7b%22Tid%22%3a%2272f988bf-86f1-41af-91ab-2d7cd011db47%22%2c%22Oid%22%3a%22550fae72-d251-43ec-868c-373732c2704f%22%7d","participants":{"organizer":{"identity":{"user":{"id":"550fae72-d251-43ec-868c-373732c2704f","displayName":"Heidi Steen"}},"upn":"upn-value"}},"subject":"User Token Meeting"}',
      'report' => '{"@odata.context":"https://graph.microsoft.com/v1.0/#users(\'16664f75-11dc-4870-bec6-38c1aaa81431\')/onlineMeetings(\'MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZ\')/attendanceReports(\'c9b6db1c-d5eb-427d-a5c0-20088d9b22d7\')","id":"c9b6db1c-d5eb-427d-a5c0-20088d9b22d7","totalParticipantCount":1,"meetingStartDateTime":"2021-10-05T04:38:23.945Z","meetingEndDateTime":"2021-10-05T04:43:49.77Z","attendanceRecords":[{"emailAddress":"frederick.cormier@contoso.com","totalAttendanceInSeconds":1152,"role":"Presenter","identity":{"id":"dc17674c-81d9-4adb-bfb2-8f6a442e4623","displayName":"Frederick Cormier","tenantId":null},"attendanceIntervals":[{"joinDateTime":"2021-03-16T18:59:52.2782182Z","leaveDateTime":"2021-03-16T19:06:47.7218491Z","durationInSeconds":415},{"joinDateTime":"2021-03-16T19:09:23.9834702Z","leaveDateTime":"2021-03-16T19:16:31.1381195Z","durationInSeconds":427},{"joinDateTime":"2021-03-16T19:20:27.7094382Z","leaveDateTime":"2021-03-16T19:25:37.7121956Z","durationInSeconds":310}]}]}'
    ]);


    /**
     * Crear classrooms, schedules y attendances
     */
    $hoy = now('-0600');
    $enWeekdays = config('wiseabc.en_weekdays');
    $cursoStart = $hoy->copy()->subMonth();

    $cursos = Curso::where('nivel','<',3)->get();
    $profesores = \App\Models\User::where('user_type',3)->take(2)->get();

    foreach( $profesores AS $profe ) {
      $horarios = [1 => [9, 11], 3 => [9, 11], 5 => [9, 11]];

      foreach ($cursos AS $curso) {
        $dia = array_key_first($horarios);
        if ( empty( $horarios[$dia] ) ) {
          unset($horarios[$dia]);
          $dia = array_key_first($horarios);
        }
        if ( empty( $horarios[$dia] ) ) {
          break;
        }
        $hr = array_shift( $horarios[$dia] );

        // Inserta el classroom utilizando el ID del curso
        //1 clase para cada profesor
        //1 clase por cada curso
        $classroom = Classroom::create([
          'teacher_id' => $profe->id,
          'curso_id' => $curso->id,
          'status' => 'active',
          'tipo' => 1,
          'ritmo' => 1,
          'start' => $cursoStart->copy()->setTimezone('UTC')
        ]);

        /**
         * ASIGNAR HORARiO A CLASSROOM
         * $classroom->saveHorarios([1=>[9]])
         * lunes 9am
         */
        $classroom->saveHorarios([$dia=>[$hr]]);

        /**
         * CREAR Attendances del mes pasado
         *
         */
        $_sigdia = $cursoStart->copy()->next( $enWeekdays[($dia)] )->hour($hr)->minute(0);
        while( $_sigdia->lessThan($hoy) ) {

          /**
           * Crear asistencia
           */
          $attendance = Attendance::create([
            'user_id' => $profe->id,
            'class_id' => $classroom->id,
            'teams_id' => $teamsInfo->id,
            'fechahora' => $_sigdia->copy()->setTimezone('UTC'),
            'duracion' => 2400, //40 mins
            'puntual' => true
          ]);

          foreach($conceptosDePago AS $pconcept) {
            $attendance->pconcepts()->attach( $pconcept->id, ['amount'=>$pconcept->amount] );
          }

          $_sigdia->next( $enWeekdays[($dia)] )->hour($hr)->minute(0);
        }//END mientras _sigdia < hoy

        /**
         * CREAR SCHEDULE CON HORARIO DE CLASE
         * $fechahora = $classroom->sigFechaHora()
         */
        echo '    next schedule: '.$classroom->sigFechaHora()->format('Y-m-d H:i O').PHP_EOL;
        $schedule = Schedule::create([
          'class_id' => $classroom->id,
          'fechahora' => $classroom->sigFechaHora()->copy()->setTimezone('UTC'),
        ]);

        /**
         * Crear 2 estudiantes para cada clase
         */
        $students = \App\Models\User::factory()->count(2)->create([
          'user_type'=>2,
          'password' => bcrypt('qwerasdf')
        ]);
        foreach($students AS $student) {

          $arrName = explode(' ', $student->name);

          $student->saveMetas([
            'lname' => array_pop($arrName),
            'fname' => implode(' ', $arrName),
            'edad'=>$curso->edad,
            'nivel'=>$curso->nivel,
            'clase_tipo' => 1,
            'ritmo' => 1,
            'suscripcion'=>1,
            'timezone' => '-0600'
          ]);

          $student->saveHorarios([1=>[$hr]]);
          $classroom->students()->attach($student->id);
        }

      }//END por cada curso
    }//endforeach profe

    /**
     * Crear un pago para cada profesor
     */
    $profesores = $profesores->slice(0, ceil($profesores->count()/2) );
    $finmes = $cursoStart->copy()->endOfMonth();
    foreach( $profesores AS $profe ) {
      $Asistencias = Attendance::with(['pconcepts'])->where('user_id', $profe->id)->where('fechahora','<',$finmes)->get();

      $pago_amount = 0;
      foreach( $Asistencias AS $asistencia ) {
        foreach($asistencia->pconcepts AS $pconcept) {
          $pago_amount += $pconcept->recibo->amount;
        }
      }

      $pago = \App\Models\Payout::create([
        'user_id'=>$profe->id,
        'status'=>'paid',
        'reference'=>uuid_create(),
        'amount'=>$pago_amount
      ]);

      $pago->attendances()->saveMany($Asistencias);

    }//endforeach profe


    $this->call([
      StudentSeeder::class
    ]);

  }//END run method
}
