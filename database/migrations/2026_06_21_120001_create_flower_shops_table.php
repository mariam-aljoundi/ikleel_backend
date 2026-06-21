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
        Schema::create('flower_shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            // الترخيص إجباري: بدون ترخيص ما في إمكانية يسجل صاحب المحل كمزود خدمة
            $table->string('license')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flower_shops');
    }
};
