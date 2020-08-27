<?php

namespace App\Http\Controllers;

use App\Cierre;
use App\Facturado;
use App\Incidencia;
use App\Aforador;
use App\Arqueo;
use App\Cio;
use App\ControlAforadores;
use App\ControlArticulos;
use App\CierreCio;
use App\Ypfenruta;
use App\Cheque;
use App\Promo;
use Illuminate\Http\Request;
use App\Mail\CierreMail;
use Mail;
use App\Traits\Fechas;
use Log;

class CierreController extends Controller
{
    use Fechas;

    public function index()
    {
        return Cierre::orderBy('period', 'Desc')->limit(100)->get();
    }

    public function show($period)
    {
        $cierre = Cierre::where('period', $period)->first();
        return ['ok'=>'true', 'cierre' => $cierre];
    }

    public function close($period)
    {
        // Me queda controlar que este todo facturado desde el servidor.
        $cierre = Cierre::where('period', $period)->first();
        if(!$cierre) return ['ok' => false, 'msg' => 'No existe el cierre'];
        $cierre_id = $cierre->id;
        $data =  $this->toPeriodFechasHoras($cierre->period);
        $data = array_merge($data, ['usuario' => strtoupper($cierre->usuario),
                                    'cancelacion_desde' => 2,
                                    'cancelacion_hasta' => 3]);

        $comprobantes = Facturado::getComprobantesCaja($data);
        $facturado =  Facturado::getFacturados($data);
        $arqueo = Arqueo::select('v1000','v500','v200','v100','v50','v20','v10','v5','v2','v1',
                        'cheques','otros_texto','otros_valor','tarjetas','tickets')
                        ->where('cierre_id', $cierre->id)->first();
        $aforadores = ControlAforadores::where('cierre_id', $cierre_id)
                ->select('aforador_id',
                    'articulo',
                    'nombre',
                    'orden',
                    'cierre_id',
                    'control_aforadores.inicial',
                    'final')
                ->join('aforadores', 'aforadores.id', '=', 'aforador_id')
                ->get() ?? [];
        $control = ControlArticulos::where('cierre_id', $cierre_id)
                        ->select(   'control_articulos.articulo_id',
                                    'control_articulos.cierre_id',
                                    'articulos.codigo',
                                    'articulos.denominacion',
                                    'control_articulos.final',
                                    'control_articulos.yer',
                                    'control_articulos.id',
                                    'control_articulos.inicial',
                                    'articulos.orden',
                                    'control_articulos.reposicion')
                        ->join('articulos', 'articulos.id', '=', 'articulo_id')
                        ->get() ?? [];
        $incidencias = Incidencia::where('cierre_id', $cierre->id)->get() ?? [];
        $yer = Ypfenruta::where('period', $period)->first();
        $cierre_cio = CierreCio::where('period', $period)->first();

        $contado = 0;
        $ctacte = 0;
        $tarjeta = 0;
        foreach($comprobantes as $c) {
            if($this->isContado($c)) {
                $contado += ($c->totalcom + ($c->totalcom  - $c->imptarj ) * ($c->tarjcred ==0?0:1)) * ($c->debcre ==2?-1:1);
            } else if($this->isCtacte($c)) {
                $ctacte += $c->totalcom  * ($c->debcre==2?-1:1) + ($c->totalcom  - $c->imptarj ) * ($c->tarjcred ==0?0:1);
            } else if($this->isTarjeta($c)) {
                $tarjeta += $c->imptarj  * ($c->debcre ==2?-1:1);
            }
        }

        $arqueo_contado = 0;
        // Log::info($arqueo->attributesToArray());
        foreach($arqueo->attributesToArray() as $k=>$v){
            if(strtoupper($k) == 'TARJETAS') continue;

            // los campos contado son v1000,v500, etc.
            // quitamos con substr la v
            if(is_numeric(substr($k,1))) {
                $arqueo_contado += (substr($k,1) * $v);
            } else {
                if(is_numeric($v)) {
                    $arqueo_contado += $v;
                }
            }
        }
        $arqueo_tarjeta =   $arqueo->tarjetas;

        $all_data = [
            'cierre'         => $cierre,
            'fechas'         => $data,
            'comprobantes'   => $comprobantes,
            'facturado'      => $facturado,
            'arqueo'         => $arqueo,
            'aforadores'     => $aforadores,
            'control'        => $control,
            'incidencias'    => $incidencias,
            'yer'            => $yer,
            'cierre_cio'     => $cierre_cio,
            'contado'        => $contado,
            'ctacte'         => $ctacte,
            'tarjeta'        => $tarjeta,
            'arqueo_tarjeta' => $arqueo_tarjeta,
            'arqueo_contado' => $arqueo_contado
        ];

        Mail::to('pmartorell@coopunion.com.ar')
             ->cc(['fmargherit@coopunion.com.ar','vbaquel@coopunion.com.ar','enunez@coopunion.com.ar','ngigli@coopunion.com.ar'])
            ->send(new CierreMail(['d' => $all_data]));
        return $this->changeStatus($cierre_id, '1000');
    }

    // Cuando se controla modificaciones y no hay ningun comprobante se pasa a status 4
    public function modificaciones(Request $request)
    {
        return $this->changeStatus($request->cierre_id, '4');
    }

    public function changeStatus($cierre_id, $status)
    {
        $cierre = Cierre::find($cierre_id);
        if($cierre) {
            $cierre->status = $status;
            $cierre->save();
            return ['ok'=>'true', 'cierre' => $cierre];
        }

        return ['ok'=>'false', 'msg' => 'No existe el cierre solicitado'];
    }

    public function store(Request $request)
    {
        $cierre = Cierre::where('period', $request->period)->first();

        $es_nuevo = false;
        if(!$cierre) {
            $cierre = new Cierre;
            $cierre->status = 0;
            $es_nuevo = true;
        }

        if($cierre->status != 1000) {
            $cierre->playero1 = $request->playero1;
            $cierre->playero2 = $request->playero2;
            $cierre->playero3 = $request->playero3;
            $cierre->usuario = $request->turno;
            $cierre->period = $request->period;
            $cierre->save();
        }

        // Todo cierre tiene un arqueo si o si, porque la pantalla cheques lo exige
        $arqueo = Arqueo::where('cierre_id', $cierre->id)->first();
        if(!$arqueo) {
            $data = [];
            $data['cierre_id'] = $cierre->id;
            $arqueo = Arqueo::create($data);
        }

        // Si es un nuevo cierre, pasar ticket y cheques con arqueo_id = 0 al nuevo arqueo
        if($es_nuevo) {
            Cheque::where('arqueo_id', 0)->update(['arqueo_id' => $arqueo->id]);
             Promo::where('arqueo_id', 0)->update(['arqueo_id' => $arqueo->id]);
        }


        return ['ok'=>'true', 'cierre' => $cierre];
    }


    public function remove($period) {
        $cierre = Cierre::where('period', $period)->first();
        $cierre->delete();

        return ['ok'=>'true'];
    }

    public function setObs(Request $request) {
        $cierre = Cierre::where('period', $request->period)->first();

        if($cierre) {
            $cierre->observaciones = $request->observaciones;
            $cierre->save();

            return ['ok'=>'true'];
        }

        return ['ok'=>'false'];
    }

    private function isContado($c) {
        if($c->contadocc == 1
             && $c->tipomovim == 66
             && (   $c->tarjcred == 0 ||
                   ($c->tarjcred != 0 && ( abs($c->totalcom - $c->imptarj) > 1))
                )
             && $c->tipocomp != '802'
             && $c->tipocomp != '803')
            return true;
        return false;
    }

    private function isCtacte($c) {
        if($c->contadocc == 2
             && $c->tipomovim == 66
             && (   $c->tarjcred == 0 ||
                   ($c->tarjcred != 0 && ( abs($c->totalcom - $c->imptarj) > 1))
                )
             && $c->tipocomp != '802'
             && $c->tipocomp != '803')
            return true;
        return false;
    }

    public function isTarjeta($c) {
        if($c->tarjcred != 0)
            return true;
        return false;
    }

}
