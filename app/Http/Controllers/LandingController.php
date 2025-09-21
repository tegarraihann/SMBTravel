<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Landing', [
            'packages' => $this->getPackages(),
            'testimonials' => $this->getTestimonials(),
            'gallery' => $this->getGallery(),
            'countdown' => [
                'deadline' => '2024-12-31T23:59:59',
                'title' => 'Dengan DP 3 Juta Bisa Umroh',
                'description' => 'Promo terbatas! Daftar sekarang dan wujudkan impian umroh Anda.'
            ]
        ]);
    }

    private function getPackages(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Umroh Reguler',
                'price' => 25000000,
                'dp' => 3000000,
                'duration' => '9 Hari',
                'description' => 'Paket umroh ekonomis dengan fasilitas lengkap',
                'features' => [
                    'Tiket pesawat PP',
                    'Hotel bintang 4',
                    'Makan 3x sehari',
                    'Transportasi AC',
                    'Guide berpengalaman',
                    'Manasik'
                ],
                'badge' => 'Best Seller',
                'image' => 'https://images.unsplash.com/photo-1519112232436-9923c6ba3d26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80'
            ],
            [
                'id' => 2,
                'name' => 'Umroh Plus',
                'price' => 35000000,
                'dp' => 5000000,
                'duration' => '12 Hari',
                'description' => 'Umroh plus wisata religi dengan fasilitas premium',
                'features' => [
                    'Tiket pesawat PP',
                    'Hotel bintang 5',
                    'Makan 3x sehari',
                    'Transportasi AC',
                    'Guide berpengalaman',
                    'Manasik',
                    'City tour Madinah',
                    'Ziarah Masjid Quba'
                ],
                'badge' => 'Popular',
                'image' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1932&q=80'
            ],
            [
                'id' => 3,
                'name' => 'Umroh VIP',
                'price' => 50000000,
                'dp' => 7000000,
                'duration' => '15 Hari',
                'description' => 'Paket umroh mewah dengan pelayanan eksklusif',
                'features' => [
                    'Tiket pesawat business class',
                    'Hotel bintang 5 premium',
                    'Makan 3x sehari',
                    'Transportasi AC private',
                    'Guide personal',
                    'Manasik premium',
                    'Full city tour',
                    'Ziarah lengkap',
                    'Shopping tour'
                ],
                'badge' => 'Premium',
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80'
            ]
        ];
    }

    private function getTestimonials(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Ahmad Wijaya',
                'location' => 'Jakarta',
                'rating' => 5,
                'comment' => 'Alhamdulillah, perjalanan umroh bersama SMB Travel sangat memuaskan. Pelayanan prima dan guide yang berpengalaman.',
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80',
                'date' => '2024-08-15'
            ],
            [
                'id' => 2,
                'name' => 'Siti Nurhaliza',
                'location' => 'Surabaya',
                'rating' => 5,
                'comment' => 'Pengalaman spiritual yang luar biasa. Terima kasih SMB Travel telah memfasilitasi ibadah umroh yang khusyuk.',
                'image' => 'https://images.unsplash.com/photo-1494790108755-2616b612b47c?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80',
                'date' => '2024-07-20'
            ],
            [
                'id' => 3,
                'name' => 'Muhammad Ridwan',
                'location' => 'Bandung',
                'rating' => 5,
                'comment' => 'Harga terjangkau dengan kualitas pelayanan yang tidak mengecewakan. Recommended!',
                'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80',
                'date' => '2024-09-10'
            ]
        ];
    }

    private function getGallery(): array
    {
        return [
            [
                'id' => 1,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
                'title' => 'Masjidil Haram dari Atas',
                'category' => 'makkah'
            ],
            [
                'id' => 2,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1519112232436-9923c6ba3d26?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
                'title' => 'Kaaba dan Jamaah',
                'category' => 'makkah'
            ],
            [
                'id' => 3,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1932&q=80',
                'title' => 'Masjid Nabawi',
                'category' => 'madinah'
            ],
            [
                'id' => 4,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
                'title' => 'Interior Masjid Indah',
                'category' => 'madinah'
            ],
            [
                'id' => 5,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=1632&q=80',
                'title' => 'Jamaah SMB Travel',
                'category' => 'group'
            ],
            [
                'id' => 6,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
                'title' => 'Grup Ibadah Bersama',
                'category' => 'group'
            ],
            [
                'id' => 7,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=1174&q=80',
                'title' => 'Perjalanan Menuju Tanah Suci',
                'category' => 'journey'
            ],
            [
                'id' => 8,
                'type' => 'image',
                'url' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80',
                'title' => 'Persiapan Keberangkatan',
                'category' => 'journey'
            ]
        ];
    }
}
