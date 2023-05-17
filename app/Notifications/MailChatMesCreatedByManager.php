<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailChatMesCreatedByManager extends Notification implements ShouldQueue
{
    use Queueable;
    public string $client_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($client_name)
    {
        $this->client_name = $client_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новое сообщение в чате КомпанияМечты')
            ->greeting('Здравствуйте, ' . $this->client_name . '!')
            ->line('У Вас новое сообщение в чате личного кабинета компании "КомпанияМечты".')
            ->action('Перейти к сообщению', route('client.chat'))
            ->line('Это письмо было отправлено автоматически. Пожалуйста, не отвечайте на него. Для контактов используйте dreamcompany-group@mail.ru');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
