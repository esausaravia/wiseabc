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
      $hoy = now('-0600');
      $hoy_utc = $hoy->copy()->setTimezone('UTC');
      $hastaFecha = $hoy_utc->copy()->addWeeks($paraSemanas)->addHour();

      $msApi = new MsApiController();
      $Clases =  Classroom::with(['curso'])->where('status','active')
                    ->where('ends_at','>=', $hoy->format('Y-m-d') )->get();

      foreach($Clases as $clase) {
        echo "Schedules for Classroom #{$clase->id}\n";

        /**
         * Buscamos schedules sin registro en Microsoft Teams
         */
        $Schedules = Schedule::whereNull('teams_id')->where('class_id', $clase->id)->get();
        foreach( $Schedules AS $schedule)
        {
          echo '  schedule->fechahora: '.$schedule->fechahora->format('Y-m-d H:i O').PHP_EOL;

          $data = $msApi->createOnlineMeeting($clase->curso->name, $schedule->fechahora);

          $teamsInfo = TeamsInfo::create([
            'msid'=>$data['id'],
            'link'=>$data['onlineMeeting']['joinUrl'],
            'info'=>json_encode($data),
            'report'=>""
          ]);

          $schedule->teams_id = $teamsInfo->id;
          $schedule->save();
          echo "  schedule->teams_id: {$schedule->teams_id}".PHP_EOL;
        }

        /**
         * Buscamos schedules faltantes
         */
        $schedules = Schedule::whereBetween('fechahora', [$hoy_utc, $hastaFecha])->where('class_id', $clase->id)->orderBy('fechahora')->get();

        $schedules_count = $schedules->count();//cuantos hay registrados
        echo "  schedules_count: {$schedules_count}\n";

        $schedules_need = (int)$clase->ritmo * $paraSemanas;//cuantos necesitamos
        echo "  schedules_need: {$schedules_need}\n";

        $lastSchedule = $schedules->last();
        if ( !empty($lastSchedule) ) {
          $lastSchedule = $lastSchedule->fechahora->copy();
        }

        $lastSchedule->setTimezone('-0600');
        echo '  lastSchedule: '.$lastSchedule->format('Y-m-d H:i O').PHP_EOL;

        while( $schedules_count < $schedules_need ) {

          $nextClass = $clase->sigFechaHora( $lastSchedule->addHour() );
          echo '  nextClass: '.$nextClass->format('Y-m-d H:i O').PHP_EOL;

          $data = $msApi->createOnlineMeeting($clase->curso->name, $nextClass);

          $teamsInfo = new TeamsInfo();
          $teamsInfo->msid = $data['id'];
          $teamsInfo->link = $data['onlineMeeting']['joinUrl'] ;
          $teamsInfo->info = json_encode($data);
          $teamsInfo->report = "";
          $teamsInfo->save();

          $schedule = new Schedule();
          $schedule->class_id = $clase->id;
          $schedule->teams_id = $teamsInfo->id;
          $schedule->fechahora = $nextClass->copy()->setTimezone('UTC');
          $schedule->save();

          $lastSchedule = $nextClass;
          $schedules_count++;
        }

        echo PHP_EOL;
      }//END foreach classes
      return "Cron job is working fine!";

    }
}
