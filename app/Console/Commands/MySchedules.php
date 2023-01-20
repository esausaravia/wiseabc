<?php

namespace App\Console\Commands;

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
      $json = '{
    "@odata.context": "https://graph.microsoft.com/v1.0/$metadata#users(\'5d8d505c-864f-4804-88c7-4583c966cde8\')/calendars(\'AAMkAGViNDU9zAAAAAGtlAAA%3D\')/events/$entity",
    "@odata.etag": "W/\"/IUUrIl3PkG1JCSsPfU+8wAAGXjGjw==\"",
    "id": "AAMkAGViNDU7zAAAAA7zAAAZe6CkAAA=",
    "createdDateTime": "2019-02-28T21:36:26.7105485Z",
    "lastModifiedDateTime": "2019-02-28T21:36:26.9577227Z",
    "changeKey": "/IUUrIl3PkG1JCSsPfU+8wAAGXjGjw==",
    "categories": [],
    "originalStartTimeZone": "Pacific Standard Time",
    "originalEndTimeZone": "Pacific Standard Time",
    "iCalUId": "040000008200C780DAE",
    "reminderMinutesBeforeStart": 15,
    "isReminderOn": true,
    "hasAttachments": false,
    "hideAttendees": false,
    "subject": "Lets go for lunch",
    "bodyPreview": "Does next month work for you?",
    "importance": "normal",
    "sensitivity": "normal",
    "isAllDay": false,
    "isCancelled": false,
    "isDraft": false,
    "isOrganizer": true,
    "responseRequested": true,
    "seriesMasterId": null,
    "showAs": "busy",
    "type": "singleInstance",
    "webLink": "https://outlook.office365.com/owa/?itemid=AAMkAGViNDU7zAAAAA7zAAAZe6CkAAA%3D&exvsurl=1&path=/calendar/item",
    "onlineMeetingUrl": null,
    "isOnlineMeeting": true,
    "onlineMeetingProvider": "teamsForBusiness",
    "recurrence": null,
    "responseStatus": {
      "response": "organizer",
        "time": "0001-01-01T00:00:00Z"
    },
    "body": {
      "contentType": "html",
        "content": "Does next month work for you?"
    },
    "start": {
      "dateTime": "2019-03-10T12:00:00.0000000",
        "timeZone": "Pacific Standard Time"
    },
    "end": {
      "dateTime": "2019-03-10T14:00:00.0000000",
        "timeZone": "Pacific Standard Time"
    },
    "location": {
      "displayName": "Harrys Bar",
        "locationType": "default",
        "uniqueId": "Harrys Bar",
        "uniqueIdType": "private"
    },
    "locations": [
        {
          "displayName": "Harrys Bar",
            "locationType": "default",
            "uniqueId": "Harrys Bar",
            "uniqueIdType": "private"
        }
    ],
    "attendees": [
        {
          "type": "required",
            "status": {
          "response": "none",
                "time": "0001-01-01T00:00:00Z"
            },
            "emailAddress": {
          "name": "Adele Vance",
                "address": "AdeleV@contoso.OnMicrosoft.com"
            }
        }
    ],
    "organizer": {
      "emailAddress": {
        "name": "Megan Bowen",
            "address": "MeganB@contoso.OnMicrosoft.com"
        }
    },
    "onlineMeeting": {
      "joinUrl": "https://teams.microsoft.com/l/meetup-join/19%3ameeting_NzIyNzhlMGEtM2YyZC00ZmY0LTlhNzUtZmZjNWFmZGNlNzE2%40thread.v2/0?context=%7b%22Tid%22%3a%2272f988bf-86f1-41af-91ab-2d7cd011db47%22%2c%22Oid%22%3a%22bc55b173-cff6-457d-b7a1-64bda7d7581a%22%7d",
        "conferenceId": "177513992",
        "tollNumber": "+1 425 555 0123"
    }
}';
      $data = json_decode($json, true);

      $classes = Classroom::all();
      foreach($classes as $class) {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addWeeks(4);
        $schedules = Schedule::whereBetween('fechahora', [$startDate, $endDate])->where('class_id', $class->id)->get();
        $count = $schedules->count();
        $classesPerWeek = $class->ritmo;

        if($count >= $classesPerWeek*4 ){
          continue;
        }

        //$data = Controllers\MsApiController::createEvent()

        $nextClass = $class->nextSchedule();
        $teamsInfo = new TeamsInfo();
        $teamsInfo->msid = $data['onlineMeeting']['conferenceId'];
        $teamsInfo->link = $data['onlineMeeting']['joinUrl'];
        $teamsInfo->info = $json;
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
