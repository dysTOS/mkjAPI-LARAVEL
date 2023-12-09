<?php

namespace App\Listeners;

use App\classes\KassabuchungTyp;
use App\Events\KassabuchungUpdated;
use App\Models\Kassabuch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateKassabuch
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\KassabuchungUpdated $event
     * @return void
     */
    public function handle(KassabuchungUpdated $event)
    {
        $kassabuch = $event->kassabuch;
        $buchungen = $kassabuch->kassabuchungen()->get();

//        info('UpdateKassabuch_Name:'. $kassabuch->name);
//        info('UpdateKassabuch_Kassastand_old:  '. $kassabuch->kassastand);

        $saldo = $buchungen->sum(function ($item) {
            $typ = $item->typ;
            if ($typ == KassabuchungTyp::EINNAHME) {
                return $item['gesamtpreis'];
            } elseif ($typ === KassabuchungTyp::AUSGABE) {
                return $item['gesamtpreis'] * -1;
            }
            return 0;
        });

//        info('UpdateKassabuch_Kassastand_new:  '. $saldo);
        $kassabuch->update(['kassastand' => $saldo]);
    }
}
