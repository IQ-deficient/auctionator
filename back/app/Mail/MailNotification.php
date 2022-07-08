<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $mail_text;
    // Do not use variable $message !

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
        // Less secure app access setting on Auction House Google Account is turned ON
        // On the 30th of May 2022, this will be obsolete

        return $this->view('mail.notification')
            ->subject($this->title)
            ->with([
                'message' => $this->mail_text
            ]);

    }
}
