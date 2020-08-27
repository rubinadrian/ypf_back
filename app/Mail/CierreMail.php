<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Articulo;
use PDF;

class CierreMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = PDF::loadView('mail.cierreAttachPDF', $this->data );
        $pdf->save('cierre_ypf.pdf');

        return $this->from('arubin@coopunion.com.ar')
                    ->attach('cierre_ypf.pdf')
                    ->view('mail.cierreBodyMail', $this->data);
    }
}
