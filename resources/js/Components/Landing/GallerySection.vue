<template>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Galeri Perjalanan
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                    Saksikan dokumentasi perjalanan spiritual jamaah SMB Travel di Tanah Suci
                </p>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <button @click="setActiveFilter('all')"
                        class="px-6 py-3 rounded-full font-semibold transition-all duration-300" :class="{
                            'bg-slate-600 text-white': activeFilter === 'all',
                            'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'all'
                        }">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Semua ({{ fullGallery.length }})</span>
                        </span>
                    </button>
                    <button @click="setActiveFilter('makkah')"
                        class="px-6 py-3 rounded-full font-semibold transition-all duration-300" :class="{
                            'bg-red-600 text-white': activeFilter === 'makkah',
                            'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'makkah'
                        }">
                        <span class="flex items-center space-x-2">
                            <span>🕋</span>
                            <span>Makkah ({{ filteredGallery('makkah').length }})</span>
                        </span>
                    </button>
                    <button @click="setActiveFilter('madinah')"
                        class="px-6 py-3 rounded-full font-semibold transition-all duration-300" :class="{
                            'bg-blue-600 text-white': activeFilter === 'madinah',
                            'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'madinah'
                        }">
                        <span class="flex items-center space-x-2">
                            <span>🕌</span>
                            <span>Madinah ({{ filteredGallery('madinah').length }})</span>
                        </span>
                    </button>
                    <button @click="setActiveFilter('group')"
                        class="px-6 py-3 rounded-full font-semibold transition-all duration-300" :class="{
                            'bg-yellow-600 text-white': activeFilter === 'group',
                            'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'group'
                        }">
                        <span class="flex items-center space-x-2">
                            <span>👥</span>
                            <span>Jamaah ({{ filteredGallery('group').length }})</span>
                        </span>
                    </button>
                    <button @click="setActiveFilter('journey')"
                        class="px-6 py-3 rounded-full font-semibold transition-all duration-300" :class="{
                            'bg-slate-600 text-white': activeFilter === 'journey',
                            'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'journey'
                        }">
                        <span class="flex items-center space-x-2">
                            <span>✈️</span>
                            <span>Perjalanan ({{ filteredGallery('journey').length }})</span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="(item, index) in displayedGallery" :key="item.id" @click="openLightbox(index)"
                    class="group relative bg-gray-100 rounded-lg overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-lg"
                    :style="{ aspectRatio: getAspectRatio(index) }">
                    <!-- Cover Image -->
                    <div class="absolute inset-0">
                        <img v-if="item.coverImage" :src="item.coverImage" :alt="item.title"
                            class="w-full h-full object-cover" loading="lazy">
                        <div v-else class="bg-slate-200 w-full h-full flex items-center justify-center">
                            <div class="text-center text-slate-600">
                                <div
                                    class="w-16 h-16 bg-slate-300 rounded-lg flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold mb-2">{{ item.title }}</h4>
                                <span class="text-sm text-slate-500 uppercase tracking-wider">{{ item.category }}</span>
                            </div>
                        </div>

                        <!-- Album overlay with photo count -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="bg-black bg-opacity-60 rounded-lg p-3 text-white">
                                <h4 class="font-semibold mb-1">{{ item.title }}</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm opacity-90">{{ item.photoCount }} foto</span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                        </svg>
                                        <span class="text-xs">ALBUM</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hover Overlay -->
                    <div
                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center">
                        <div class="transform scale-0 group-hover:scale-100 transition-transform duration-200">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Load More Button -->
            <div v-if="displayedGallery.length < currentFilteredGallery.length" class="text-center mt-12">
                <button @click="loadMore"
                    class="bg-slate-600 hover:bg-slate-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors duration-200">
                    <span class="flex items-center space-x-2">
                        <span>Lihat Lebih Banyak</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 text-center">
                <div class="bg-slate-50 border border-gray-200 rounded-lg p-8">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                        Jadilah Bagian dari Perjalanan Spiritual Berikutnya
                    </h3>
                    <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                        Bergabunglah dengan ribuan jamaah yang telah merasakan pengalaman tak terlupakan bersama SMB
                        Travel
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`" target="_blank"
                            class="bg-yellow-500 hover:bg-yellow-600 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                            Konsultasi Gratis
                        </a>
                        <button @click="scrollToPackages"
                            class="bg-white border border-gray-300 hover:border-blue-600 text-slate-700 hover:text-blue-600 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                            Lihat Paket Umroh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div v-if="showLightbox" @click="closeLightbox"
            class="fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center p-4">
            <!-- Navigation Buttons - Outside Image -->
            <button v-if="canGoPrevious" @click.stop="previousImage"
                class="absolute left-8 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white transition-all duration-200 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button v-if="canGoNext" @click.stop="nextImage"
                class="absolute right-8 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white transition-all duration-200 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="relative max-w-4xl max-h-full" @click.stop>
                <!-- Close Button -->
                <button @click.stop="closeLightbox"
                    class="absolute -top-12 right-0 w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Content -->
                <div class="bg-white rounded-lg overflow-hidden">
                    <!-- Media Display -->
                    <div class="aspect-video bg-slate-100 flex items-center justify-center">
                        <img v-if="currentLightboxItem.url" :src="currentLightboxItem.url"
                            :alt="currentLightboxItem.title" class="w-full h-full object-contain">
                        <div v-else class="text-center text-slate-600">
                            <div
                                class="w-24 h-24 bg-slate-200 rounded-lg flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ currentLightboxItem.title }}</h3>
                            <p class="text-slate-500">Loading...</p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-slate-900">{{ currentLightboxItem.title }}</h3>
                            <span class="text-sm text-slate-500">
                                {{ currentLightboxItem.currentPhotoNumber }} / {{ currentLightboxItem.totalPhotos }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-block bg-slate-100 text-slate-700 text-sm font-medium px-3 py-1 rounded-full">
                                {{ getCategoryLabel(currentLightboxItem.category) }}
                            </span>
                            <span class="text-sm text-slate-600">Album: {{ currentLightboxItem.albumTitle }}</span>
                        </div>
                    </div>
                </div>

                <!-- Counter -->
                <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 text-white text-sm text-center">
                    <div>Foto {{ currentLightboxItem.currentPhotoNumber }} dari {{ currentLightboxItem.totalPhotos }}</div>
                    <div class="text-xs opacity-75 mt-1">Album {{ currentAlbumIndex + 1 }} dari {{ displayedGallery.length }}</div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { BRAND } from '@/Utils/constants.js'

const props = defineProps({
    gallery: {
        type: Array,
        required: true,
        default: () => []
    }
})

// Album gallery data - each album contains multiple photos
const dummyGallery = [
    {
        id: 1,
        title: 'Masjidil Haram dari Atas',
        category: 'makkah',
        type: 'album',
        coverImage: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        photoCount: 15,
        photos: [
            {
                id: 101,
                url: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Pemandangan Kaaba dari Atas'
            },
            {
                id: 102,
                url: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah Sedang Tawaf'
            },
            {
                id: 103,
                url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Masjidil Haram Siang Hari'
            },
            {
                id: 104,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Pemandangan Malam Hari'
            },
            {
                id: 105,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Kepadatan Jamaah'
            },
            {
                id: 106,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Area Sholat'
            },
            {
                id: 107,
                url: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah di Area Tawaf'
            },
            {
                id: 108,
                url: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Pemandangan Luas Masjidil Haram'
            },
            {
                id: 109,
                url: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Cahaya Matahari Pagi'
            },
            {
                id: 110,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah Berdoa'
            },
            {
                id: 111,
                url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Detail Arsitektur Masjid'
            },
            {
                id: 112,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Menara Jam Makkah'
            },
            {
                id: 113,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Saf Jamaah yang Rapi'
            },
            {
                id: 114,
                url: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Panorama Masjidil Haram'
            },
            {
                id: 115,
                url: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Tawaf Saat Subuh'
            }
        ]
    },
    {
        id: 2,
        title: 'Jamaah SMB Travel',
        category: 'group',
        type: 'album',
        coverImage: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        photoCount: 12,
        photos: [
            {
                id: 201,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Foto Bersama di Masjid Nabawi'
            },
            {
                id: 202,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah Saat Manasik'
            },
            {
                id: 203,
                url: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Makan Bersama'
            },
            {
                id: 204,
                url: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Foto di Hotel'
            },
            {
                id: 205,
                url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah di Bus'
            },
            {
                id: 206,
                url: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Foto Kebersamaan'
            },
            {
                id: 207,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah di Bandara'
            },
            {
                id: 208,
                url: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Briefing sebelum Umroh'
            },
            {
                id: 209,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah di Lobby Hotel'
            },
            {
                id: 210,
                url: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Group Photo di Makkah'
            },
            {
                id: 211,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah Istirahat'
            },
            {
                id: 212,
                url: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Foto Bersama Muthowif'
            }
        ]
    },
    {
        id: 3,
        title: 'Perjalanan ke Tanah Suci',
        category: 'journey',
        type: 'album',
        coverImage: 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        photoCount: 10,
        photos: [
            {
                id: 301,
                url: 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Keberangkatan dari Bandara'
            },
            {
                id: 302,
                url: 'https://images.unsplash.com/photo-1500835556837-99ac94a94552?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Dalam Pesawat'
            },
            {
                id: 303,
                url: 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Tiba di Jeddah'
            },
            {
                id: 304,
                url: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Check In Hotel'
            },
            {
                id: 305,
                url: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Persiapan Umroh'
            },
            {
                id: 306,
                url: 'https://images.unsplash.com/photo-1549144511-f099e773c147?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Bus Menuju Makkah'
            },
            {
                id: 307,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Pemandangan dari Bus'
            },
            {
                id: 308,
                url: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Rest Area Perjalanan'
            },
            {
                id: 309,
                url: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Sampai di Makkah'
            },
            {
                id: 310,
                url: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Melihat Masjidil Haram Pertama Kali'
            }
        ]
    },
    {
        id: 4,
        title: 'Masjid Nabawi',
        category: 'madinah',
        type: 'album',
        coverImage: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        photoCount: 13,
        photos: [
            {
                id: 401,
                url: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Kubah Hijau Masjid Nabawi'
            },
            {
                id: 402,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah Sholat Berjamaah'
            },
            {
                id: 403,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Area Raudhah'
            },
            {
                id: 404,
                url: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Pemandangan Masjid dari Luar'
            },
            {
                id: 405,
                url: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah di Halaman Masjid'
            },
            {
                id: 406,
                url: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Sholat Malam'
            },
            {
                id: 407,
                url: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Ziarah Makam Rasul'
            },
            {
                id: 408,
                url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Pintu Masuk Masjid'
            },
            {
                id: 409,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Jamaah Berdoa di Raudhah'
            },
            {
                id: 410,
                url: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Arsitektur Masjid Nabawi'
            },
            {
                id: 411,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Halaman Masjid Nabawi'
            },
            {
                id: 412,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Sholat Subuh Berjamaah'
            },
            {
                id: 413,
                url: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Keindahan Masjid di Sore Hari'
            }
        ]
    },
    {
        id: 5,
        title: 'Fasilitas Hotel',
        category: 'journey',
        type: 'album',
        coverImage: 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        photoCount: 8,
        photos: [
            {
                id: 501,
                url: 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Lobby Hotel Makkah'
            },
            {
                id: 502,
                url: 'https://images.unsplash.com/photo-1500835556837-99ac94a94552?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Kamar Hotel yang Nyaman'
            },
            {
                id: 503,
                url: 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Restaurant Hotel'
            },
            {
                id: 504,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'View dari Kamar Hotel'
            },
            {
                id: 505,
                url: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Fasilitas Buffet'
            },
            {
                id: 506,
                url: 'https://images.unsplash.com/photo-1549144511-f099e773c147?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Area Istirahat'
            },
            {
                id: 507,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Musholla Hotel'
            },
            {
                id: 508,
                url: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Lobby Hotel Madinah'
            }
        ]
    },
    {
        id: 6,
        title: 'Kegiatan Spiritual',
        category: 'makkah',
        type: 'album',
        coverImage: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        photoCount: 9,
        photos: [
            {
                id: 601,
                url: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Tawaf Perdana'
            },
            {
                id: 602,
                url: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Sai Antara Shofa dan Marwa'
            },
            {
                id: 603,
                url: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Minum Air Zamzam'
            },
            {
                id: 604,
                url: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Berdo\'a di Multazam'
            },
            {
                id: 605,
                url: 'https://images.unsplash.com/photo-1590736969955-71cc94901144?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Istighosah Bersama'
            },
            {
                id: 606,
                url: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Tahajud di Masjidil Haram'
            },
            {
                id: 607,
                url: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Dzikir Pagi'
            },
            {
                id: 608,
                url: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Manasik Haji'
            },
            {
                id: 609,
                url: 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                title: 'Tawaf Wada'
            }
        ]
    }
]

// Merge with existing gallery data
const fullGallery = computed(() => {
    return [...(props.gallery || []), ...dummyGallery]
})

const activeFilter = ref('all')
const showLightbox = ref(false)
const currentLightboxIndex = ref(0)
const currentAlbumIndex = ref(0)
const currentPhotoIndex = ref(0)
const itemsPerPage = 8
const currentPage = ref(1)

const filteredGallery = (category) => {
    if (category === 'all') return fullGallery.value
    return fullGallery.value.filter(item => item.category === category)
}

const currentFilteredGallery = computed(() => {
    return filteredGallery(activeFilter.value)
})

const displayedGallery = computed(() => {
    const endIndex = currentPage.value * itemsPerPage
    return currentFilteredGallery.value.slice(0, endIndex)
})

const currentLightboxItem = computed(() => {
    const album = displayedGallery.value[currentAlbumIndex.value]
    if (!album || !album.photos) return {}

    const photo = album.photos[currentPhotoIndex.value]
    return {
        ...photo,
        albumTitle: album.title,
        category: album.category,
        totalPhotos: album.photos.length,
        currentPhotoNumber: currentPhotoIndex.value + 1
    }
})

const canGoPrevious = computed(() => {
    return currentPhotoIndex.value > 0 || currentAlbumIndex.value > 0
})

const canGoNext = computed(() => {
    const currentAlbum = displayedGallery.value[currentAlbumIndex.value]
    if (!currentAlbum || !currentAlbum.photos) return false

    const isLastPhotoInAlbum = currentPhotoIndex.value >= currentAlbum.photos.length - 1
    const isLastAlbum = currentAlbumIndex.value >= displayedGallery.value.length - 1

    return !isLastPhotoInAlbum || !isLastAlbum
})

const setActiveFilter = (filter) => {
    activeFilter.value = filter
    currentPage.value = 1
}

const loadMore = () => {
    currentPage.value += 1
}

const getAspectRatio = (index) => {
    // Create varied aspect ratios for masonry effect
    const ratios = ['1/1', '4/3', '3/4', '16/9', '1/1', '4/3']
    return ratios[index % ratios.length]
}

const openLightbox = (index) => {
    currentAlbumIndex.value = index
    currentPhotoIndex.value = 0
    currentLightboxIndex.value = index
    showLightbox.value = true
    document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
    showLightbox.value = false
    document.body.style.overflow = 'auto'
}

const nextImage = () => {
    const currentAlbum = displayedGallery.value[currentAlbumIndex.value]
    if (!currentAlbum || !currentAlbum.photos) return

    // Navigate within current album
    if (currentPhotoIndex.value < currentAlbum.photos.length - 1) {
        currentPhotoIndex.value += 1
    } else {
        // Move to next album if available
        if (currentAlbumIndex.value < displayedGallery.value.length - 1) {
            currentAlbumIndex.value += 1
            currentPhotoIndex.value = 0
            currentLightboxIndex.value = currentAlbumIndex.value
        }
    }
}

const previousImage = () => {
    // Navigate within current album
    if (currentPhotoIndex.value > 0) {
        currentPhotoIndex.value -= 1
    } else {
        // Move to previous album if available
        if (currentAlbumIndex.value > 0) {
            currentAlbumIndex.value -= 1
            const prevAlbum = displayedGallery.value[currentAlbumIndex.value]
            if (prevAlbum && prevAlbum.photos) {
                currentPhotoIndex.value = prevAlbum.photos.length - 1
                currentLightboxIndex.value = currentAlbumIndex.value
            }
        }
    }
}

const getCategoryLabel = (category) => {
    const labels = {
        'makkah': '🕋 Makkah',
        'madinah': '🕌 Madinah',
        'group': '👥 Jamaah',
        'journey': '✈️ Perjalanan'
    }
    return labels[category] || category
}

const scrollToPackages = () => {
    const packagesSection = document.getElementById('packages-section')
    if (packagesSection) {
        packagesSection.scrollIntoView({ behavior: 'smooth' })
    }
}

// Keyboard navigation for lightbox
const handleKeydown = (event) => {
    if (!showLightbox.value) return

    switch (event.key) {
        case 'ArrowLeft':
            previousImage()
            break
        case 'ArrowRight':
            nextImage()
            break
        case 'Escape':
            closeLightbox()
            break
    }
}

onMounted(() => {
    document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = 'auto'
})
</script>
