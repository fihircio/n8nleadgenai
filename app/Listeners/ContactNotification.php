<?php

namespace App\Listeners;

//use App\Events\LetterSent;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use LaraZeus\Wind\Events\LetterSent;

class ContactNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LetterSent $event): void
    {
        if (config('saashovel.SEND_EMAIL_CONTACT')) {
            $htmlContent = "
                <html>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <h1 style='color: #4a4a4a; border-bottom: 1px solid #eee; padding-bottom: 10px;'>" . __('New Contact Form Submission') . "</h1>
                    <p><strong style='color: #2c3e50;'>" . __('Name:') . "</strong> {$event->letter->name}</p>
                    <p><strong style='color: #2c3e50;'>" . __('Email:') . "</strong> {$event->letter->email}</p>
                    <p><strong style='color: #2c3e50;'>" . __('Subject:') . "</strong> {$event->letter->title}</p>
                    <p><strong style='color: #2c3e50;'>" . __('Message:') . "</strong></p>
                    <p style='background-color: #f8f8f8; padding: 15px; border-radius: 5px;'>" . nl2br(e($event->letter->message)) . "</p>
                </body>
                </html>
            ";

            Mail::html($htmlContent, function($message) use ($event) {
                $message->to(User::first()->email)
                        ->subject( __('New Contact Form Submission'));
            });
        }
    }
}
