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
        Schema::table('halls', function (Blueprint $table) {
            // الترخيص إجباري: بدون ترخيص ما في إمكانية يسجل صاحب الصالة كمزود خدمة
            $table->string('license')->after('location');
            $table->unique('license');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->dropUnique(['license']);
            $table->dropColumn('license');
        });
    }
};
