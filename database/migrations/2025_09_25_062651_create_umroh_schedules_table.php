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
        Schema::create('umroh_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umroh_package_id')->constrained()->onDelete('cascade');
            $table->string('nama_jadwal'); // e.g., "Januari 2025 Batch 1"
            $table->integer('tahun');
            $table->integer('bulan'); // 1-12
            $table->integer('tanggal_keberangkatan'); // 1-31
            $table->date('tanggal_lengkap'); // computed field for easy queries
            $table->integer('kuota')->default(40);
            $table->integer('terisi')->default(0);
            $table->enum('status', ['tersedia', 'penuh', 'ditutup'])->default('tersedia');
            $table->text('catatan')->nullable();
            $table->decimal('biaya_tambahan', 12, 2)->default(0); // jika ada biaya tambahan untuk jadwal tertentu
            $table->timestamps();

            // Indexes
            $table->index(['umroh_package_id', 'tahun', 'bulan']);
            $table->index(['tanggal_lengkap', 'status']);
            $table->unique(['umroh_package_id', 'tanggal_lengkap']); // prevent duplicate schedule
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umroh_schedules');
    }
};