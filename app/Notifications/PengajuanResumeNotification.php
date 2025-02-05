<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PengajuanResumeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $resume;
    public function __construct($resume)
    {
        $this->resume = $resume;
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
        if (Auth::guard('dosen')->check()) {
            return [
                'notification_type' => 'resume',
                'message_content' => 'Segera Verifikasi Data Berita Acara',
                'title' => 'Pengajuan Rekap Berita Acara',
                'name' => $this->resume->jadwal->dosen->nama,
                'matkul' => $this->resume->matkul->nama_matkul,
                'class' => $this->resume->kelas->nama_kelas
            ];
        } else {
            return [
                'notification_type' => 'resume',
                'message_content' => 'Verifikasi Data Berita Acara Berhasil',
                'title' => 'Pengajuan Rekap Berita Acara',
                'name' => $this->resume->jadwal->dosen->nama,
                'matkul' => $this->resume->matkul->nama_matkul,
                'class' => $this->resume->kelas->nama_kelas
            ];
        }
    }
}
