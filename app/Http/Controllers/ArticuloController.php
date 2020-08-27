<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Aforador;
use Illuminate\Http\Request;
use DB;
use PDF;

class ArticuloController extends Controller
{

    // Retorna los articulos del control
    public function index()
    {
        return Articulo::orderBy('orden')->get();
    }

    public function getArtRelacionMultiple() {
        return Articulo::getArtRelacionMultiple();
    }

    // Retornamos los articulos combustibles del sistema bit
    public function getArtCombBit() {

        $codigos = ['4-/G','4-/GY','4-/D','4-/EA','4-/NS','4-/NF','4-/P1000'];

        return DB::connection('oracle')
            ->table('FAMSTOCK')
            ->select('articulo', 'denominacion')
            ->whereIn('articulo', $codigos)
            ->get();
    }


    public function getPdf() {
        $aforadores = Aforador::orderBy('orden')->get();
        $data = Articulo::orderBy('orden')->get();
        $pdf = PDF::loadView('PlanillaControlArticulos', ['data' => $data, 'aforadores' => $aforadores]);
        return $pdf->stream('customers.pdf');
    }

    public function saveOrden(Request $request) {
        $data = $request->toArray()['articulos'];
        foreach ($request->get('articulos') as $articulo) {
            $a = Articulo::find($articulo['id']);
            $a->orden = $articulo['orden'];
            $a->update();
        }
        return ['ok' => true];
    }

    public function delete($id) {
        $articulo =  Articulo::find($id);
        $articulo->delete();

        return ['ok' => true];
    }

    public function store(Request $request) {
        if($request->has('id')) {
            $articulo = Articulo::find($request->id);
        } else {
            $orden = Articulo::max('orden');
            $articulo = new Articulo();
            $articulo->codigo = $request->codigo;
            $articulo->orden = $orden + 1;
        }
        $articulo->denominacion = $request->denominacion;
        $articulo->inicial = $request->inicial;
        $articulo->save();

        return ['ok' => true, 'articulo'=>$articulo];
    }

}
