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
        Schema::create('manasik_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['online', 'offline', 'hybrid'])->default('hybrid');
            $table->datetime('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable(); // For offline/hybrid sessions
            $table->text('online_link')->nullable(); // For online/hybrid sessions
            $table->string('instructor_name')->nullable();
            $table->string('instructor_contact')->nullable();
            $table->text('materials')->nullable(); // JSON array of materials
            $table->boolean('is_mandatory')->default(true);
            $table->integer('max_participants')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manasik_sessions');
    }
};