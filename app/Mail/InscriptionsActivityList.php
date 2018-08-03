<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InscriptionsActivityList extends Mailable
{
    use Queueable, SerializesModels;

    public $users;
    public $activity;
    public $format;

    public function __construct($activity, $format, $users)
    {
        $this->users = $users;
        $this->activity = $activity;
        $this->format = $format;
    }

    public function build()
    {
        return $this->view('mails.inscriptions_activity_list')->subject('Inscriptos al ' . $this->format);
    }
}
