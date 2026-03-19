<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    public $titulo;
    public $mensaje;
    public $tipo;
    public $url;

    public function __construct($titulo, $mensaje, $tipo = 'info', $url = null)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url ?? '#';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'titulo' => $this->titulo,
            'mensaje' => $this->mensaje,
            'tipo' => $this->tipo,
            'url' => $this->url,
        ];
    }
}
