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

        Schema::create('engagement_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('permission_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('engagement')->constrained('engagements')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('create')->default(false);
            $table->boolean('read')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_has_permissions');
    }
};
