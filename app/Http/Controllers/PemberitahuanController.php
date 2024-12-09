<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Jadwal;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemberitahuanController extends Controller
{
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
            'sender_type' => $request->sender_type,
            'receiver_id' => auth()->id(),
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
    

}
