<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeWorkshopActivity extends Mailable
{
    use Queueable, SerializesModels;

     public $name;
    public $event;
    public $actividad_name;

    public function __construct($name, $event, $actividad_name)
    {
        $this->name = $name;
        $this->event = $event;
        $this->actividad = $actividad_name;
    }

    public function build()
    {
        return $this->view('mails.workshop_inscription')->subject('Inscripción a M_Workshop');;
    }
}
