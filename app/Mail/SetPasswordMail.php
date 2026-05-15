<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $rol;

    public function __construct(User $user, $token, $rol = '')
    {
        $this->user = $user;
        $this->token = $token;
        $this->rol = $rol;
    }

    public function build()
    {
        return $this->subject('Bienvenido a Pet Spa - Establece tu contraseña')
                    ->view('emails.set-password');
    }
}