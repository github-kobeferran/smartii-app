<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeApplicant extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $dept;
    public $prog;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $dept, $prog)
    {
        $this->name = $name;
        $this->dept = $dept;
        $this->prog = $prog;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcomes.welcome-applicant');
    }
}
