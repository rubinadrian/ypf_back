<?php

namespace App\Http\Controllers;

use App\Promo;
use App\Cierre;
use Illuminate\Http\Request;

class PromoController extends Controller
{

    public function index($arqueo_id)
    {
        return Promo::where('arqueo_id', $arqueo_id)->get();
    }

    public function store(Request $request)
    {
        $promo =  Promo::find($request->id);
        if(!$promo) {
            $promo = new Promo();
        }

        $promo->arqueo_id = $request->arqueo_id;
        $promo->ticket = $request->ticket;
        $promo->importe = $request->importe;

        $promo->save();

        return ['ok' => true];
    }

    public function delete($id) {
        $promo =  Promo::find($id);
        $promo->delete();

        return ['ok' => true];
    }


}
