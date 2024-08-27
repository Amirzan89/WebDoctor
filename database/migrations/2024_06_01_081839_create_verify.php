<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verify', function (Blueprint $table) {
            $table->id('id_verify');
            $table->string('email',45);
            $table->string('code',6);
            $table->string('link');
            $table->enum('description',['password','email']);
            $table->unsignedSmallInteger('send');
            $table->timestamps();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi');
    }
};
