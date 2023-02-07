<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\MsApiController;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TeamsInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MySchedulesToAttendances extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'my:atendance';

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


    foreach ($schedules as $schedule) {
      $classroom = Classroom::find($schedule->class_id);
      $msApi = new MsApiController();
      $meeting = $msApi->getReport($classroom->teacher_id, $schedule['teams_id']);
      //dd($meeting);
      $total_attendance_in_seconds = $meeting['attendanceRecords'][0]['totalAttendanceInSeconds'];
      $join_time = $meeting['attendanceRecords'][0]['attendanceIntervals'][0]['joinDateTime'];

      $attendance = new Attendance;
      $attendance->user_id = $classroom->teacher_id;
      $attendance->class_id = $classroom->id;
      $attendance->teams_id = null;
      $attendance->fechahora = $schedule->fechahora;
      $attendance->duracion = $total_attendance_in_seconds;
      $attendance->puntual = ($join_time < Carbon::parse('11:05')) ? true : false;
      $attendance->save();

      $team_info = TeamsInfo::where('id', $schedule['teams_id'])->first();
      $team_info->report = json_encode($meeting);
      $team_info->save();
      $schedule->delete();
    }
    return 0;

  }
}
