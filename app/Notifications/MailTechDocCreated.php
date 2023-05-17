<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailTechDocCreated extends Notification implements ShouldQueue
{
    use Queueable;
    public array $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            ->subject('Новый технический документ: ' . $this->data['client_address'])
            ->line('Новый документ загружен в раздел технической документации.')
            ->line('Адрес объекта: ' . $this->data['client_address'])
            ->line('Имя заказчика: ' . $this->data['client_name'])
            ->line('Название документа: ' . $this->data['tech_doc_name'])
            ->line('Комментарий: ' . $this->data['tech_doc_comment'])
            ->line('Загрузил: ' . $this->data['uploaded_name'])
            ->action('Перейти к документу', $this->data['tech_doc_url'])
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
