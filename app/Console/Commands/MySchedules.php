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
      $hoy_utc = now();
      $hastaFecha = now()->addWeeks($paraSemanas)->addHour();

      $msApi = new MsApiController();
      $Classrooms = Classroom::with(['teacher','curso'])->where('status','ACTIVE')
                    ->where('ends_at','>=', $hoy_utc->format('Y-m-d') )->get();

      foreach($Classrooms as $clase) {
        echo "Schedules for Classroom #{$clase->id}\n";

        /**
         * Buscamos schedules sin registro en Microsoft Teams
         */
        $Schedules = Schedule::whereNull('teams_id')->where('class_id', $clase->id)->get();
        foreach( $Schedules AS $schedule)
        {
          echo '  schedule->fechahora: '.$schedule->fechahora->format('Y-m-d H:i O').PHP_EOL;

          $data = $msApi->createOnlineMeeting($clase->teacher->email, $clase->curso->name, $schedule->fechahora);

          if ( is_object($data) )
          {
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
        if ( is_object($lastSchedule) )
        {
          $lastSchedule = $lastSchedule->fechahora->copy();
          $lastSchedule->setTimezone('-0600');
          echo '  lastSchedule: '.$lastSchedule->format('Y-m-d H:i O').PHP_EOL;
        }
        else
        {
          $lastSchedule = $hoy_utc;
        }

        while( $schedules_count < $schedules_need ) {

          $nextClass = $clase->sigFechaHora( $lastSchedule->addHour() );
          echo '  nextClass: '.$nextClass->format('Y-m-d H:i O').PHP_EOL;

          $teamsInfo = null;

          /*
          $data = $msApi->createOnlineMeeting($clase->curso->name, $nextClass);

          $teamsInfo = TeamsInfo::create([
            'msid' => $data['id'],
            'link' => $data['onlineMeeting']['joinUrl'],
            'info' => json_encode($data),
            'report' => ""
          ]);*/

          $schedule = new Schedule();
          $schedule->class_id = $clase->id;
          $schedule->teams_id = is_object($teamsInfo) ? $teamsInfo->id : null;
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
