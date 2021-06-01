<?php

namespace App\Mail;

use App\Models\Site_has_incidente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeudaTecnicaResumen extends Mailable
{
    use Queueable, SerializesModels;
    public $deudas;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deudas)
    {
        $this->deudas = $deudas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.deudaGlobalResumen');
    }
}
