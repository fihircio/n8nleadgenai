<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AutomationResult;

class AutomationResultNotification extends Notification
{
    use Queueable;
    public $result;
    public function __construct(AutomationResult $result)
    {
        $this->result = $result;
    }
    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Automation Result is Ready')
            ->greeting('Hello!')
            ->line('Your coin-gated automation has completed.')
            ->line('Status: ' . $this->result->status)
            ->line('Result: ' . json_encode($this->result->result))
            ->action('View Result', url('/dashboard'));
    }
}
