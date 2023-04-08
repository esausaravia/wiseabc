<?php

namespace App\Console\Commands;
use App\Http\Controllers\Admin\MsApiController;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TeamsInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
    protected $description = 'Agenda las siguientes clases';

    /**
     * Execute the console command.
     *
     * @re  turn int
     */
    public function handle()
    {
      $paraSemanas = 2; //cuantas semanas hacia adelante
      $enWeekdays = config('wiseabc.en_weekdays');//monday,tuesday,etc.
      $hoy = now('America/Mexico_City')->locale('es');
      $hastaFecha = $hoy->copy()->addWeeks($paraSemanas);

      $msApi = new MsApiController();
      $token = $msApi->getAccessToken();
      $Clases =  Classroom::where('status','active')
                    ->where('ends_at','>=', $hoy->format('Y-m-d') )->get();

      foreach($Clases as $clase) {

        $schedules = Schedule::whereBetween('fechahora', [$hoy, $hastaFecha])->where('class_id', $clase->id)->orderBy('fechahora')->get();
        $schedules_count = $schedules->count();//cuantos hay registrados
        $schedules_need = (int)$clase->ritmo * $paraSemanas;//cuantos necesitamos

        $lastSchedule = $schedules->last();
        if ( !empty($lastSchedule) ) {
          $lastSchedule = new Carbon($lastSchedule->fechahora, 'America/Mexico_City');
        }

        while( $schedules_count < $schedules_need ) {

          $nextClass = $clase->sigFechaHora( $lastSchedule->addHour() );

          $data = $msApi->createOnlineMeeting($clase->curso->name, $nextClass, $token);

          $teamsInfo = new TeamsInfo();
          $teamsInfo->msid = $data['id'];
          $teamsInfo->link = $data['onlineMeeting']['joinUrl'] ;
          $teamsInfo->info = json_encode($data);
          $teamsInfo->report = "";
          $teamsInfo->save();

          $schedule = new Schedule();
          $schedule->class_id = $clase->id;
          $schedule->teams_id = $teamsInfo->id;
          $schedule->fechahora = $nextClass;
          $schedule->save();

          $lastSchedule = $nextClass;
          $schedules_count++;
        }

      }//END foreach classes
      return "Cron job is working fine!";

    }
}
