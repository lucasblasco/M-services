<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SummitQuery extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $name;
    public $mail;
    public $phone;
    public $query;

    public function __construct($event, $name, $mail, $phone, $query)
    {
        $this->event = $event;
        $this->name  = $name;
        $this->mail  = $mail;
        $this->phone = $phone;
        $this->query = $query;
    }

    public function build()
    {
        return $this->view('mails.summit_query')->subject('Consulta por M_Summit');;
    }
}