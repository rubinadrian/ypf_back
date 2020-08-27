<?php

namespace App\Http\Controllers;

use App\Playero;
use Illuminate\Http\Request;

class PlayeroController extends Controller
{
	public function index() {
		return Playero::all();
	}

	public function usuariosCaja() {
		return [
			'manana',
			'tarde',
			'noche',
			// 'gmenghi',
			// 'rbarrera',
	         //'lbrizuela',
	  //       'jderoberti',
	  //       'llisandron',
	  //       'mmaccari',
	        // 'enunez',
	       // 'galarcon'
	    ];
	}
}
