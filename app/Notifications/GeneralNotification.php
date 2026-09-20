<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class GeneralNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $messageContent;
    protected $type;
    protected $url;

    public function __construct($title, $messageContent, $type = 'info', $url = null)
    {
        $this->title = $title;
        $this->messageContent = $messageContent;
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * Pilih channel pengiriman: 'database' untuk web, 'mail' untuk email, 'broadcast' untuk push notification.
     */
    public function via(object $notifiable): array
    {
        // Tambahkan 'broadcast' agar notifikasi terkirim secara real-time via WebSocket
        return ['database', 'mail', 'broadcast'];
    }

    /**
     * Format notifikasi untuk Database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->messageContent,
            'type'    => $this->type,
            'url'     => $this->url,
        ];
    }

    /**
     * Format notifikasi untuk Email menggunakan Template Custom.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title) // Subject / Judul Email
            ->view('emails.general-notification', [
                'title'          => $this->title,
                'messageContent' => $this->messageContent,
                'type'           => $this->type,
                'url'            => $this->url,
                'user'           => $notifiable // Mengirim data user ke view untuk nama penerima
            ]);
    }

    /**
     * Format data yang dikirim secara Real-Time (Push) ke layar pengguna via Pusher/Reverb.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'   => $this->title,
            'message' => $this->messageContent,
            'type'    => $this->type,
            'url'     => $this->url,
        ]);
    }
}