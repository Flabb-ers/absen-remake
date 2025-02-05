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
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();
            $table->integer('pertemuan');
            $table->foreignId('matkuls_id');
            $table->foreignId('kelas_id');
            $table->foreignId('jadwals_id');
            $table->string('materi')->nullable();
            $table->string('tahun');
            $table->string('pustaka')->nullable();
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
        Schema::dropIfExists('kontraks');
    }
};
