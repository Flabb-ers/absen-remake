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
        Schema::create('nilai_hurufs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->references('id')->on('mahasiswas');
            $table->foreignId('kelas_id')->references('id')->on('kelas');
            $table->foreignId('matkul_id')->references('id')->on('matkuls');
            $table->integer('nilai_total');
            $table->string('nilai_huruf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_hurufs');
    }
};
