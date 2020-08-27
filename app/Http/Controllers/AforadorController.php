<?php

namespace App\Http\Controllers;

use App\Aforador;
use Illuminate\Http\Request;
use DB;

class AforadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Aforador::orderBy('orden')->get();
    }

    public function store(Request $request) {
        if($request->has('id')) {
            $aforador = Aforador::find($request->id);
        } else {
            $orden = Aforador::max('orden');
            $aforador = new Aforador();
            $aforador->articulo = $request->articulo;
            $aforador->orden = $orden + 1;
        }
        
        $aforador->inicial = $request->inicial;
        $aforador->nombre = $request->nombre;
        $aforador->save();

        return ['ok' => true, 'aforador'=>$aforador];
    }

    public function delete($id) {
        $aforador =  Aforador::find($id);
        $aforador->delete();

        return ['ok' => true];
    }

    public function saveOrden(Request $request) {

        foreach($request->get('aforadores') as $aforador) {
            $a = Aforador::find($aforador['id']);
            $a->orden = $aforador['orden'];
            $a->update();
        }

        return ['ok' => true];
    }

  
}
