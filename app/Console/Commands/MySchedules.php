<?php

namespace App\Console\Commands;
use App\Http\Controllers\Admin\MsApiController;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TeamsInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class MySchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @re  turn int
     */
    public function handle()
    {
      /**
       * Mover a Controllers\MsApiController::createEvent()
       */

      $msApi = new MsApiController();
      $token = $msApi->getAccessToken();
      $classes = Classroom::all();
      foreach($classes as $class) {
        $startDate = Carbon::now();
        //validar qeu la clase tenga horarios
        $endDate = Carbon::now()->addWeeks(4);
        $schedules = Schedule::whereBetween('fechahora', [$startDate, $endDate])->where('class_id', $class->id)->get();
        $count = $schedules->count();
        $classesPerWeek = $class->ritmo;

        if($count >= $classesPerWeek*4 ){
          continue;
        }
        $nextClass = $class->nextSchedule();

        $curso = $class->curso->nombre;
        $data = $msApi->createOnlineMeeting($curso,$nextClass, $token);

        $teamsInfo = new TeamsInfo();
        $teamsInfo->msid = $data['id'];
        $teamsInfo->link = $data['onlineMeeting']['joinUrl'] ;
        $teamsInfo->info = json_encode($data);
        $teamsInfo->report = "";
        $teamsInfo->save();

        $schedule = new Schedule();
        $schedule->class_id = $class->id;
        $schedule->teams_id = $teamsInfo->id;
        $schedule->fechahora = $nextClass;
        $schedule->save();

      }
      return "Cron job is working fine!";

    }
}
