<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectApplicant extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $dept;
    public $prog;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $dept, $prog, $reason)
    {
        $this->name = $name;
        $this->dept = $dept;
        $this->prog = $prog;
        $this->reason = $reason;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()->addTextHeader(
                'Application Rejected', 'Application Rejected'
            );
        });

        $this->markdown('emails.applicant.reject');

        return $this;
    }
}
