<?php

namespace App\Notifications;

use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PembayaranNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $pembayaran;
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
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

        if (Session::get('user.role') == 'mahasiswa') {
            return [
                'notification_type' => 'pembayaran',
                'title' => 'Konfirmasi Pembayaran Diperlukan',
                'message_content' => 'Menunggu Konfirmasi',
                'name' => $this->pembayaran->mahasiswa->nama_lengkap,
                'class' => $this->pembayaran->mahasiswa->kelas->nama_kelas,
                'prodi' => $this->pembayaran->prodi->nama_prodi,
                'pembayaran_id' => $this->pembayaran->id,
                'status' => $this->pembayaran->status_pembayaran,
                'keterangan' => $this->pembayaran->keterangan,
            ];
        } elseif (Session::get('user.role') == 'admin') {
            if ($this->pembayaran->keterangan == 'Belum' && $this->pembayaran->status_pembayaran == 0) {
                return [
                    'notification_type' => 'pembayaran',
                    'title' => 'Pembayaran Ditolak',
                    'message_content' => 'Segera Hubungi Akadmik',
                    'pembayaran_id' => $this->pembayaran->id,
                    'status' => $this->pembayaran->status_pembayaran,
                    'keterangan' => $this->pembayaran->keterangan,
                ];
            } elseif ($this->pembayaran->keterangan == 'Sudah' && $this->pembayaran->status_pembayaran == 1) {
                return [
                    'notification_type' => 'pembayaran',
                    'title' => 'Pembayaran Berhasil Diterima',
                    'message_content' => 'Segera lakukan Pengajuan Krs',
                    'pembayaran_id' => $this->pembayaran->id,
                    'status' => $this->pembayaran->status_pembayaran,
                    'keterangan' => $this->pembayaran->keterangan,
                ];
            }
        }
        return [];
    }
}
