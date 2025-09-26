<?php

namespace Database\Seeders;

use App\Models\UmrohPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UmrohPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'nama_paket' => 'City Tour Malaysia',
                'deskripsi' => 'Paket umroh dengan city tour Malaysia, kunjungan Kuala Lumpur dan sekitarnya sebelum ke Tanah Suci.',
                'harga' => 24500000,
                'durasi_hari' => 12,
                'fasilitas' => [
                    'hotel' => '4 bintang di Makkah dan Madinah',
                    'transportasi' => 'Bus AC selama perjalanan',
                    'makan' => '3x sehari prasmanan',
                    'city_tour' => 'Kuala Lumpur city tour',
                    'pemandu' => 'Tour guide berpengalaman',
                    'visa' => 'Visa umroh included'
                ],
                'jadwal_keberangkatan' => [
                    [
                        'tanggal' => '2025-01-15',
                        'kuota' => 40,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-02-20',
                        'kuota' => 40,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-03-25',
                        'kuota' => 40,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-04-30',
                        'kuota' => 40,
                        'status' => 'tersedia'
                    ]
                ],
                'status' => 'aktif'
            ],
            [
                'nama_paket' => 'Plus Tha\'if',
                'deskripsi' => 'Paket umroh dengan tambahan ziarah ke Tha\'if, kota sejuk di pegunungan Arab Saudi.',
                'harga' => 34500000,
                'durasi_hari' => 14,
                'fasilitas' => [
                    'hotel' => '4 bintang di Makkah, Madinah, dan Tha\'if',
                    'transportasi' => 'Bus AC + pesawat domestik',
                    'makan' => '3x sehari prasmanan',
                    'ziarah' => 'Tha\'if dan tempat bersejarah lainnya',
                    'pemandu' => 'Tour guide dan mutawif berpengalaman',
                    'visa' => 'Visa umroh included'
                ],
                'jadwal_keberangkatan' => [
                    [
                        'tanggal' => '2025-01-10',
                        'kuota' => 35,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-02-15',
                        'kuota' => 35,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-03-20',
                        'kuota' => 35,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-05-05',
                        'kuota' => 35,
                        'status' => 'tersedia'
                    ]
                ],
                'status' => 'aktif'
            ],
            [
                'nama_paket' => 'Plus Tha\'if dan Kereta Cepat',
                'deskripsi' => 'Paket premium dengan ziarah ke Tha\'if dan pengalaman naik kereta cepat Haramain Express.',
                'harga' => 41000000,
                'durasi_hari' => 16,
                'fasilitas' => [
                    'hotel' => '5 bintang di Makkah dan Madinah',
                    'transportasi' => 'Bus AC + Haramain Express + pesawat domestik',
                    'makan' => '3x sehari dengan menu premium',
                    'ziarah' => 'Tha\'if, Uhud, Quba, dan tempat bersejarah',
                    'premium' => 'Kereta cepat Haramain Express',
                    'pemandu' => 'Tour guide dan mutawif profesional',
                    'visa' => 'Visa umroh included'
                ],
                'jadwal_keberangkatan' => [
                    [
                        'tanggal' => '2025-01-05',
                        'kuota' => 30,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-02-10',
                        'kuota' => 30,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-03-15',
                        'kuota' => 30,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-04-20',
                        'kuota' => 30,
                        'status' => 'tersedia'
                    ],
                    [
                        'tanggal' => '2025-05-25',
                        'kuota' => 30,
                        'status' => 'tersedia'
                    ]
                ],
                'status' => 'aktif'
            ]
        ];

        foreach ($packages as $packageData) {
            UmrohPackage::create($packageData);
        }
    }
}