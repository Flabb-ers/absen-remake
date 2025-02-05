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
        Schema::create('Tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id');
            $table->foreignId('matkul_id');
            $table->foreignId('kelas_id');
            $table->foreignId('jadwal_id');
            $table->integer('tugas_ke');
            $table->float('nilai');
            $table->boolean('setuju')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tugas');
    }
};
