<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;

class LoginTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public User $user) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.login-token')->with('link', $this->buildLink());
    }

    protected function buildLink()
    {
        return route('login-token', [
            'email' => $this->user->email,
            'token' => Hash::make($this->user->login_token)
        ]);
    }
}
