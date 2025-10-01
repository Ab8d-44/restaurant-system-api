<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class RegisterCustomerOrManagerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $appName = config('app.name');
        $registerDate = now()->format('Y-m-d H:i:s');

        return (new MailMessage)
            ->mailer('smtp')
            ->subject("🎉 Welcome to $appName!")
            ->view('emails.RegisterCustomerOrManagerNotification', [
                'user' => $this->user,
                'registerDate' => $registerDate,
                'appName' => $appName,
            ])
            ->line('Thank you for joining us!')
            ->salutation('Best regards, The ' . $appName . ' Team');
    }
}
