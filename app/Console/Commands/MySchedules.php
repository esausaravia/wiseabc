<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MySchedules extends Command
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
        /*Generar para schedules
        consultar la tabla schedules si hay suficientes Schedules para las siguientes 4 semanas ritmo(numero de clases por semana) * 4 semanas
         si hay suficientes pasar a siguiente classroom
        Obtener siguiente clase siguiente (Models/Classroom->nextSchedule())
        consultar ms.api create online meeting
        Guardar onlinemeeting msid , link , info json en tabla teams_infos
        crear Schedule con class_id, teams_info_id y fechahora de siguiente clase
        */

        return 0;
    }
}
