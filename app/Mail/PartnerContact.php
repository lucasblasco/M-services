<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PartnerContact extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $email;
    public $user;
    public $event;

    public function __construct($user, $name, $phone, $email, $event)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->user = $user;
        $this->event = $event;
    }
    
    public function build()
    {
        return $this->view('mails.partner_contact')->subject('Contacto de Partner');
    }
}
