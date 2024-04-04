<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReporteError extends Mailable
{
    use Queueable, SerializesModels;
    public $errores;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($errores)
    {
        $this->errores = $errores;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ReporteError');
    }
}
