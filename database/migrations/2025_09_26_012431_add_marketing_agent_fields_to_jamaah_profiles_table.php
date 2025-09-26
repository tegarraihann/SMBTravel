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
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('marketing_id')->nullable()->after('nama_marketing');
            $table->unsignedBigInteger('agent_id')->nullable()->after('marketing_id');

            $table->foreign('marketing_id')->references('id')->on('marketings')->onDelete('set null');
            $table->foreign('agent_id')->references('id')->on('marketing_agents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropForeign(['marketing_id']);
            $table->dropColumn(['agent_id', 'marketing_id']);
        });
    }
};
