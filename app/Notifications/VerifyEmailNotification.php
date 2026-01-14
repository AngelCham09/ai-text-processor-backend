<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
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
        // 1. Create the secure backend URL (this has /id/hash in the path)
        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // 2. Parse the URL to get pieces
        $urlParts = parse_url($temporarySignedUrl);

        // 3. Extract ID and Hash from the path string
        // Path looks like: /api/email/verify/1/abc...
        $segments = explode('/', trim($urlParts['path'], '/'));

        // Based on your route: /email/verify/{id}/{hash}
        // These are usually the last two segments
        $id = $segments[count($segments) - 2];
        $hash = $segments[count($segments) - 1];

        // 4. Build the final Nuxt link with everything in the query string
        // Logic: BASE_URL + /verify-email + ?id=...&hash=...&expires=...&signature=...
        $frontendUrl = config('app.frontend_url') . '/verify-email?' . http_build_query([
            'id' => $id,
            'hash' => $hash,
            'expires' => request()->query('expires', $urlParts['query'] ?? ''),
        ]);

        // Append the signature manually because it's already in the query string from parse_url
        $frontendUrl = config('app.frontend_url') . '/verify-email?id=' . $id . '&hash=' . $hash . '&' . $urlParts['query'];

        return (new MailMessage)
            ->subject('Welcome to Writing Assistant ✨')
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
