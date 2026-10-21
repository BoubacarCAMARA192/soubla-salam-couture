<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public string $url = '',
        public string $icon = 'bike',
        public bool $mail = true,
    ) {}

    public function via($notifiable): array
    {
        $via = ['database'];

        if ($this->mail && filled($notifiable->email)) {
            $via[] = 'mail';
        }

        return $via;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'icon' => $this->icon,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title.' – '.config('app.name'))
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line($this->body);

        if ($this->url !== '') {
            $mail->action('Voir dans mon espace', url($this->url));
        }

        $mail->line('À très bientôt, l\'équipe de l\'atelier Soubla Salam Couture.');

        return $mail;
    }
}
