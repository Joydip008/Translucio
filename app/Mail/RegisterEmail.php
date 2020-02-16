<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterEmail extends Mailable
{
    use Queueable, SerializesModels;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */

    use Queueable, SerializesModels;

    public $data;


    public function __construct($data)
    {
        //
        $this->data = $data;
    } 

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'translucio@admin.com';
        $subject = 'Transluc.io - Confirm your email !';
        $name = $this->data['name'];
        
        return $this->view('user.emails.registerEmailSend')
                    ->from($address, 'TRANSLUCIO')
                    ->cc($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with([ 'message' => $this->data['message'] ]);
    }
}
