<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var string
     */
    public $password;

    /**
     * Create a new notification instance.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
                    ->subject('Welcome ' . $notifiable->name . ' to ' . config('app.name'))
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('You are now an admin on our platform')
                    ->line('Your username: ' . $notifiable->email)
                    ->line('Your password: ' . $this->password)
                    ->action('Sign in now', url('/mx-admin/login'))
                    ->salutation('Regards');
    }
}
