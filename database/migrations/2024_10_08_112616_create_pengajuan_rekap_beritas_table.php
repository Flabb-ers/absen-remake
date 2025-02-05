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
        Schema::create('pengajuan_rekap_beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosens_id');
            $table->foreignId('matkuls_id');
            $table->foreignId('jadwal_id');
            $table->foreignId('kelas_id');
            $table->string('pertemuan');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_rekap_beritas');
    }
};
