<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MessageReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $message;
    private $sender;
    private $receiver;


    /**
     * Create a new notification instance.
     */
    public function __construct($message,$sender,$receiver)
    {
        $this->message=$message;
        $this->sender=$sender;
        $this->receiver=$receiver;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast(object $notifiable)
    {
      //  dump('Notification sent to user:', $notifiable->id);

      Log::info('Notification sent to user: ' . $notifiable->id, [
        'message' => $this->message->message,
        'sender_id' => $this->sender->id,
        'receiver_id' => $this->receiver->id,
    ]);
        return new BroadcastMessage([
            'message' => $this->message->message,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'receiver_id' => $this->receiver->id,
            'receiver_name' => $this->receiver->name,
        ]);}

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
