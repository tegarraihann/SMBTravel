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
        Schema::create('manasik_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manasik_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['registered', 'attended', 'absent', 'excused'])->default('registered');
            $table->datetime('registered_at')->nullable();
            $table->datetime('attended_at')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('completion_score', 5, 2)->nullable(); // For quiz/evaluation scores
            $table->timestamps();

            $table->unique(['manasik_session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manasik_attendances');
    }
};