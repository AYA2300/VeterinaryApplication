<?php

namespace App\Notifications;

use App\Models\Group_Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CommunityMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;


    /**
     * Create a new notification instance.
     */

     public function __construct(Group_Message $message)
     {
         $this->message = $message;
     }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        // يمكنك استخدام قنوات متعددة مثل broadcast, mail, أو database
        return ['broadcast','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast($notifiable)
    {

        Log::info("Notification sent to: " . $notifiable->email . " with message: " . $this->message->message);
       // dump('Notification sent to user:', $notifiable->id);

        return new BroadcastMessage([
            'message' => $this->message->message,
            'sender_id' => $this->message->breeder_id,
            'sender_name' => $this->message->breeder->name,
            'community_id' => $this->message->community_id,
             'notification_type' => 'community'
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->breeder_id,
            'sender_name' => $this->message->breeder->name,
            'community_id' => $this->message->community_id,
            'notification_type' => 'community'


        ];
    }
}
