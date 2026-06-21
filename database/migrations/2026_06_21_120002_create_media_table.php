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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            // polymorphic relation: mediable_id + mediable_type
            // mediable_type holds the model class, e.g. App\Models\Hall or App\Models\FlowerShop
            $table->unsignedBigInteger('mediable_id');
            $table->string('mediable_type');
            $table->enum('type', ['image', 'video']);
            $table->string('path');
            $table->timestamps();

            $table->index(['mediable_id', 'mediable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
