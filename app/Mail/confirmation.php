<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class confirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $url= '' ;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->url = $link ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = $this->url;
        return $this->from('ghommidj.w@gmail.com')
                ->subject('confirmation')
                ->view('confirmationmsg',compact('link'));
    }
}
