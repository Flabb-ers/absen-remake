<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PengajuanPresensiNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $presensi;
    public function __construct($presensi)
    {
        $this->presensi = $presensi;
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
                'notification_type' => 'presensi',
                'message_content' => 'Segera Verifikasi Data Presensi',
                'title' => 'Pengajuan Rekap Presensi',
                'name' => $this->presensi->jadwal->dosen->nama,
                'matkul' => $this->presensi->matkul->nama_matkul,
                'class' => $this->presensi->kelas->nama_kelas
            ];
        } else {
            return [
                'notification_type' => 'presensi',
                'message_content' => 'Verifikasi Data Presensi Berhasil',
                'title' => 'Pengajuan Rekap Presensi',
                'name' => $this->presensi->jadwal->dosen->nama,
                'matkul' => $this->presensi->matkul->nama_matkul,
                'class' => $this->presensi->kelas->nama_kelas
            ];
        }
    }
}
