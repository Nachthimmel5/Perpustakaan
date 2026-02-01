<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_anggota')->nullable();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'anggota']);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_anggota')
                  ->references('id_anggota')
                  ->on('anggota')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};