<?php

namespace App\Mail;

use App\Models\Site_has_incidente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IncidenciaGlobalActualizacion extends Mailable
{
    use Queueable, SerializesModels;
    public $incidente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Site_has_incidente $incidente)
    {
        $this->incidente = $incidente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.IncidenteGlobalActualizacion');
    }
}
