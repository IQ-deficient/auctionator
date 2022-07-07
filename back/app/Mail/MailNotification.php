<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $mail_text;

    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct(String $mail_text, String $title)
    {
        $this->title = $title;
        $this->mail_text = $mail_text;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build()
    {

        return $this->view('mail.notification')
            ->subject($this->title)
            ->with([
                'message' => $this->mail_text
            ]);

    }
}
