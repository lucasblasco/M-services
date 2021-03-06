<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeSummitActivity extends Mailable
{
    use Queueable, SerializesModels;

     public $name;
    public $event;

    public function __construct($name, $event)
    {
        $this->name = $name;
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('mails.summit_inscription')->subject('Inscripción a M_Summit');;
    }
}
