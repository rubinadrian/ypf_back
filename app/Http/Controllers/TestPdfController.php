<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class TestPdfController extends Controller
{
    public function index() {
        $pdf = PDF::loadView('mail.testMail');
        $pdf->save('cierre_ypf.pdf');

        return $pdf->stream();
    }
}
