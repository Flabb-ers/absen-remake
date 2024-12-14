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
        Schema::create('kaprodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodis_id')->constrained('prodi')->onDelete('cascade');
            $table->string('nama');
            $table->string('no_telephone');
            $table->integer('status');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_first_login')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaprodi');
    }
};
