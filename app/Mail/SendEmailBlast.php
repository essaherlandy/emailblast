<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailBlast extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->data['attachment'] == ''){
            return $this->from(env("MAIL_FROM_ADDRESS"))
                        ->subject("EVENT GYM NEST")
                        ->view('mail.blast-email', [
                            'data' => $this->data
                        ]);
        }else{
            return $this->from(env("MAIL_FROM_ADDRESS"))
                        ->subject("EVENT GYM NEST")
                        ->attach(public_path($this->data['attachment']))
                        ->view('mail.blast-email', [
                            'data' => $this->data
                        ]);
        }
    }
}
