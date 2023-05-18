<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\MsApiController;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TeamsInfo;
use Illuminate\Console\Command;

class MySchedulesToAttendances extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'my:attendance';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Convertir Schedules viejos a registor de Asistencias';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    // Obtener horarios vencidos
    $schedules = Schedule::with(['classroom'])->where('fechahora', '<', now()->subHour() )->get();

    // Iniciar transacción

    foreach ($schedules as $schedule) {
      $classroom = $schedule->classroom;
      $attendance = Attendance::create([
        'user_id' => $classroom->teacher_id,
        'class_id' => $classroom->id,
        'teams_id' => null,
        'fechahora' => $schedule->fechahora,
        'duracion' => 2400,
        'puntual' => true
      ]);
      /*
      $msApi = new MsApiController();
      $meeting = $msApi->getReport($classroom->teacher_id, $schedule['teams_id']);
      //dd($meeting);
      $total_attendance_in_seconds = $meeting['attendanceRecords'][0]['totalAttendanceInSeconds'];
      $join_time = $meeting['attendanceRecords'][0]['attendanceIntervals'][0]['joinDateTime'];

      $attendance->duracion = $total_attendance_in_seconds;
      $attendance->puntual = ($join_time < Carbon::parse('11:05')) ? true : false;

      $attendance->save();
      */


      /*$team_info = TeamsInfo::where('id', $schedule['teams_id'])->first();
      $team_info->report = json_encode($meeting);
      $team_info->save();*/
      $schedule->delete();
    }
    return 0;

  }
}
