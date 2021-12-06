<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use App\Models\User;
use App\Models\Event;
use Session;
use Auth;

use Illuminate\Http\Request;

class gCalendarController extends Controller
{
    protected $client;

    function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret_21914209338-78d3qmlr5jpl1fh40n0v73gqm3jfptjp.apps.googleusercontent.com.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client=$client;
     
    }

    public function index()
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);
           // Print the next 10 events on the user's calendar.
            $calendarId = 'primary';
            $optParams = array(
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            );
            $results = $service->events->listEvents($calendarId, $optParams);
            $events[] = $results->getItems();

            return $events;

        } else {
            return redirect('/oauth');
        }

    }

    public function oauth()
    {
        session_start();
        $rurl = action('gCalendarController@oauth');
        $this->client->setRedirectUri($rurl);
        
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            return redirect('/api/cal');
  
        }
    }
    public function create()
    {
        return view('calendar.createEvent');
    }

    public function store(Request $request)
    {
        session_start();
        $startDateTime = $request->start_date;
        $endDateTime = $request->end_date;

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';
            $event = new Google_Service_Calendar_Event([
                'summary' => $request->title,
                'location' =>  $request->location,
                'description' => $request->description,
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'America/Santiago',
                ],
                'end' =>
                [
                    'dateTime' => $endDateTime,
                    'timeZone' => 'America/Santiago',
                ],
                "conferenceData" => [
                    "createRequest" => [
                        "conferenceId" => [
                            "type" => "eventNamedHangout"
                        ],
                        "requestId" => "123"
                    ]
                ],
                'recurrence' => [
                    'RRULE:FREQ=DAILY;COUNT=2'
                ],
                'attendees' => [
                    array('email' => 'lpage@example.com'),
                    array('email' => 'sbrin@example.com'),
                ],
                'reminders' => [
                    'useDefault' => FALSE,
                    'overrides' => [
                        array('method' => 'email', 'minutes' => 24 * 60),
                        array('method' => 'popup', 'minutes' => 10),
                    ],
                ],
            ]);
            $results = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);
            if (!$results) {
                return response()->json(['status' => 'error', 'message' => 'Ha sucedido un error']);
            }
            $event = new Event();
            $event->event_google_id = $results->id;
            $event->location = $results->location;
            $event->description = $results->description;
            $event->summary = $results->summary;
            $event->htmlLink = $results->htmlLink;
            $event->hangoutLink = $results->hangoutLink;
            $event->email_creator = $results->creator->email;
            $event->save();
            return response()->json(['status' => 'success', 'message' => $results->getHangoutLink()]);
        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function show($eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);

                $service = new Google_Service_Calendar($this->client);
                $event = $service->events->get('primary', $eventId);

            if (!$event) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
                return response()->json(['status' => 'success', 'data' => $event]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function edit($id)
    {
        //
    }

    public function actualizar()
    {
        $event_id = 'c77puvgav9a7c8k8mlfleil9og'; 
        return view('calendar.updateEvent', compact('event_id'));
    }

    public function eliminar()
    {
        $event_id = 'heqnvacoppet9842taisnjgp40_20211123T160000Z'; 
        return view('calendar.deleteEvent', compact('event_id'));
    }

    public function update(Request $request, $eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $startDateTime = Carbon::parse($request->start_date)->toRfc3339String();
            $eventDuration = 30; //minutes

            if ($request->has('end_date')) {
                $endDateTime = Carbon::parse($request->end_date)->toRfc3339String();

            } else {
                $endDateTime = Carbon::parse($request->start_date)->addMinutes($eventDuration)->toRfc3339String();
            }

            // recuperar evento de la API.
            $event = $service->events->get('primary', $eventId);
            $event->setSummary($request->title);
            $event->setDescription($request->description);

            //start time
            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($startDateTime);
            $start->SetTimeZone('America/Santiago');
            $event->setStart($start);

            //end time
            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($endDateTime);
            $end->SetTimeZone('America/Santiago');
            $event->setEnd($end);

            $updatedEvent = $service->events->update('primary', $event->getId(), $event);

            if (!$updatedEvent) {
                return response()->json(['status' => 'error', 'message' => 'Ha sucedido un error']);
            }
            
            $eventDb=Event::where(
            array(
                'event_google_id' => $eventId,
            ))->first();
            
            $event = Event::findOrFail($eventDb->id);
            $event->location = $request->location;
            $event->description = $request->description;
            $event->summary = $request->summary;
            $event->save();
            
            return response()->json(['status' => 'success', 'data' => $updatedEvent]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function destroy($eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);
            $service->events->delete('primary', $eventId);
            
            $eventDb=Event::where(
             array(
                'event_google_id' => $eventId,
            ))->first();
            
            $event = Event::findOrFail($eventDb->id);
            $event->delete();
            return response()->json(['status' => 'success', 'data' => $eventId]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }
    
}
