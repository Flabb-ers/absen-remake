<?php

namespace App\Notifications;

use App\Models\Matkul;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageSentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $matkul = Matkul::find($this->message->matkul_id);
        $dari = '';
        if ($this->message->sender_type == 'App\Models\Direktur') {
            $dari = 'Direktur';
        } elseif ($this->message->sender_type == 'App\Models\Wadir') {
            $dari = 'Wakil Direktur';
        }
        if (Session::get('user.role') == 'direktur' || Session::get('user.role') == 'wakil_direktur') {
            return [
                'sender_name' => $this->getSenderName(),
                'notification_type' => 'pemberitahuan',
                'message_content' => $this->message->message,
                'matkul' => $matkul->nama_matkul,
                'title' => 'Pemberitahuan dari ' . $dari
            ];
        } else {
            return [
                'sender_name' => $this->getSenderName(),
                'notification_type' => 'pemberitahuan',
                'message_content' => $this->message->message,
                'matkul' => $matkul->nama_matkul,
                'title' => 'Membalas Pemberitahuan'
            ];
        }
    }

    private function getSenderName()
    {
        $senderType = $this->message->sender_type;
        $sender = $senderType::find($this->message->sender_id);

        return $sender->name ?? $sender->nama ?? 'Pengirim';
    }
}
