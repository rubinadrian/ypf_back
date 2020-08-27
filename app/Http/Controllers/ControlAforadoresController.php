<?php

namespace App\Http\Controllers;

use App\ControlAforadores;
use Illuminate\Http\Request;
use App\Cierre;
use App\Aforador;

class ControlAforadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cierre_id)
    {
        return ControlAforadores::where('cierre_id', $cierre_id)
                ->select('aforador_id',
                    'articulo',
                    'nombre',
                    'orden',
                    'cierre_id',
                    'control_aforadores.inicial',
                    'final')
                ->join('aforadores', 'aforadores.id', '=', 'aforador_id')
                ->get();
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
        if($cierre && $cierre->status >= 2) {
            ControlAforadores::where('cierre_id', $cierre->id)->delete();
            $data = [];
            foreach($request->aforadores as $aforador) {

                // // guarda solo lo que movio
                // if($aforador['inicial'] == $aforador['final']) continue;

                $temp = [
                   'cierre_id' => $cierre->id,
                   'aforador_id' => $aforador['id'],
                   'inicial' => $aforador['inicial'],
                   'final' => $aforador['final']
                ];

                $a = Aforador::find($aforador['id']);
                $a->inicial =  $aforador['final'];
                $a->save();

                array_push($data, $temp);
            }
            ControlAforadores::insert($data);
            if($cierre->status == 2) {
                $cierre->status = 3;
            }
            $cierre->save();
            return ['ok'=>'true', 'cierre'=>$cierre];
        }
        return ['ok'=>false];
    }


}
