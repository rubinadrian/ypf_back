<?php

namespace App\Http\Controllers;

use App\Incidencia;
use Illuminate\Http\Request;

class IncidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cierre_id)
    {
        return Incidencia::where('cierre_id', $cierre_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $incidencia = Incidencia::where('cierre_id', $input['cierre_id'])
                        ->where('articulo', $input['articulo'])
                        ->first();
        if($incidencia) {
            $incidencia->cantidad += $input['cantidad'];
            $incidencia->save();
        } else {
            $incidencia = Incidencia::create($input);
        }
        return ['ok'=>true];
    }

    public function remove(Request $request) {
        $input = $request->all();
        $incidencia = Incidencia::where('cierre_id', $input['cierre_id'])
            ->where('articulo', $input['articulo'])
            ->first();
        $incidencia->delete();
        return ['ok'=>true];    
    }

}
