<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        URL::forceRootUrl(config('app.url'));

        // 1. Generate the Backend Signed URL
        $backendUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // 2. Extract the signature part
        $queryString = parse_url($backendUrl, PHP_URL_QUERY);

        // 3. Manually build the Frontend URL
        // This points to your Nuxt page: http://localhost:3000/verify-email
        $frontendUrl = config('app.frontend_url') . '/verify-email?' .
            'id=' . $notifiable->getKey() .
            '&hash=' . sha1($notifiable->getEmailForVerification()) .
            '&' . $queryString;

        return (new MailMessage)
            ->subject('Verify Your Email ✨')
            ->markdown('emails.verify-email', [
                'url' => $frontendUrl,
                'name' => $notifiable->name
            ]);
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
