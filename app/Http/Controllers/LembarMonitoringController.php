<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LembarMonitoringController extends Controller
{
    protected $role;
    protected $userId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->role = Session::get('user.role');
            $this->userId = Session::get('user.id');
            return $next($request);
        });
    }
    public function index($jadwal_id)
    {
        // INI RANCU
        // $messages = Message::with(['replies','jadwal.dosen','jadwal','kelas','matkul','kelas.prodi'])
        //     ->where('jadwal_id', $jadwal_id)
        //     ->whereNull('parent_id')
        //     ->get();


        // ATAU INI SAYA JUGA TIDAK TAHU
        $jadwal = Jadwal::findOrFail($jadwal_id);

        $receiverType = $this->getModelNamespaceFromRole($this->role);
        $dosenType = $this->getModelNamespaceFromRole('dosen');

        $messages = Message::with(['replies', 'jadwal.dosen', 'jadwal', 'kelas', 'matkul', 'kelas.prodi'])
            ->where('jadwal_id', $jadwal_id)
            ->where(function ($query) use ($jadwal, $receiverType, $dosenType) {
                $query->where(function ($subQuery) use ($jadwal, $receiverType, $dosenType) {
                    $subQuery->where('sender_id', $this->userId)
                        ->where('sender_type', $receiverType)
                        ->where('receiver_id', $jadwal->dosens_id)
                        ->where('receiver_type', $dosenType);
                })->orWhere(function ($subQuery) use ($jadwal, $receiverType, $dosenType) {
                    $subQuery->where('receiver_id', $this->userId)
                        ->where('receiver_type', $receiverType)
                        ->where('sender_id', $jadwal->dosens_id)
                        ->where('sender_type', $dosenType);
                });
            })
            ->whereNull('parent_id')
            ->get();


        return view('pages.lembar-monitoring.index', compact('messages'));
    }
    protected function getModelNamespaceFromRole($role)
    {
        $roleToModelMap = [
            'admin' => 'App\Models\Admin',
            'direktur' => 'App\Models\Direktur',
            'wakil_direktur' => 'App\Models\Wadir',
            'kaprodi' => 'App\Models\Kaprodi',
            'mahasiswa' => 'App\Models\Mahasiswa',
            'dosen' => 'App\Models\Dosen'
        ];

        return $roleToModelMap[$role] ?? '';
    }
}
