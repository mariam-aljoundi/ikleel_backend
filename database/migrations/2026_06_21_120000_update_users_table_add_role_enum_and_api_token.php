<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // mysql does not let us alter a string column straight to enum
        // without doctrine/dbal, so we do it with raw SQL.
        DB::statement("ALTER TABLE users MODIFY role ENUM('customer','hall_owner','flower_owner') NOT NULL DEFAULT 'customer'");

        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 80)->nullable()->unique()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });

        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) NOT NULL");
    }
};
