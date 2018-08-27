<?php

namespace App\Mail;

use App\Entities\User;
use Carbon\Carbon;
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
        $payLoad = [
            'email' => $this->user->getAttribute('email'),
            'is_active' => $this->user->getAttribute('is_active'),
            'time' => Carbon::now()
        ];
        $userToken = encrypt($payLoad);
        return $this->from(config('mail.from.address'))
            ->subject('User Registeration Notification')
            ->view('mail.user.register')
            ->with('userToken',$userToken);
    }
}
