<?php

namespace Database\Seeders;

use App\Models\UmrohPackage;
use App\Models\UmrohSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UmrohScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all packages
        $packages = UmrohPackage::all();

        foreach ($packages as $package) {
            // Create schedules for 2025
            $this->createSchedulesForPackage($package, 2025);
        }
    }

    private function createSchedulesForPackage(UmrohPackage $package, int $year)
    {
        // Schedule data untuk setiap paket
        $scheduleTemplates = [
            // Januari
            ['bulan' => 1, 'tanggal' => 15, 'kuota' => 40],
            ['bulan' => 1, 'tanggal' => 30, 'kuota' => 35],

            // Februari
            ['bulan' => 2, 'tanggal' => 10, 'kuota' => 40],
            ['bulan' => 2, 'tanggal' => 25, 'kuota' => 35],

            // Maret
            ['bulan' => 3, 'tanggal' => 12, 'kuota' => 45],
            ['bulan' => 3, 'tanggal' => 28, 'kuota' => 40],

            // April
            ['bulan' => 4, 'tanggal' => 8, 'kuota' => 35],
            ['bulan' => 4, 'tanggal' => 22, 'kuota' => 40],

            // Mei
            ['bulan' => 5, 'tanggal' => 5, 'kuota' => 40],
            ['bulan' => 5, 'tanggal' => 20, 'kuota' => 45],

            // Juni
            ['bulan' => 6, 'tanggal' => 3, 'kuota' => 35],
            ['bulan' => 6, 'tanggal' => 18, 'kuota' => 40],

            // Juli - High season
            ['bulan' => 7, 'tanggal' => 2, 'kuota' => 50, 'biaya_tambahan' => 2000000],
            ['bulan' => 7, 'tanggal' => 16, 'kuota' => 50, 'biaya_tambahan' => 2000000],
            ['bulan' => 7, 'tanggal' => 30, 'kuota' => 50, 'biaya_tambahan' => 2000000],

            // Agustus - High season
            ['bulan' => 8, 'tanggal' => 14, 'kuota' => 50, 'biaya_tambahan' => 2000000],
            ['bulan' => 8, 'tanggal' => 28, 'kuota' => 50, 'biaya_tambahan' => 2000000],

            // September
            ['bulan' => 9, 'tanggal' => 10, 'kuota' => 40],
            ['bulan' => 9, 'tanggal' => 25, 'kuota' => 35],

            // Oktober
            ['bulan' => 10, 'tanggal' => 8, 'kuota' => 40],
            ['bulan' => 10, 'tanggal' => 23, 'kuota' => 35],

            // November
            ['bulan' => 11, 'tanggal' => 5, 'kuota' => 40],
            ['bulan' => 11, 'tanggal' => 20, 'kuota' => 35],

            // Desember - High season
            ['bulan' => 12, 'tanggal' => 10, 'kuota' => 45, 'biaya_tambahan' => 1500000],
            ['bulan' => 12, 'tanggal' => 28, 'kuota' => 45, 'biaya_tambahan' => 1500000],
        ];

        foreach ($scheduleTemplates as $template) {
            UmrohSchedule::create([
                'umroh_package_id' => $package->id,
                'tahun' => $year,
                'bulan' => $template['bulan'],
                'tanggal_keberangkatan' => $template['tanggal'],
                'kuota' => $template['kuota'],
                'terisi' => rand(0, min(10, $template['kuota'])), // Random initial bookings
                'biaya_tambahan' => $template['biaya_tambahan'] ?? 0,
                'status' => 'tersedia',
                'catatan' => $this->generateNotes($template['bulan'], $template['biaya_tambahan'] ?? 0)
            ]);
        }
    }

    private function generateNotes($bulan, $biayaTambahan)
    {
        $notes = [];

        // High season notes
        if (in_array($bulan, [7, 8])) {
            $notes[] = 'Musim liburan sekolah - sangat ramai';
        }

        if ($bulan == 12) {
            $notes[] = 'Musim liburan akhir tahun';
        }

        if ($biayaTambahan > 0) {
            $notes[] = 'Biaya tambahan untuk musim high season';
        }

        // Weather notes
        if (in_array($bulan, [6, 7, 8, 9])) {
            $notes[] = 'Cuaca panas di Arab Saudi';
        } elseif (in_array($bulan, [12, 1, 2, 3])) {
            $notes[] = 'Cuaca sejuk dan nyaman';
        }

        return implode('. ', $notes);
    }
}