<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Faculty;

class RemindToArchive extends Mailable
{
    use Queueable, SerializesModels;

    public Faculty $faculty;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Faculty $faculty)
    {
        $this->faculty = $faculty;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.faculty.remind_to_archive');
    }
}
