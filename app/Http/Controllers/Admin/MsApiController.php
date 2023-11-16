<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MsApiController extends Controller
{
    public static $token = null;

    protected readonly string $tenantId;

    protected readonly string $clientId;

    protected readonly string $clientSecret;

    protected readonly int $accessTokenTtl;

    public function __construct()
    {
        $this->tenantId = env('MICROSOFT_GRAPH_TENANT_ID', null);
        $this->clientId = env('MICROSOFT_GRAPH_CLIENT_ID', null);
        $this->clientSecret = env('MICROSOFT_GRAPH_CLIENT_SECRET', null);
        $this->accessTokenTtl = 3300;

        throw_if(empty($this->tenantId), __CLASS__.' missing env MICROSOFT_GRAPH_TENANT_ID');
        throw_if(empty($this->clientId), __CLASS__.' missing env MICROSOFT_GRAPH_CLIENT_ID');
        throw_if(empty($this->clientSecret), __CLASS__.' missing env MICROSOFT_GRAPH_CLIENT_SECRET');
    }

    protected function getAccessToken()
    {
        return Cache::remember('microsoft-graph-api-access-token', 3300, function (): string {
            $response = Http::asForm()
                ->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token",
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'scope' => 'https://graph.microsoft.com/.default',
                    ]);

            $response->throw();

            Log::debug(__METHOD__, ['response' => $response->json()]);

            return $response->json('access_token');
        });
    }

    protected function getBaseRequest(): PendingRequest
    {
        return Http::withToken($this->getAccessToken())
            ->baseUrl('https://graph.microsoft.com/v1.0');
    }

    public function createOnlineMeeting($fecha, string $userMsId = 'admin@wiseabcenglish.com', string $subject = 'WiseABC Clase en línea', array $payload = [])
    {
        if (is_object($fecha) && class_basename($fecha) === 'Carbon') {
            $fecha = $fecha->copy();
        } elseif (is_string($fecha) && trim($fecha) !== '') {
            $fecha = \Carbon\Carbon::parse($fecha);
        } else {
            return false;
        }

        if (! is_array($payload)) {
            $payload = [];
        }

        $defaults = [
            'subject' => $subject,
            'body' => [
                'contentType' => 'HTML',
                'content' => $subject,
            ],
            'location' => [
                'displayName' => 'WiseABC Online Classroom',
            ],
            'isOnlineMeeting' => true,
            'onlineMeetingProvider' => 'teamsForBusiness',
        ];

        $payload = array_merge_recursive($defaults, $payload);

        $fecha->setTimezone('-0600');

        $payload = array_merge_recursive($payload, [
            'start' => [
                'dateTime' => $fecha->format('Y-m-d\TH:i:s'),
                'timeZone' => 'America/Mexico_City',
            ],
            'end' => [
                'dateTime' => $fecha->addMinutes(40)->format('Y-m-d\TH:i:s'),
                'timeZone' => 'America/Mexico_City',
            ],
        ]);

        return $this->getBaseRequest()
            ->post("/users/{$userMsId}/calendar/events", $payload)
            ->throw()->json();
    }

    public function getUserByMail(string $mail)
    {
        $response = $this->getBaseRequest()->get('/users/'.$mail);
        if ($response->failed()) {
            Log::error(__METHOD__, [
                'mail' => $mail,
                'resp_body' => $response->body(),
            ]);

            return null;
        }

        return $response->json();
    }

    public function getUserId(string $mail)
    {
        $msUser = $this->getUserByMail($mail);

        return is_object($msUser) ? $msUser->id : (is_array($msUser) ? $msUser['id'] : null);
    }

    public function getOnlineMeetingByJoinURL(string $userId, string $joinUrl)
    {
        $response = $this->getBaseRequest()
            ->get("/users/{$userId}/onlineMeetings?\$filter=JoinWebUrl eq '".rawurlencode($joinUrl)."'");

        if ($response->failed()) {
            Log::error(__METHOD__, [
                'joinUrl' => $joinUrl,
                'resp_body' => $response->body(),
            ]);

            return false;
        }

        return $response->json();
    }

    public function getAttendanceReportsList(string $userId, string $meetingId)
    {
        $response = $this->getBaseRequest()
            ->get("/users/{$userId}/onlineMeetings/{$meetingId}/attendanceReports?\$expand=attendanceRecords");

        if ($response->failed()) {
            Log::error(__METHOD__, [
                'userId' => $userId,
                'meetingId' => $meetingId,
                'resp_body' => $response->body(),
            ]);

            return false;
        }

        return $response->json();
    }

    public function getAttendanceReport(string $userId, string $meetingId, string $reportId)
    {
        $response = $this->getBaseRequest()
            ->get("/users/{$userId}/onlineMeetings/{$meetingId}/attendanceReports/{$reportId}?\$expand=attendanceRecords");

        if ($response->failed()) {
            Log::error(__METHOD__, [
                'userId' => $userId,
                'meetingId' => $meetingId,
                'resp_body' => $response->body(),
            ]);

            return false;
        }

        return $response->json();
    }

    /**
     * Get meetingAttendanceReport
     */
    public function getReport($userId, $meetingId)
    {
        /**
         * Primero debemos obtener listado
         * List meetingAttendanceReports
         * https://learn.microsoft.com/en-us/graph/api/meetingattendancereport-list?view=graph-rest-1.0&tabs=http
         */
        /*
        {"@odata.context":"","value":[{"id":"80e65d43-4180-4723-98eb-a115e5ed150a","totalParticipantCount":2,"meetingStartDateTime":"2023-05-25T19:14:41.28Z","meetingEndDateTime":"2023-05-25T19:49:21.697Z"}]}
        */

        /**
         * Filtrar reportes que sucedan únicamente en la fecha de la clase,
         * comiencen antes de la (hora de inicio +1hr),
         * y terminen después de la hora de inicio,
         *
         * Obtener únicamente IDs de reporte
         */

        /**
         * Foreach response.value => record
         *   record.id
         *
         *   Get meetingAttendanceReport
         *   GET /users/{userId}/onlineMeetings/{meetingId}/attendanceReports/{reportId}
         */
        /*
        {"@odata.context":"","id":"80e65d43-4180-4723-98eb-a115e5ed150a","totalParticipantCount":2,"meetingStartDateTime":"2023-05-25T19:14:41.28Z","meetingEndDateTime":"2023-05-25T19:49:21.697Z","attendanceRecords@odata.context":"","attendanceRecords":[{"id":"fba3c228-3a07-42f1-8722-63958f8a81e9","emailAddress":"admin@wiseabcenglish.com","totalAttendanceInSeconds":1868,"role":"Organizer","identity":{"id":"fba3c228-3a07-42f1-8722-63958f8a81e9","displayName":"Administrator","tenantId":"d2bd8599-9a43-4141-8364-a885f8571570"},"attendanceIntervals":[{"joinDateTime":"2023-05-25T19:18:12.8777223Z","leaveDateTime":"2023-05-25T19:49:21.6979659Z","durationInSeconds":1868}]},{"id":"1445c618-3e8d-4d1a-8790-7ad3b4eed537","emailAddress":"esau@wiseabcenglish2023.onmicrosoft.com","totalAttendanceInSeconds":1387,"role":"Presenter","identity":{"id":"1445c618-3e8d-4d1a-8790-7ad3b4eed537","displayName":"Esau Saravia","tenantId":"d2636ec3-22a2-4eaa-8e2a-72f28bfe0fef"},"attendanceIntervals":[{"joinDateTime":"2023-05-25T19:18:16.5166802Z","leaveDateTime":"2023-05-25T19:41:24.0500422Z","durationInSeconds":1387}]}]}
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
