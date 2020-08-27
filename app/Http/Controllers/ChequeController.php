<?php

namespace App\Http\Controllers;

use App\Cheque;
use App\Cierre;
use Illuminate\Http\Request;

class ChequeController extends Controller
{

    public function index($arqueo_id)
    {
        return Cheque::where('arqueo_id', $arqueo_id)->get();
    }

    public function store(Request $request)
    {
        $cheque =  Cheque::find($request->id);
        if(!$cheque) {
            $cheque = new Cheque();
        }

        $cheque->arqueo_id = $request->arqueo_id;
        $cheque->portador = $request->portador;
        $cheque->numero = $request->numero;
        $cheque->importe = $request->importe;
        $cheque->banco = $request->banco;
        $cheque->telefono = $request->telefono;
        $cheque->fecha = $request->fecha;

        $cheque->save();

        return ['ok' => true];
    }

    public function delete($id) {
        $cheque =  Cheque::find($id);
        $cheque->delete();

        return ['ok' => true];
    }

 
}
