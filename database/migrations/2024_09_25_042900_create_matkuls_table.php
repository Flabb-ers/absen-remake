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
        Schema::create('matkuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->references('id')->on('prodi');
            $table->foreignId('semester_id')->references('id')->on('semesters');
            $table->string('nama_matkul');
            $table->string('alias');
            $table->string('kode');
            $table->integer('teori')->nullable();
            $table->integer('praktek')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkuls');
    }
};
