<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignUpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $messageContent;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$messageContent)
    {
        $this->subject = $subject;
        $this->messageContent = $messageContent;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->subject;
        return $this->view('admin.emails.signup');
    }
}
