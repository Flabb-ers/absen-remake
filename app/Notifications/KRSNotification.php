<?php

namespace App\Notifications;

use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KRSNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $krs;
    public function __construct($krs)
    {
        $this->krs = $krs;
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
        if ($this->krs->setuju_mahasiswa == 1 && $this->krs->setuju_pa == 0 && $this->krs->status_krs == 0) {
            return [
                'title' => 'Pengajuan KRS',
                'message_content' => 'Harap Periksa dan Segera Verifikasi KRS',
                'name' => $this->krs->mahasiswa->nama_lengkap,
                'kelas' => $this->krs->kelas->nama_kelas,
                'prodi' => $this->krs->prodi->nama_prodi,
                'notification_type' => 'krs'
            ];
        } elseif ($this->krs->setuju_mahasiswa == 1 && $this->krs->setuju_pa == 1 && $this->krs->status_krs == 1){
            return [
                'title' => 'Pengajuan KRS',
                'message_content' => 'KRS Berhasil Diverifikasi',
                'name' => $this->krs->mahasiswa->nama_lengkap,
                'kelas' => $this->krs->kelas->nama_kelas,
                'prodi' => $this->krs->prodi->nama_prodi,
                'notification_type' => 'krs'
            ];
        }
            return [];
    }
}
