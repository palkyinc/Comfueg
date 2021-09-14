<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Issue;

class TicketActualizado extends Mailable
{
    use Queueable, SerializesModels;
    public $issue;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Issue $ticket)
    {
        $this->issue = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ticketActualizado');
    }
}
