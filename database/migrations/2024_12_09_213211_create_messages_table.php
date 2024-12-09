<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->morphs('sender');
            $table->foreignId('receiver_id')->constrained('dosens');
            $table->foreignId('matkul_id')->constrained('matkuls'); 
            $table->text('message'); 
            $table->timestamp('sent_at');
            $table->foreignId('jadwal_id')->constrained('jadwals'); 
            $table->foreignId('kelas_id')->constrained('kelas'); 
            $table->foreignId('parent_message_id')->nullable()->constrained('messages'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
