<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $tipo;
    public $mascotaNombre;

    public function __construct($mensaje, $tipo, $mascotaNombre = null)
    {
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->mascotaNombre = $mascotaNombre;
    }

    public function build()
    {
        $subject = match($this->tipo) {
            'LISTO_RECOGER' => '🐾 ¡Tu mascota está lista para recoger!',
            'RECORDATORIO_24H' => '⏰ Recordatorio: Tu cita es mañana',
            'RECORDATORIO_2H' => '⏰ Recordatorio: Tu cita es en 2 horas',
            'COMPRA_PRODUCTOS' => '✅ Confirmación de compra - Pet Spa',
            default => 'Notificación Pet Spa'
        };

        return $this->subject($subject)
                    ->view('emails.notificacion');
    }
}