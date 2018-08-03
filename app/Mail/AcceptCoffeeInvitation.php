<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AcceptCoffeeInvitation extends Mailable
{
    public $user;
    public $user_receive;
    public function __construct($user, $user_receive)
    {
        $this->user = $user;
        $this->user_receive = $user_receive;
    }

    public function build()
    {
        return $this->view('mails.accept_coffee_invitation')->subject('Invitación de Coffee');
    }
}
