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
        Schema::table('registration_settings', function (Blueprint $table) {
            $table->enum('ip_status', ['enable', 'disable'])
                ->default('disable')
                ->after('similarity_threshold');

            $table->string('whitelist_ips')
                ->nullable()
                ->after('ip_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_settings', function (Blueprint $table) {
            $table->dropColumn(['ip_status', 'whitelist_ips']);
        });
    }
};