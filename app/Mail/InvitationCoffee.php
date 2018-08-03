<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitationCoffee extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $guest;
    public $event;

    public function __construct($user, $guest, $event)
    {
        $this->user = $user;
        $this->guest = $guest;
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('mails.invitation_coffee')->subject('Invitación de Coffee');
    }
}
