<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PemberitahuanController extends Controller
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
    public function sendMessage(Request $request)
    {
        if ($this->role != 'direktur' && $this->role != 'wakil_direktur') {
            $request->validate([
                'message' => 'required|string',
                'jadwal_id' => 'required|exists:jadwals,id',
                'sender_id' => 'required|integer',
                'sender_type' => 'required|string',
                'receiver_type' => 'required|string',
                'receiver_id' => 'required|integer', 
            ]);
        } else {
            $request->validate([
                'message' => 'required|string',
                'jadwal_id' => 'required|exists:jadwals,id',
                'sender_id' => 'required|integer',
                'sender_type' => 'required|string',
            ]);
        }
    
        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $senderModel = "App\\Models\\" . $request->sender_type;
        $receiverType = $request->receiver_type;
    
        $receiverType = trim($receiverType, '\\');
        if (strpos($receiverType, 'App\\Models\\') !== 0) {
            $receiverType = ltrim($receiverType, 'App\Models\\');
            $receiverType = "App\\Models\\" . $receiverType;
        }
    
        if (!class_exists($senderModel)) {
            return response()->json([
                'message' => 'Model pengirim tidak ditemukan.',
            ], 400);
        }

        $sender = $senderModel::find($request->sender_id);
        if (!$sender) {
            return response()->json([
                'message' => 'Data pengirim tidak valid.',
            ], 400);
        }
    
        if ($this->role == 'direktur' || $this->role == 'wakil_direktur') {
            $receiver = Dosen::find($jadwal->dosens_id);
            
            if (!$receiver) {
                return response()->json([
                    'message' => 'Dosen yang dituju tidak ditemukan.',
                ], 400);
            }
    
            $message = Message::create([
                'sender_id' => $request->sender_id,
                'sender_type' => $senderModel,
                'receiver_type' => 'App\Models\Dosen',
                'receiver_id' => $receiver->id, 
                'matkul_id' => $jadwal->matkuls_id,
                'message' => $request->message,
                'sent_at' => now(),
                'jadwal_id' => $request->jadwal_id,
                'kelas_id' => $jadwal->kelas_id,
            ]);
        } elseif ($this->role == 'dosen') {
            $message = Message::create([
                'sender_id' => $request->sender_id,
                'sender_type' => $senderModel,
                'receiver_type' => $receiverType,
                'receiver_id' => $request->receiver_id, 
                'matkul_id' => $jadwal->matkuls_id,
                'message' => $request->message,
                'sent_at' => now(),
                'jadwal_id' => $request->jadwal_id,
                'kelas_id' => $jadwal->kelas_id,
            ]);
        }
    
        return response()->json([
            'message' => 'Pesan berhasil dikirim!',
            'data' => $message,
        ]);
    }
    


    public function getMessages(Request $request)
    {
        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        if ($this->role == 'wakil_direktur') {
            $messages = Message::where('jadwal_id', $jadwal->id)
                ->whereIn('receiver_id', [$jadwal->dosens_id, $this->userId])
                ->whereIn('sender_id', [$jadwal->dosens_id, $this->userId])
                ->whereIn('sender_type', ['App\Models\Wadir', 'App\Models\Dosen'])
                ->whereIn('receiver_type', ['App\Models\Wadir', 'App\Models\Dosen'])
                ->orderBy('sent_at', 'asc')->get();
        }elseif($this->role == 'direktur'){
            $messages = Message::where('jadwal_id', $jadwal->id)
                ->whereIn('receiver_id', [$jadwal->dosens_id, $this->userId])
                ->whereIn('sender_id', [$jadwal->dosens_id, $this->userId])
                ->whereIn('sender_type', ['App\Models\Direktur', 'App\Models\Dosen'])
                ->whereIn('receiver_type', ['App\Models\Direktur', 'App\Models\Dosen'])
                ->orderBy('sent_at', 'asc')->get();
        };
        return response()->json($messages);
    }
    public function getMessagesDosen(Request $request)
    {
        $senderType = $request->input('sender_type');

        if (strpos($senderType, 'AppModels') === 0) {
            $normalizedSenderType = 'App\\Models\\' . str_replace('AppModels', '', $senderType);
        } else {
            $normalizedSenderType = $senderType;
        }

        $messages = Message::where('jadwal_id', $request->jadwal_id)
            ->whereIn('sender_id', [$request->sender_id,$this->userId])
            ->whereIn('receiver_id', [$request->sender_id,$this->userId])
            ->whereIn('sender_type', [$normalizedSenderType, 'App\Models\Dosen'])
            ->whereIn('receiver_type', [$normalizedSenderType, 'App\Models\Dosen'])
            ->get();

        return response()->json($messages);
    }

    public function pollMessages(Request $request)
    {
        $userType = $request->input('user_type');
        $userId = $request->input('user_id');
        $jadwalId = $request->input('jadwal_id');
        $lastMessageTime = $request->input('last_message_time');

        $newMessages = Message::where('jadwal_id', $jadwalId)
            ->where('created_at', '>', $lastMessageTime)
            ->where(function ($query) use ($userType, $userId) {
                $query->where('receiver_type', $userType)
                    ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($newMessages);
    }
}
