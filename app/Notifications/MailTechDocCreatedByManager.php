<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailTechDocCreatedByManager extends Notification implements ShouldQueue
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
            ->subject('Технический документ загружен в личный кабинет КомпанияМечты')
            ->greeting('Здравствуйте, ' . $this->data['client_name'] . '!')
            ->line('В Ваш личный кабинет компании "КомпанияМечты" загружен новый технический документ "' . $this->data['tech_doc_name'] . '".')
            ->lineIf(!empty($this->data['tech_doc_comment']),'Комментарий: ' . $this->data['tech_doc_comment'])
            ->action('Перейти к документу', route('client.tech_doc'))
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
