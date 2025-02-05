<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PengajuanKontrakNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $kontrak;
    public function __construct($kontrak)
    {
        $this->kontrak = $kontrak;
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
                'notification_type' => 'kontrak',
                'message_content' => 'Segera Verifikasi Data Kontrak Perkuliahan',
                'title' => 'Pengajuan Rekap Kontrak Perkuliahan',
                'name' => $this->kontrak->jadwal->dosen->nama,
                'matkul' => $this->kontrak->matkul->nama_matkul,
                'class' => $this->kontrak->kelas->nama_kelas
            ];
        } else {
            return [
                'notification_type' => 'kontrak',
                'message_content' => 'Verifikasi Data Kontrak Berhasil',
                'title' => 'Pengajuan Rekap Kontrak Perkuliahan ',
                'name' => $this->kontrak->jadwal->dosen->nama,
                'matkul' => $this->kontrak->matkul->nama_matkul,
                'class' => $this->kontrak->kelas->nama_kelas
            ];
        }
    }
}
