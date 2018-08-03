<?php

namespace App\Mail;

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InscriptionsEventList extends Mailable
{
    use Queueable, SerializesModels;

    public $users;
    public $event;

    public function __construct($event, $users)
    {
        $this->users = $users;
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('mails.inscriptions_event_list')->subject('Inscriptos al evento');
    }
}
