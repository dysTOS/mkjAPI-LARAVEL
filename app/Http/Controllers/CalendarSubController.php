<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Description\Location;
use Jsvrcek\ICS\Utility\Formatter;

class CalendarSubController extends Controller
{
    public function getSubscription()
    {
        $calendar = new Calendar();
        $calendar->setProdId('mkjAPP');
        $calendar->setName('MK Jainzen Kalender');
        $calendar->setColor('#006600');
        $timezone = config('app.timezone');
        $timeZone = new \DateTimeZone($timezone);
        $calendar->setTimezone($timeZone);
        $calendar->setCustomHeaders([
            'X-WR-TIMEZONE' => $timezone, // Support e.g. Google Calendar -> https://blog.jonudell.net/2011/10/17/x-wr-timezone-considered-harmful/
            'X-WR-CALNAME' => 'mkjAPP', // https://en.wikipedia.org/wiki/ICalendar
            'X-PUBLISHED-TTL' => 'PT60M' // update calendar every 15 minutes
        ]);

        $actualYear = date('Y') . "-01-01";
        $events = Ausrueckung::where('vonDatum', '>=', $actualYear)->orderBy('vonDatum', 'asc')->get();

        foreach ($events as $event) {
            $calendarEvent = new CalendarEvent();

            $vonDateTime = $event->vonDatum;
            $bisDateTime = $event->bisDatum;
            if ($event->vonZeit) {
                $vonDateTime = $vonDateTime . " " . $event->vonZeit;
            }
            if ($event->bisZeit) {
                $bisDateTime = $bisDateTime . " " . $event->bisZeit;
            }
            if (!$vonDateTime) {
                $calendarEvent->setAllDay(true)
                    ->setCustomProperties([
                        'X-MICROSOFT-CDO-ALLDAYEVENT' => true
                    ]);
            }

            $calendarEvent->setStart(new \DateTime($vonDateTime))
                ->setEnd(new \DateTime($bisDateTime))
                ->setDescription($event->infoMusiker)
                ->setUid($event->id);

            if($event->status == 'ersatztermin'){
                $calendarEvent->setSummary($event->name . ' - Ersatztermin');
            }else{
                $calendarEvent->setSummary($event->name);
            }

            $location = new Location();
            $location->setName($event->ort);
            $calendarEvent->addLocation($location);

            $calendar->addEvent($calendarEvent);
        }

        $calendarExport = new CalendarExport(new CalendarStream, new Formatter());
        $calendarExport->addCalendar($calendar);

        return response()->attachment($calendarExport->getStream(), 'mkjcalendar', 'text/calendar', 'ics');
    }
}
