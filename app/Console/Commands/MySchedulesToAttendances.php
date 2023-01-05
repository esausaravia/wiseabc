<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MySchedulesToAttendances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      // Obtener horarios vencidos
      $schedules = Schedule::where('fechahora', '<', now())->get();

      // Iniciar transacción
      DB::beginTransaction();

      try {
        // Recorrer horarios vencidos
        foreach ($schedules as $schedule) {
          // Obtener clase correspondiente
          $classroom = Classroom::find($schedule->class_id);

          // Crear registro de asistencia
          $attendance = new Attendance;
          $attendance->user_id = $classroom->teacher_id;
          $attendance->class_id = $classroom->id;
          $attendance->teams_id = null;
          $attendance->fechahora = $schedule->fechahora;
          $attendance->duracion = null;
          $attendance->puntual = null;
          $attendance->save();

          // Borrar horario
          $schedule->delete();

          // Conectar a Microsoft API para obtener informe de asistencia
          $graph = new Graph();
          $graph->setAccessToken($access_token);
          $meeting = $graph->createRequest("GET", "/meetingAttendanceReport?startDateTime=".$attendance->fechahora."&endDateTime=".$attendance->fechahora)
            ->setReturnType(Model\MeetingAttendanceReport::class)
            ->execute();
          // Actualizar registro de asistencia con datos del informe
          $attendance->duracion = $meeting->getDuration();
          $attendance->puntual = $meeting->getPunctuality();
          $attendance->save();

          // Obtener datos del informe de asistencia
          $attendance_data = json_decode($meeting->getAttendanceRecords()[0]->getAttendanceIntervals()[0]->getAttendanceData(), true);
          $join_time = new Carbon($meeting->getAttendanceRecords()[0]->getAttendanceIntervals()[0]->getJoinDateTime());
          $punctual = ($join_time < Carbon::parse('11:05')) ? true : false;
          $team_info = TeamsInfo::where('msid', $attendance_data['id'])->first();
          $team_info->info = $attendance_data['json'];
          $team_info->report = $meeting->getAttendanceRecords()[0]->getAttendanceData();
          $team_info->save();

        }
        // Commit de transacción
        DB::commit();
      } catch (\Exception $e) {
        // Rollback de transacción en caso de error
        DB::rollback();
        throw $e;
      }

      return 0;
    }


}
