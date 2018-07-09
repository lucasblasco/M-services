<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $code;
    
    public function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
    }
    
    public function build()
    {
        return $this->view('mails.email_verify');
    }
}
