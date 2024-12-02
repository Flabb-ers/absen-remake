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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_pembimbing_id')
                ->nullable()
                ->constrained('dosens')
                ->onDelete('set null');
            $table->string('nama_lengkap');
            $table->string('nim');
            $table->string('nisn');
            $table->string('nik');
            $table->string('email');
            $table->string('password');
            $table->text('alamat');
            $table->string('no_telephone');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('nama_ibu');
            $table->string('jenis_kelamin');
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->boolean('status_krs')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
