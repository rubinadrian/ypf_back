<?php

namespace App\Http\Controllers;

use App\Arqueo;
use App\Cierre;
use App\Cheque;
use App\Promo;
use Illuminate\Http\Request;

class ArqueoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cierre_id)
    {
        $arqueo = Arqueo::where('cierre_id', $cierre_id)->first();
        $importe_cheques = $this->getImporteCheques($arqueo->id);
        $importe_promos = $this->getImportePromos($arqueo->id);
        $data = $arqueo->toArray();
        $data['cheques'] = $importe_cheques;
        $data['promos'] = $importe_promos;
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cierre = Cierre::find($request->cierre_id);
        if($cierre && $cierre->status == 0) {
            $data = $request->all();
            $data['cierre_id'] = $cierre->id;
            $arqueo = Arqueo::where('cierre_id', $cierre->id)->first();
            $data['cheques'] = $this->getImporteCheques($arqueo->id);
            $data['promos'] = $this->getImportePromos($arqueo->id);

            $num1 = 1;

            $arqueo->v5 = $data['5'];
            $arqueo->v10 = $data['10'];
            $arqueo->v20 = $data['20'];
            $arqueo->v50 = $data['50'];
            $arqueo->v100 = $data['100'];
            $arqueo->v200 = $data['200'];
            $arqueo->v500 = $data['500'];
            $arqueo->v1000 = $data['1000'];
            $arqueo->cheques = $data['cheques'];
            $arqueo->promos = $data['promos'];
            $arqueo->otros_texto = $data['otros_texto'];
            $arqueo->otros_valor = $data['otros_valor'];
            $arqueo->tarjetas = $data['tarjetas'];
            $arqueo->tickets = $data['tickets'];

            $arqueo->save();


            $cierre->status = 1;
            $cierre->save();
            return ['ok'=>'true', 'cierre'=>$cierre];
        }
        return ['ok'=>false];

    }

    // public function saveImporteCheques(Request $request) {
    //     $cierre = Cierre::find($request->cierre_id);
    //     if($cierre && $cierre->status == 0) {
    //         $arqueo = Arqueo::where('cierre_id', $cierre->id)->first();
    //         if(!$arqueo) {
    //             $arqueo = new Arqueo();
    //         }
    //         $arqueo->cierre_id = $cierre->id;
    //         $arqueo->cheques = $request->cheques;
    //         $arqueo->save();
    //         return ['ok'=>'true', 'arqueo' => $arqueo ];
    //     }

    //     return [ 'ok' => false ];
    // }

    public function getImporteCheques($arqueo_id) {
        $total = 0;
        $cheques = Cheque::where('arqueo_id', $arqueo_id)->get();
        foreach($cheques as $cheque) {
            $total += $cheque->importe;
        }

        return $total;
    }

    public function getImportePromos($arqueo_id) {
        $total = 0;
        $promos = Promo::where('arqueo_id', $arqueo_id)->get();
        foreach($promos as $promo) {
            $total += $promo->importe;
        }

        return $total;
    }


}
