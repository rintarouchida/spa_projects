<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //todo: トークンをメールに含める
        return $this->view('email.reset_password')
        ->to($this->email)
        ->from('mokumoku_map@gmail.com', 'もくもくMAP')
        ->subject('パスワード再設定をお願いします。');
    }
}
