<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailClientCreated extends Notification implements ShouldQueue
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
            ->subject('Ваш Личный кабинет в КомпанияМечты')
            ->greeting('Здравствуйте, ' . $this->data['name'] . '!')
            ->line('Для Вас создан личный кабинет в компании "КомпанияМечты":')
            ->line('Email: '.$this->data['email'])
            ->line('Пароль: '.$this->data['password'])
            ->action('Перейти в личный кабинет', env('APP_URL'))
            ->line('Примечание. Вы можете изменить свой пароль, воспользовавшись функций "Забыли пароль" на странице входа.')
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
