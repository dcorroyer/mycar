<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string $link
     */
    protected string $link;

    /**
     * Create a new notification instance.
     *
     * @param string $link
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Veuillez vérifier votre adresse email')
            ->line(
                'Vous recevez ce message car vous avez demandé la modification de votre adresse email'
            )
            ->action('Vérifier mon email', $this->getLink());
    }

    /**
     * Get notification's link.
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
