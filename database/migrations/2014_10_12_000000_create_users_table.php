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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('prename')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->string('password');
            $table->text('image')->nullable();
            $table->string('qrcode')->nullable();
            $table->longText('fingerprint')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('status');
            $table->integer('deleted');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
