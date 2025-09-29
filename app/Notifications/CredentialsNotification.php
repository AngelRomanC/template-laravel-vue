<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CredentialsNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $email;
    protected $password;
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Credenciales de Acceso')
                    ->greeting('¡Hola!')
                    ->line('Se ha creado una cuenta para ti.')
                    ->line('Correo: ' . $this->email)
                    ->line('Contraseña: ' . $this->password)
                    ->line('Por favor, cambia tu contraseña después de iniciar sesión.')
                    ->action('Iniciar sesión', url('/login'))
                    ->line('¡Gracias por usar nuestra aplicación!')
                    ->salutation('Documentación Developer.com');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
