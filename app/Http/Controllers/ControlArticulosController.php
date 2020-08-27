<?php

namespace App\Http\Controllers;

use App\ControlArticulos;
use Illuminate\Http\Request;
use App\Cierre;
use App\Articulo;
use PDF;

class ControlArticulosController extends Controller
{

    public function index($cierre_id)
    {
        return ControlArticulos::where('cierre_id', $cierre_id)
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
                        ->get();
    }

    
    public function store(Request $request)
    {
        $cierre = Cierre::find($request->cierre_id);
        if($cierre && $cierre->status >= 1) {
            ControlArticulos::where('cierre_id', $cierre->id)->delete();
            $data = [];
            foreach($request->articulos as $articulo) {

                // // guardar solo lo que movio
                // if( $articulo['inicial'] + $articulo['reposicion'] == $articulo['final']) continue;

                $temp = [
                   'cierre_id' => $cierre->id,
                   'articulo_id' => $articulo['id'],
                   'inicial' => $articulo['inicial'],
                   'yer' => $articulo['yer'],
                   'reposicion' => $articulo['reposicion'],
                   'final' => $articulo['final']
                ];

                $a = Articulo::find($articulo['id']);
                $a->inicial = $articulo['final'];
                $a->save();

                array_push($data, $temp);
            }
            ControlArticulos::insert($data);
            if($cierre->status == 1) {
                $cierre->status = 2;
            }
            $cierre->save();
            return ['ok'=>'true', 'cierre'=>$cierre];
        }
        return ['ok'=>false];
    }
}