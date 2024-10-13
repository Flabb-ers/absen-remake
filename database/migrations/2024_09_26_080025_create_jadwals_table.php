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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosens_id');
            $table->foreignId('matkuls_id')->constrained()->onDelete('cascade');;
            $table->foreignId('kelas_id');
            $table->foreignId('ruangans_id');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('hari');
            $table->string('tahun');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
