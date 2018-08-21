<?php

namespace App\Mail;

use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this->from(config('mail.from'))
            ->subject('User Registeration Notification')
            ->view('mail.user.register')
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()
                    ->addTextHeader('Custom-Header', 'HeaderValue');
            });
    }
}
