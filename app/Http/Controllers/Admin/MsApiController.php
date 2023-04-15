<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MsApiController extends Controller
{

  public static $token = null;

  public static function getFileToken()
  {
    $fileToken = Storage::get('MsApiToken.txt');

    if ( empty($fileToken) ) {
      return null;
    }

    $fileToken = json_decode($fileToken);

    if ( empty($fileToken) || !is_object($fileToken) || empty($fileToken->expires_at) ) {
      return null;
    }

    $expires_at = \Carbon\Carbon::parse($fileToken->expires_at);

    if ( !is_object($expires_at) ) {
      return null;
    }

    if ( $expires_at->subSeconds(30)->lessThan( now() ) ) {
      return null;
    }

    self::$token = $fileToken;
    return $fileToken;
  }

  /**
   * Solicita un Access Token a Microsoft Graph API
   * https://learn.microsoft.com/en-us/graph/auth-v2-service#4-get-an-access-token
   *
   */
  public static function reqBearerToken(){
    Log::info('MsApiController::reqBearerToken');

    $guzzle = new \GuzzleHttp\Client();
    $url = env('OAUTH_APP_TOKEN_ENDPOINT');

    $response = $guzzle->post( $url, [
      'form_params' => [
        'client_id' => env('OAUTH_APP_ID'),
        'client_secret' => env('OAUTH_CLIENT_ID'),
        'scope' => env('OAUTH_SCOPES'),
        'grant_type' => 'client_credentials',
      ],
    ]);

    $resBody = $response->getBody()->getContents();

    self::$token = json_decode( $resBody );

    self::$token->expires_at = now()->addSeconds( self::$token->expires_in )->format('Y-m-d H:i:s');

    Storage::put('MsApiToken.txt', json_encode(self::$token) );

    return self::$token;
  }

  public static function getAccessToken(){

    if ( empty(self::$token) || !is_object(self::$token) || empty(self::$token->access_token) )
    {
      if ( self::getFileToken()==null ) {
        self::reqBearerToken();
      }
    }

    return self::$token->access_token;
  }

  public function createOnlineMeeting( $subject, $fecha, $reqBody=array() )
  {
    if ( empty($subject) || empty($fecha) ) {
      return false;
    }

    if ( is_object($fecha) && class_basename($fecha)==='Carbon' )
    {
      $fecha = $fecha->copy();
    }
    else if ( is_string($fecha) && !empty($fecha) )
    {
      $fecha = \Carbon\Carbon::parse($fecha);
    }
    else {
      return false;
    }

    $token = self::getAccessToken();

    $client = new \GuzzleHttp\Client([
      'base_uri' => 'https://graph.microsoft.com/v1.0/',
      'headers' => [
        'Authorization' => 'Bearer ' . self::$token->access_token,
        'Content-Type' => 'application/json'
      ]
    ]);

    $defaults = [
      'body' => [
        'contentType' => 'HTML',
        'content' => $subject
      ],
      'location' => [
        'displayName' => 'WiseABC Online Classroom',
      ],
      'isOnlineMeeting' => true,
      'onlineMeetingProvider' => 'teamsForBusiness'
    ];

    if ( !is_array($reqBody) ) {
      $reqBody = array();
    }

    $body = array_merge_recursive( $defaults, $reqBody );

    $fecha->setTimezone('-0600');

    $body = array_merge_recursive( $body, [
      'subject' => $subject,
      'start' => [
        'dateTime' => $fecha->format('Y-m-d\TH:i:s'),
        'timeZone' => 'America/Mexico_City',
      ],
      'end' => [
        'dateTime' => $fecha->copy()->addMinutes(40)->format('Y-m-d\TH:i:s'),
        'timeZone' => 'America/Mexico_City',
      ]
    ] );

    $response = $client->post('users/esau@wiseabcenglish.com/calendar/events', [
      'body' => json_encode($body)
    ]);
    return json_decode( $response->getBody()->getContents(), true);
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
