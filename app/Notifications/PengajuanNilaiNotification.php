<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PengajuanNilaiNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $nilai;
    protected $value;
    public function __construct($nilai,$value)
    {
        $this->nilai = $nilai;
        $this->value = $value;
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
                'notification_type' => 'nilai',
                'message_content' => 'Segera Verifikasi Data Nilai',
                'title' => 'Pengajuan Rekap Nilai',
                'name' => $this->nilai->jadwal->dosen->nama,
                'matkul' => $this->nilai->matkul->nama_matkul,
                'class' => $this->nilai->kelas->nama_kelas
            ];
        } elseif(Auth::guard('admin')->check()) {
            return [
                'notification_type' => 'nilai',
                'message_content' => 'Verifikasi Data nilai Berhasil',
                'title' => 'Pengajuan Rekap nilai',
                'name' => $this->nilai->jadwal->dosen->nama,
                'matkul' => $this->nilai->matkul->nama_matkul,
                'class' => $this->nilai->kelas->nama_kelas,
                'nilai'=>$this->value->nilai_huruf ?? null
            ];
        }
        return [];
    }
}
