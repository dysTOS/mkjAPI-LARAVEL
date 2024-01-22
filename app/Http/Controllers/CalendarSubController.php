<?php

namespace App\Http\Controllers;

use App\Models\Termin;
use App\Models\Mitglieder;
use App\Models\User;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Description\Location;
use Jsvrcek\ICS\Utility\Formatter;

class CalendarSubController extends Controller
{
    public static function getSubscription($id = null)
    {
        $user = User::where('id', $id)->first();
        $actualYear = date('Y') . "-01-01";

        if ($user) {
            $gruppen = Mitglieder::where('user_id', $user->id)->first()->gruppen()->get();
            $events = Termin::where('vonDatum', '>=', $actualYear)
                ->when(
                    $gruppen, function ($query, $gruppen) {
                    $query->where(function ($query) use ($gruppen) {
                        foreach ($gruppen as $gruppe) {
                            if ($gruppe) {
                                $query->orWhere('gruppe_id', '=', $gruppe['id']);
                            }
                        }
                        return $query->orWhere('gruppe_id', '=', null);
                    });
                }
                )
                ->get();
        } else {
            $events = Termin::where('oeffentlich', true)->where('vonDatum', '>=', $actualYear)->get();
        }

        $calendar = new Calendar();
        $calendar->setProdId(env('APP_NAME', 'APP Kalender'));
        $calendar->setName(env('APP_NAME', 'APP Kalender'));
        $calendar->setColor('#006600');
        $timezone = config('app.timezone');
        $timeZone = new \DateTimeZone($timezone);
        $calendar->setTimezone($timeZone);
        $calendar->setCustomHeaders([
            'X-WR-TIMEZONE' => $timezone, // Support e.g. Google Calendar -> https://blog.jonudell.net/2011/10/17/x-wr-timezone-considered-harmful/
            'X-WR-CALNAME' => 'mkjAPP', // https://en.wikipedia.org/wiki/ICalendar
            'X-PUBLISHED-TTL' => 'PT60M' // update calendar every 60 minutes
        ]);

        foreach ($events as $event) {
            $calendarEvent = new CalendarEvent();

            $vonDateTime = $event->vonDatum;
            $bisDateTime = $event->bisDatum;
            if ($event->treffzeit) {
                $vonDateTime = $vonDateTime . " " . $event->treffzeit;
            }
            if ($event->bisZeit) {
                $bisDateTime = $bisDateTime . " " . $event->bisZeit;
            }
            if (!$event->vonZeit) {
                $vonDateTime = $vonDateTime . " 00:00";
                $bisDateTime = $bisDateTime . " 24:00";
                $calendarEvent->setAllDay(true)
                    ->setCustomProperties([
                        'X-MICROSOFT-CDO-ALLDAYEVENT' => true
                    ]);
            }

            $calendarEvent->setStart(new \DateTime($vonDateTime))
                ->setEnd(new \DateTime($bisDateTime))
                ->setDescription($event->infoMusiker)
                ->setUid($event->id);

            if ($event->status == 'ersatztermin') {
                $calendarEvent->setSummary($event->name . ' - Ersatztermin');
            } else {
                $calendarEvent->setSummary($event->name);
            }

            $location = new Location();
            $location->setName($event->ort);
            $calendarEvent->addLocation($location);

            $calendar->addEvent($calendarEvent);
        }

        $calendarExport = new CalendarExport(new CalendarStream, new Formatter());
        $calendarExport->addCalendar($calendar);

        return response()->attachment($calendarExport->getStream(), 'app_calendar', 'text/calendar', 'ics');
    }
}
