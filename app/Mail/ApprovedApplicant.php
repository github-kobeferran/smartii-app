<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedApplicant extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $dept;
    public $prog;
    public $studid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $studid, $dept, $prog)
    {
        $this->name = $name;
        $this->dept = $dept;
        $this->prog = $prog;
        $this->studid = $studid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.approved.applicant');
    }
}
