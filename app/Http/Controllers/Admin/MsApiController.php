<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;

class MsApiController extends Controller
{

  public static $token = null;

  public static function getAccessToken(){
    $guzzle = new \GuzzleHttp\Client();
    $url = env('OAUTH_APP_TOKEN_ENDPOINT');

    self::$token = json_decode( $guzzle->post($url, [
      'form_params' => [
        'client_id' => env('OAUTH_APP_ID'),
        'client_secret' => env('OAUTH_CLIENT_ID'),
        'scope' => env('OAUTH_SCOPES'),
        'grant_type' => 'client_credentials',
      ],
    ])->getBody()->getContents() );

    return self::$token->access_token;
  }

  public function createOnlineMeeting( $subject,  $fecha, $reqBody=array())
  {
    if ( empty(self::$token) || empty(self::$token->access_token) )
    {
      $token = self::getAccessToken();
    }

    $client = new \GuzzleHttp\Client([
      'base_uri' => 'https://graph.microsoft.com/v1.0/',
      'headers' => [
        'Authorization' => 'Bearer ' . self::$token->access_token,
        'Content-Type' => 'application/json'
      ]
    ]);

    if ( is_object($fecha) && class_basename($fecha)==='Carbon' )
    {
      $fecha = $fecha->copy()->setTimezone('-0600');
    }
    else if ( is_string($fecha) && !empty($fecha) ) {
      $fecha = new \Carbon\Carbon($fecha, '-0600');
    }
    else {
      return false;
    }

    $body = [
      'subject' => $subject,
      'body' => [
        'contentType' => 'HTML',
        'content' => $subject
      ],
      'start' => [
        'dateTime' => $fecha->format('Y-m-d\TH:i:s'),
        'timeZone' => 'America/Mexico_City',
      ],
      'end' => [
        'dateTime' => $fecha->copy()->addMinutes(40)->format('Y-m-d\TH:i:s'),
        'timeZone' => 'America/Mexico_City',
      ],
      'location' => [
        'displayName' => 'WiseABC Online Classroom',
      ],
      'isOnlineMeeting' => true,
      'onlineMeetingProvider' => 'teamsForBusiness'
    ];

    $body = array_merge_recursive( $body, $reqBody );

    $response = $client->post('users/esau@wiseabcenglish.com/calendar/events', [
      'body' => json_encode($body)
    ]);
    return json_decode($response->getBody()->getContents(), true);
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
