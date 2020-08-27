<?php

namespace App\Traits;

use Carbon\Carbon;

trait Fechas {

	/** @fecha type Carbon */
    protected function toFechaBit($fecha) {
        $fechaCero = Carbon::parse('1800-12-28');

        return $fecha->diffInDays($fechaCero);
    }
 
    /** @fecha type Carbon */
    protected function toHoraBit($fecha) {

        $resp =  $fecha->hour *60 *60 *100;
        $resp += $fecha->minute   *60 *100;
        $resp += $fecha->second       *100;

        // truncamos en 7 digitos. ej: 0000001
        return str_pad($resp, 7, "0", STR_PAD_LEFT);
    }

    protected function toPeriodFechasHoras($period) {
       
        $fhd = substr($period, 0, 14);  //fhd fecha hora desde
        $fhh = substr($period, 14, 14); //fhh fecha hora hasta

        $fhd = Carbon::parse($fhd);
        $fhh = Carbon::parse($fhh);

        $fhd->subHour(0); // Resto horas previas a la caja.
        $fhh->addHour(12); // Sumo hora posteriores a la caja.


        return [
            'fhd'   =>  $this->toFechaBit($fhd) . $this->toHoraBit($fhd),
            'fhh'   =>  $this->toFechaBit($fhh) . $this->toHoraBit($fhh),
            'fd'    =>  $this->toFechaBit($fhd),
            'fh'    =>  $this->toFechaBit($fhh),
            'fhdc'  =>  $fhd,
            'fhhc'  =>  $fhh
        ];

    }

}