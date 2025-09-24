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
            $table->string('foto_ktp')->nullable()->after('admin_approval_at');
            $table->string('foto_kk')->nullable()->after('foto_ktp');
            $table->string('foto_diri')->nullable()->after('foto_kk');
            $table->string('foto_paspor')->nullable()->after('foto_diri');
            $table->string('scan_buku_nikah')->nullable()->after('foto_paspor');
            $table->string('scan_akta_lahir')->nullable()->after('scan_buku_nikah');
            $table->boolean('documents_verified')->default(false)->after('scan_akta_lahir');
            $table->timestamp('documents_uploaded_at')->nullable()->after('documents_verified');
            $table->text('document_notes')->nullable()->after('documents_uploaded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'foto_ktp',
                'foto_kk',
                'foto_diri',
                'foto_paspor',
                'scan_buku_nikah',
                'scan_akta_lahir',
                'documents_verified',
                'documents_uploaded_at',
                'document_notes'
            ]);
        });
    }
};
