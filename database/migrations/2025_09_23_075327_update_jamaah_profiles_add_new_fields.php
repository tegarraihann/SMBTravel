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
            // Rename and modify existing fields
            $table->renameColumn('nama_lengkap', 'nama_lengkap_bin_binti');
            $table->renameColumn('no_ktp', 'nik');
            $table->integer('usia')->nullable()->after('tanggal_lahir');

            // New fields
            $table->string('nama_marketing')->nullable()->after('no_darurat');
            $table->date('rencana_keberangkatan')->nullable()->after('paket_id');
            $table->boolean('program_talangan')->default(false)->after('rencana_keberangkatan');
            $table->json('cicilan_data')->nullable()->after('program_talangan'); // Max 5 rows: [{dp, tenor, cicilan_perbulan}]

            // Payment fields
            $table->string('sistem_pembayaran')->default('Transfer')->after('dp_paid');
            $table->date('tgl_pelunasan')->nullable()->after('sistem_pembayaran');

            // Group jamaah data
            $table->json('jamaah_rombongan')->nullable()->after('tgl_pelunasan'); // Array of jamaah group members

            // Additional info
            $table->string('sumber_info_mmb')->nullable()->after('jamaah_rombongan');
            $table->text('kelengkapan_dokumen')->nullable()->after('sumber_info_mmb');
            $table->date('tanggal_diterima_perlengkapan')->nullable()->after('kelengkapan_dokumen');
            $table->text('detail_perlengkapan_diterima')->nullable()->after('tanggal_diterima_perlengkapan');
            $table->string('pic_penerima')->nullable()->after('detail_perlengkapan_diterima');
            $table->text('request_khusus')->nullable()->after('pic_penerima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            // Revert field renames
            $table->renameColumn('nama_lengkap_bin_binti', 'nama_lengkap');
            $table->renameColumn('nik', 'no_ktp');

            // Drop new fields
            $table->dropColumn([
                'usia',
                'nama_marketing',
                'rencana_keberangkatan',
                'program_talangan',
                'cicilan_data',
                'sistem_pembayaran',
                'tgl_pelunasan',
                'jamaah_rombongan',
                'sumber_info_mmb',
                'kelengkapan_dokumen',
                'tanggal_diterima_perlengkapan',
                'detail_perlengkapan_diterima',
                'pic_penerima',
                'request_khusus'
            ]);
        });
    }
};
