<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CierreCio;
use App\Ypfenruta;

class CierreCioController extends Controller
{

  public function getCierreCio($period) {
    $c = CierreCio::where('period', $period)->first();
    if($c) {
      return [ 'period' => $c->period, 
                '4-/GY' => [  'importe' => $c['4-/GY_imp'],
                              'volumen' => $c['4-/GY_vol']  ],
                '4-/NF' => [  'importe' => $c['4-/NF_imp'],
                              'volumen' => $c['4-/NF_vol']  ],
                '4-/NS' => [  'importe' => $c['4-/NS_imp'],
                              'volumen' => $c['4-/NS_vol']  ],
                '4-/D'  => [  'importe' => $c['4-/D_imp'],
                              'volumen' => $c['4-/D_vol']  ],
                'ypfenruta' =>     [  'importe' => $c->ypfenruta_imp,
                                      'volumen' => $c->ypfenruta_vol  ],
                'vtasincontrol' => [  'importe' => $c->vtasincontrol_imp,
                                      'volumen' => $c->vtasincontrol_vol  ],
                'pbaconretorno' => [  'importe' => $c->pbaconretorno_imp,
                                      'volumen' => $c->pbaconretorno_vol  ],
                'pbasinretorno' => [  'importe' => $c->pbasinretorno_imp,
                                      'volumen' => $c->pbasinretorno_vol  ] ];
    }
  }

  public function getYERCio($period) {
    return Ypfenruta::where('period', $period)->first();
  }

  public function saveCierreCio(Request $request) {

    if(!isset($request->period)) return ['ok' => false, 'msg' => '' ];

		if(CierreCio::where('period', $request->period)->first()) {
        return [
            'ok'=>'false',
            'msg'=>'Los datos del cio ya fueron cargados'
        ];
    }

    $cio = new CierreCio;
    $cio->period = $request->period;
    $cio['4-/GY_vol'] = $request['4-/GY']['volumen'];
    $cio['4-/NF_vol'] = $request['4-/NF']['volumen'];
    $cio['4-/NS_vol'] = $request['4-/NS']['volumen'];
    $cio['4-/D_vol']  = $request['4-/D']['volumen'];
    $cio->ypfenruta_vol = $request->ypfenruta['volumen'];
    $cio->vtasincontrol_vol = $request->vtasincontrol['volumen'];
    $cio->pbaconretorno_vol = $request->pbaconretorno['volumen'];
    $cio->pbasinretorno_vol = $request->pbasinretorno['volumen'];
    $cio['4-/GY_imp'] = $request['4-/GY']['importe'];
    $cio['4-/NF_imp'] = $request['4-/NF']['importe'];
    $cio['4-/NS_imp'] = $request['4-/NS']['importe'];
    $cio['4-/D_imp']  = $request['4-/D']['importe'];
    $cio->ypfenruta_imp = $request->ypfenruta['importe'];
    $cio->vtasincontrol_imp = $request->vtasincontrol['importe'];
    $cio->pbaconretorno_imp = $request->pbaconretorno['importe'];
    $cio->pbasinretorno_imp = $request->pbasinretorno['importe'];
    $cio->impTotal = $request->impTotal;
    $cio->save();

    return ['ok' => true];
	}


	public function saveYERCio(Request $request) {
    if(!isset($request->period)) return ['ok' => false, 'msg' => '' ];

    if(Ypfenruta::where('period', $request->period)->first()) {
        return [
            'ok'=>'false',
            'msg'=>'Los datos del cio ya fueron cargados'
        ];
    }

    $yer = new Ypfenruta;
    $yer->period = $request->period;
    $yer['4-/GY'] = $request['4-/GY'];
    $yer['4-/NF'] = $request['4-/NF'];
    $yer['4-/NS'] = $request['4-/NS'];
    $yer['4-/D']  = $request['4-/D'];
    $yer->save();

    return ['ok' => true];
	}

}
