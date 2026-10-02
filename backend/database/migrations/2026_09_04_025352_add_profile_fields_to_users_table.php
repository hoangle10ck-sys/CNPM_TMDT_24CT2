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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }

            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('customer')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Profile fields may have existed before this migration.
    }
};
