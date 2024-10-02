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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('tahun');
            $table->string('materi');
            $table->integer('pertemuan');
            $table->foreignId('dosens_id');
            $table->foreignId('matkuls_id');
            $table->foreignId('prodis_id');
            $table->foreignId('kelas_id');
            $table->integer('tidak_hadir');
            $table->integer('hadir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
