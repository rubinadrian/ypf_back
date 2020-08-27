<?php

namespace App\Http\Controllers;

use App\Facturado;
use App\Cierre;
use App\Articulo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\Fechas;

class FacturadoController extends Controller
{
    use Fechas; // trait propio.

    public function getFacturados($cierre_id)
    {
        $cierre = Cierre::find($cierre_id);

        if(!$cierre) return ['ok'=> false, 'msg' => 'no existe un cierre con ese id'];

        $data =  $this->toPeriodFechasHoras($cierre->period);

        $data = array_merge($data, ['usuario' => strtoupper($cierre->usuario)]);

        return Facturado::getFacturados($data);
    }

    public function getFacturadosTest($cierre_id)
    {
        $cierre = Cierre::find($cierre_id);

        if(!$cierre) return ['ok'=> false, 'msg' => 'no existe un cierre con ese id'];

        $data =  $this->toPeriodFechasHoras($cierre->period);

        $data = array_merge($data, ['usuario' => strtoupper($cierre->usuario)]);

        $facturados = Facturado::getFacturados($data);

        $articulos_relacionados = Articulo::getArtRelacionMultiple();

        /*
        Articulos relacionados son promociones con precio 0.
        Ej: 4-/PYER360 se relacion con 4-360.
        Todo lo que se venda con 4-/PYER360 mueve el stock del 4-360.
        En la realidad mueve stock de un articulo que se vende como otro,
        entonces en la planilla de control tengo que tener en cuenta esto.
        Lo que estoy haciendo aca, es sumarle la cantidad facturada del relacionado al original.
        Pero si el original no vendio, simplemente cambio el codigo y la denominacion.
        */
        foreach($articulos_relacionados as $artrel) {
            foreach($facturados as $key => $artfac) {
                if($artrel->articulomadre == $artfac->articulo) {
                    // Suponemos que la relacion es uno a uno.
                    $fueFacturado = $this->addFacturacion($facturados, $artrel, $artfac);
                    if($fueFacturado) {
                        $facturados->forget($key); // elimino registro del relacionado.
                    }
                }
            }
        }

        dd(['facturados' => $facturados, 'relacion' => $articulos_relacionados]);
    }

    private function addFacturacion($facturados, $artrel, $artrelfac)
    {
        $fueFacturado = false;
        foreach($facturados as $art) {
            if($art->articulo == $artrel->articulo) {
                $art->cantidad += $artrelfac->cantidad;
                $fueFacturado = true;
                break;
            }
        }

        if(!$fueFacturado) {
            //la cantidad queda como esta
            $artrelfac->articulo = $artrel->articulo;
            $artrelfac->denominacion = $artrel->denominacion;
        }

        return $fueFacturado;
    }

    public function getComprobantes($cierre_id)
    {
        $cierre = Cierre::find($cierre_id);

        if(!$cierre) return ['ok'=> false, 'msg' => 'no existe un cierre con ese id'];

        $data =  $this->toPeriodFechasHoras($cierre->period);

        $data = array_merge($data, ['usuario' => strtoupper($cierre->usuario),
                                    'cancelacion_desde' => 2,
                                    'cancelacion_hasta' => 3]);

        return Facturado::getComprobantesCaja($data);
    }

    // Retorna los comprobantes que estan en modificaciones.
    public function getModificaciones($cierre_id)
    {
        $cierre = Cierre::find($cierre_id);

        if(!$cierre) return ['ok'=> false, 'msg' => 'no existe un cierre con ese id'];

        $data =  $this->toPeriodFechasHoras($cierre->period);

        $data = array_merge($data, ['usuario' => strtoupper($cierre->usuario),
                                    'cancelacion_desde' => 1,
                                    'cancelacion_hasta' => 2]);

        return Facturado::getComprobantesCaja($data);
    }


    public function getTarjetas() {
        return Facturado::getTarjetas();
    }

    public function getCajasPorFecha($cierre_id) {
        $cierre = Cierre::find($cierre_id);

        if(!$cierre) return ['ok'=> false, 'msg' => 'no existe un cierre con ese id'];

        $data =  $this->toPeriodFechasHoras($cierre->period);

        return Facturado::getCajasPorFecha($data);
    }

    public function getFacturadosYER($cierre_id) {
        $articulos_yer = ['4-/PYER360', '4-/PYER112', '4-/PYER448'];

        $cierre = Cierre::find($cierre_id);

        if(!$cierre) return ['ok'=> false, 'msg' => 'no existe un cierre con ese id'];

        $data =  $this->toPeriodFechasHoras($cierre->period);

        $data = array_merge($data, ['usuario' => strtoupper($cierre->usuario)]);

        return Facturado::getFacturadosYER($data, $articulos_yer);
    }

}
