<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShareFriend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        $subject = 'Use Translucio And Translate What You Want';
        $name = 'Hello Friend';//$this->data['name'];
        
        return $this->view('user.emails.share_friend_by_mail')
                    ->from($address, 'TRANSLUCIO')
                    ->cc($address, $name)
                    ->bcc($address, $name)
                    ->replyTo($address, $name)
                    ->subject($subject)
                    ->with([ 'message' => $this->data['message'] ])
                    ->with([ 'code' => $this->data['Code'] ]);


    }
}
