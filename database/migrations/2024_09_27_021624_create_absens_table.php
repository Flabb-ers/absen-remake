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
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->integer('pertemuan');
            $table->date('tanggal');
            $table->string('tahun');
            $table->foreignId('jadwals_id');
            $table->foreignId('matkuls_id');
            $table->foreignId('dosens_id');
            $table->foreignId('prodis_id');
            $table->foreignId('kelas_id');
            $table->foreignId('mahasiswas_id');
            $table->string('status');
            $table->boolean('setuju_wadir')->default(false); 
            $table->boolean('setuju_kaprodi')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absens');
    }
};
