<?php


namespace App\Http\Controllers;

use Mail;
use App\Mail\TestMail;

class TestMailController extends Controller
{

    public function index() {
        Mail::to('arubin@coopunion.com.ar')->send(new TestMail());
        return response()->json(['true']);
    }
}
