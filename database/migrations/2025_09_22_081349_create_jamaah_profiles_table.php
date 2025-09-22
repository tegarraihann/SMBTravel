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
        Schema::create('jamaah_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('no_ktp', 16);
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->string('no_darurat')->nullable();
            $table->string('hubungan_darurat')->nullable();
            $table->foreignId('paket_id');
            $table->text('catatan')->nullable();
            $table->integer('current_step')->default(1);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->decimal('dp_paid', 12, 2)->default(0);
            $table->timestamp('registration_completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaah_profiles');
    }
};
