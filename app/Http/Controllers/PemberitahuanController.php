<?php

namespace App\Http\Controllers;

use Log;
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
        
        $request->validate([
            'message' => 'required|string',
            'jadwal_id' => 'required|exists:jadwals,id',
            'sender_id' => 'required|integer',
            'sender_type' => 'required|string',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $senderModel = "App\\Models\\" . $request->sender_type;

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

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'sender_type' => $senderModel,
            'receiver_id' => $jadwal->dosens_id,
            'matkul_id' => $jadwal->matkuls_id,
            'message' => $request->message,
            'sent_at' => now(),
            'jadwal_id' => $request->jadwal_id,
            'kelas_id' => $jadwal->kelas_id,
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim!',
            'data' => $message,
        ]);
    }

    public function getMessages(Request $request)
    {
        $jadwalId = $request->jadwal_id;
        $messages = Message::where('jadwal_id', $jadwalId)
        ->orderBy('sent_at', 'asc')->get();
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
            ->where('sender_id', $request->sender_id)
            ->whereIn('sender_type', [$normalizedSenderType, 'App\Models\Dosen'])
            ->where('receiver_id', $this->userId)
            ->get();

        return response()->json($messages);
    }
}
