<?php

namespace Botble\Marketplace\Notifications;

use Botble\Marketplace\Models\VendorWarning;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorWarningNotification extends Notification
{
    use Queueable;

    public function __construct(protected VendorWarning $warning)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Important Notice: ' . $this->warning->title)
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a ' . $this->warning->severity . ' notice regarding your store.')
            ->line('**' . $this->warning->title . '**')
            ->line($this->warning->content)
            ->action('View Warning in Dashboard', route('marketplace.vendor.dashboard'))
            ->line('Please acknowledge this notice by logging into your vendor dashboard.')
            ->line('If you have any questions, please contact support.');
    }

    public function toArray($notifiable): array
    {
        return [
            'warning_id' => $this->warning->id,
            'title' => $this->warning->title,
            'severity' => $this->warning->severity,
            'content' => $this->warning->content,
        ];
    }
}
