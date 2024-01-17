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
     * @return void
     */
    public function __construct(string $email, string $token, string $name)
    {
        $this->email = $email;
        $this->token = $token;
        $this->name = $name;
    }

    /**
     * @return $this
     */
    public function build()
    {
        return $this->view('email.reset_password')
        ->with([
            'url' => config('app.front_url') . '/auth/edit_password/'.$this->token.'?email='.$this->email,
            'name'  => $this->name,
        ])
        ->to($this->email)
        ->from('mokumoku_map@gmail.com', 'もくもくMAP')
        ->subject('パスワード再設定をお願いします。');
    }
}
