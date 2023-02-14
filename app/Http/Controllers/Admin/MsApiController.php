<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TeamsInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MsApiController extends Controller
{

  public static $secret_id = 'MICROSOFTAPISECRETID';
  public static $secret_value = 'MICROSOFTAPISECRET';

  /**
   *  Titulo del evento
   * @param $subject
   * Fecha  del evento
   * @param $fecha
   */
  public function createOnlineMeeting( $subject,  $fecha)
  {
    $json = $json = '{
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
    return json_decode($json, true);
  }


  /**
   * Get meetingAttendanceReport
   *
   * @param $userId
   * @param $meetingId
   */
  public function getReport($userId, $meetingId)
  {
    /**
     * Primero debemos obtener listado
     * List meetingAttendanceReports
     * https://learn.microsoft.com/en-us/graph/api/meetingattendancereport-list?view=graph-rest-1.0&tabs=http
     */


    /**
     * Foreach response.value => record
     *   record.id
     *
     *   Get meetingAttendanceReport
     *   GET /users/{userId}/onlineMeetings/{meetingId}/attendanceReports/{reportId}
     *
     */



    /*$graph = new Graph();
    $graph->setAccessToken($access_token);
    $meeting = $graph->createRequest("GET", "/meetingAttendanceReport?startDateTime=".$fechahora."&endDateTime=".$fechahora)
      ->setReturnType(Model\MeetingAttendanceReport::class)
      ->execute();
  */
    $meeting = '
{
  "@odata.context": "https://graph.microsoft.com/v1.0/$metadata#users(\'16664f75-11dc-4870-bec6-38c1aaa81431\')/onlineMeetings(\'MSpkYzE3Njc0Yy04MWQ5LTRhZGItYmZ\')/attendanceReports(\'c9b6db1c-d5eb-427d-a5c0-20088d9b22d7\')",
  "id": "c9b6db1c-d5eb-427d-a5c0-20088d9b22d7",
  "totalParticipantCount": 1,
  "meetingStartDateTime": "2021-10-05T04:38:23.945Z",
  "meetingEndDateTime": "2021-10-05T04:43:49.77Z",
  "attendanceRecords": [
    {
      "emailAddress": "frederick.cormier@contoso.com",
      "totalAttendanceInSeconds": 1152,
      "role": "Presenter",
      "identity": {
        "id": "dc17674c-81d9-4adb-bfb2-8f6a442e4623",
        "displayName": "Frederick Cormier",
        "tenantId": null
      },
      "attendanceIntervals": [
        {
          "joinDateTime": "2021-03-16T18:59:52.2782182Z",
          "leaveDateTime": "2021-03-16T19:06:47.7218491Z",
          "durationInSeconds": 415
        },
        {
          "joinDateTime": "2021-03-16T19:09:23.9834702Z",
          "leaveDateTime": "2021-03-16T19:16:31.1381195Z",
          "durationInSeconds": 427
        },
        {
          "joinDateTime": "2021-03-16T19:20:27.7094382Z",
          "leaveDateTime": "2021-03-16T19:25:37.7121956Z",
          "durationInSeconds": 310
        }
      ]
    }
  ]
}';

    return json_decode($meeting, true);

  }
}
