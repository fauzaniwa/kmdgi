<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $type;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $type = 'info', $url = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type; // 'success', 'warning', atau 'info'
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Menyimpan langsung ke database
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'type'    => $this->type,
            'url'     => $this->url,
        ];
    }
}