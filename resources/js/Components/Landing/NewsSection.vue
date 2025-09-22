<template>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Berita & Update Terkini
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Ikuti perkembangan terbaru keberangkatan, kepulangan, dan aktivitas jamaah SMB Travel
                </p>
            </div>

            <!-- Filter Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button v-for="category in categories" :key="category.id" @click="setActiveCategory(category.id)"
                    class="px-6 py-3 rounded-full font-semibold transition-all duration-300" :class="{
                        'bg-blue-600 text-white': activeCategory === category.id,
                        'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== category.id
                    }">
                    <span class="flex items-center space-x-2">
                        <span>{{ category.icon }}</span>
                        <span>{{ category.name }}</span>
                        <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full">
                            {{ getNewsCount(category.id) }}
                        </span>
                    </span>
                </button>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <article v-for="news in filteredNews" :key="news.id"
                    class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200 cursor-pointer"
                    @click="openNewsDetail(news)">
                    <!-- News Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img :src="news.image" :alt="news.title" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full text-white" :class="{
                                'bg-blue-600': news.category === 'keberangkatan',
                                'bg-red-600': news.category === 'kepulangan',
                                'bg-yellow-600': news.category === 'aktivitas',
                                'bg-slate-600': news.category === 'pengumuman'
                            }">
                                {{ getCategoryName(news.category) }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4">
                            <span class="bg-black bg-opacity-60 text-white px-2 py-1 rounded text-xs">
                                {{ formatDate(news.date) }}
                            </span>
                        </div>
                    </div>

                    <!-- News Content -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ news.title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ news.excerpt }}
                        </p>

                        <!-- News Meta -->
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ formatTime(news.date) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ news.views || 0 }} views</span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Load More Button -->
            <div v-if="hasMoreNews" class="text-center mt-12">
                <button @click="loadMoreNews"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200">
                    Muat Berita Lainnya
                </button>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 text-center">
                <div class="bg-slate-50 border border-gray-200 rounded-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Ingin Mendapat Update Langsung?
                    </h3>
                    <p class="text-lg text-gray-600 mb-6 max-w-xl mx-auto">
                        Bergabung dengan grup WhatsApp kami untuk mendapat update real-time tentang perjalanan umroh
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <!-- <a :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`" target="_blank"
                            class="bg-yellow-500 hover:bg-yellow-600 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                            📱 Join Grup WhatsApp
                        </a> -->
                        <button @click="subscribeNewsletter"
                            class="bg-white border border-gray-300 hover:border-blue-600 text-slate-700 hover:text-blue-600 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                            📧 Subscribe Newsletter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Detail Modal -->
        <div v-if="showNewsModal" @click="closeNewsDetail"
            class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div @click.stop class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="relative">
                    <button @click="closeNewsDetail"
                        class="absolute top-4 right-4 w-8 h-8 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full flex items-center justify-center text-white z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <img v-if="selectedNews?.image" :src="selectedNews.image" :alt="selectedNews.title"
                        class="w-full h-64 object-cover">
                </div>

                <!-- Modal Content -->
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full text-white" :class="{
                            'bg-blue-600': selectedNews?.category === 'keberangkatan',
                            'bg-red-600': selectedNews?.category === 'kepulangan',
                            'bg-yellow-600': selectedNews?.category === 'aktivitas',
                            'bg-slate-600': selectedNews?.category === 'pengumuman'
                        }">
                            {{ getCategoryName(selectedNews?.category) }}
                        </span>
                        <span class="text-gray-500 text-sm">
                            {{ formatDate(selectedNews?.date) }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ selectedNews?.title }}
                    </h2>

                    <div class="prose max-w-none text-gray-700">
                        <p v-for="paragraph in selectedNews?.content" :key="paragraph" class="mb-4">
                            {{ paragraph }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { BRAND } from '@/Utils/constants.js'

const props = defineProps({
    news: {
        type: Array,
        required: false,
        default: () => []
    }
})

// Categories
const categories = ref([
    { id: 'all', name: 'Semua', icon: '📰' },
    { id: 'keberangkatan', name: 'Keberangkatan', icon: '✈️' },
    { id: 'kepulangan', name: 'Kepulangan', icon: '🏠' },
    { id: 'aktivitas', name: 'Aktivitas', icon: '📸' },
    { id: 'pengumuman', name: 'Pengumuman', icon: '📢' }
])

// State
const activeCategory = ref('all')
const showNewsModal = ref(false)
const selectedNews = ref(null)
const currentPage = ref(1)
const itemsPerPage = 6

// Dummy news data
const dummyNews = ref([
    {
        id: 1,
        title: 'Keberangkatan Jamaah Umroh Gelombang 1 Tahun 2024',
        excerpt: 'Sebanyak 45 jamaah umroh SMB Travel berangkat menuju Tanah Suci pada hari ini dengan penuh semangat dan harapan.',
        content: [
            'Alhamdulillah, pada hari ini Selasa, 15 Januari 2024, sebanyak 45 jamaah umroh SMB Travel telah berangkat menuju Tanah Suci dengan penuh semangat dan harapan.',
            'Keberangkatan dilaksanakan dari Bandara Internasional Sultan Syarif Kasim II Pekanbaru pukul 08.00 WIB dengan tujuan pertama Kuala Lumpur, Malaysia.',
            'Para jamaah terlihat antusias dan siap menjalani ibadah umroh dengan bimbingan ustadz yang berpengalaman. Semua persiapan telah dilakukan dengan matang.',
            'Kami mendoakan semoga perjalanan jamaah berjalan lancar dan ibadah umroh dapat diterima oleh Allah SWT. Insya Allah akan ada update berkala selama perjalanan.'
        ],
        category: 'keberangkatan',
        date: new Date('2024-01-15T08:00:00'),
        image: 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 234
    },
    {
        id: 2,
        title: 'Kepulangan Jamaah Umroh Gelombang 1 dengan Selamat',
        excerpt: 'Alhamdulillah, seluruh jamaah umroh gelombang 1 telah tiba dengan selamat di Pekanbaru setelah menyelesaikan ibadah umroh.',
        content: [
            'Alhamdulillah, pada hari Jumat, 26 Januari 2024, seluruh jamaah umroh gelombang 1 SMB Travel telah tiba dengan selamat di Bandara Sultan Syarif Kasim II Pekanbaru.',
            'Para jamaah terlihat berseri-seri dan penuh syukur setelah menunaikan ibadah umroh selama 12 hari di Tanah Suci.',
            'Selama perjalanan, jamaah mengunjungi berbagai tempat bersejarah di Makkah dan Madinah, serta melaksanakan tawaf, sa\'i, dan ziarah dengan khidmat.',
            'Terima kasih kepada seluruh jamaah yang telah mempercayakan perjalanan umrohnya kepada SMB Travel. Semoga ibadah yang telah dilaksanakan diterima Allah SWT.'
        ],
        category: 'kepulangan',
        date: new Date('2024-01-26T16:30:00'),
        image: 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 189
    },
    {
        id: 3,
        title: 'Aktivitas Jamaah di Masjidil Haram: Tawaf Malam yang Khidmat',
        excerpt: 'Dokumentasi kegiatan tawaf malam jamaah SMB Travel di Masjidil Haram yang penuh dengan kekhusyukan.',
        content: [
            'Subhanallah, malam ini jamaah SMB Travel melaksanakan tawaf malam di Masjidil Haram dengan penuh kekhusyukan.',
            'Suasana malam di Masjidil Haram sangat berbeda, lebih tenang dan khusyuk. Para jamaah dapat melaksanakan tawaf dengan lebih nyaman.',
            'Bimbingan dari ustadz sangat membantu jamaah dalam memahami adab dan doa-doa selama tawaf.',
            'Momen ini menjadi salah satu pengalaman paling berkesan bagi para jamaah dalam perjalanan umroh mereka.'
        ],
        category: 'aktivitas',
        date: new Date('2024-01-20T22:00:00'),
        image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 156
    },
    {
        id: 4,
        title: 'Pengumuman: Pembukaan Pendaftaran Umroh Gelombang 2',
        excerpt: 'SMB Travel membuka pendaftaran untuk jamaah umroh gelombang 2 dengan keberangkatan Maret 2024.',
        content: [
            'Assalamualaikum warahmatullahi wabarakatuh, SMB Travel dengan bangga mengumumkan pembukaan pendaftaran umroh gelombang 2.',
            'Keberangkatan dijadwalkan pada bulan Maret 2024 dengan paket yang lebih lengkap dan fasilitas yang ditingkatkan.',
            'Tersedia berbagai pilihan paket sesuai kebutuhan dan budget jamaah. Early bird discount tersedia untuk pendaftar pertama.',
            'Untuk informasi lebih lanjut dan pendaftaran, silakan hubungi customer service kami atau datang langsung ke kantor.'
        ],
        category: 'pengumuman',
        date: new Date('2024-01-30T10:00:00'),
        image: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 312
    },
    {
        id: 5,
        title: 'Ziarah ke Masjid Nabawi: Momen Spiritual yang Tak Terlupakan',
        excerpt: 'Jamaah SMB Travel melaksanakan ziarah ke Masjid Nabawi dan berkesempatan sholat di Raudhah.',
        content: [
            'Alhamdulillah, hari ini jamaah SMB Travel berkesempatan melaksanakan ziarah ke Masjid Nabawi, Madinah.',
            'Para jamaah sangat antusias dan terharu dapat berada di masjid yang mulia ini, tempat dimakamkannya Rasulullah SAW.',
            'Beberapa jamaah berkesempatan sholat di area Raudhah yang merupakan taman surga di dunia.',
            'Pengalaman spiritual ini menjadi pelengkap yang sempurna dalam perjalanan umroh para jamaah.'
        ],
        category: 'aktivitas',
        date: new Date('2024-01-22T14:30:00'),
        image: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 198
    },
    {
        id: 6,
        title: 'Update Perjalanan: Jamaah Tiba Safely di Madinah',
        excerpt: 'Update perjalanan jamaah SMB Travel yang telah tiba dengan selamat di Madinah Al-Munawwarah.',
        content: [
            'Bismillah, kami informasikan bahwa jamaah SMB Travel telah tiba dengan selamat di Madinah Al-Munawwarah.',
            'Perjalanan dari Makkah ke Madinah berjalan lancar dan semua jamaah dalam kondisi sehat dan prima.',
            'Jamaah sudah check-in di hotel dan bersiap untuk melaksanakan rangkaian ibadah di Madinah.',
            'Jadwal kegiatan di Madinah sudah disiapkan dengan baik oleh tim tour leader kami.'
        ],
        category: 'keberangkatan',
        date: new Date('2024-01-18T18:00:00'),
        image: 'https://images.unsplash.com/photo-1583422409516-2895a77efded?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 145
    },
    {
        id: 7,
        title: 'Promo Spesial Ramadan: Diskon 15% untuk Paket Umroh',
        excerpt: 'Menyambut bulan suci Ramadan, SMB Travel memberikan promo khusus dengan diskon hingga 15% untuk semua paket umroh.',
        content: [
            'Assalamualaikum warahmatullahi wabarakatuh. Dalam rangka menyambut bulan suci Ramadan 1445 H, SMB Travel dengan senang hati mengumumkan promo spesial.',
            'Promo Ramadan ini memberikan diskon hingga 15% untuk semua paket umroh dengan keberangkatan April - Juni 2024.',
            'Selain diskon, jamaah juga mendapat bonus perlengkapan umroh eksklusif dan bimbingan manasik gratis.',
            'Promo terbatas hanya untuk 100 jamaah pertama. Buruan daftar sebelum kehabisan! Hubungi customer service kami sekarang juga.',
            'Syarat dan ketentuan berlaku. Promo tidak dapat digabung dengan promo lainnya.'
        ],
        category: 'pengumuman',
        date: new Date('2024-02-05T09:00:00'),
        image: 'https://images.unsplash.com/photo-1609599006387-ef2e4e5e7888?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 425
    },
    {
        id: 8,
        title: 'City Tour Eksklusif: Jamaah Mengunjungi Jembatan Kereta Cepat Haramain',
        excerpt: 'Pengalaman luar biasa jamaah SMB Travel yang berkesempatan naik kereta cepat Haramain Express dari Makkah ke Madinah.',
        content: [
            'Subhanallah, hari ini jamaah SMB Travel mendapat pengalaman yang tak terlupakan dengan naik kereta cepat Haramain Express.',
            'Kereta cepat ini menghubungkan Makkah dan Madinah dengan kecepatan hingga 300 km/jam, mempersingkat perjalanan menjadi hanya 2 jam.',
            'Para jamaah sangat antusias dan takjub dengan teknologi modern yang memudahkan perjalanan antar kota suci.',
            'Fasilitas kereta sangat nyaman dengan AC, wifi gratis, dan pemandangan gurun yang menakjubkan sepanjang perjalanan.',
            'Ini merupakan salah satu highlight dari paket Premium Plus yang ditawarkan SMB Travel.'
        ],
        category: 'aktivitas',
        date: new Date('2024-02-01T11:30:00'),
        image: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 287
    },
    {
        id: 9,
        title: 'Keberangkatan Jamaah Umroh Plus Thaif: Petualangan Spiritual Dimulai',
        excerpt: 'Jamaah umroh paket Plus Thaif berangkat hari ini dengan itinerary spesial mengunjungi kota Thaif yang sejuk dan bersejarah.',
        content: [
            'Bismillahirrahmanirrahim. Hari ini, 35 jamaah umroh paket Plus Thaif SMB Travel berangkat dengan penuh semangat dan doa.',
            'Paket istimewa ini tidak hanya mencakup ibadah umroh di Makkah dan Madinah, tetapi juga city tour ke kota Thaif yang bersejarah.',
            'Thaif dikenal sebagai kota yang sejuk dengan pemandangan alam yang indah, serta memiliki nilai sejarah penting dalam Islam.',
            'Jamaah akan mengunjungi Masjid Abdullah bin Abbas, kebun anggur, dan tempat bersejarah lainnya di Thaif.',
            'Semoga perjalanan jamaah berjalan lancar dan ibadah yang dilaksanakan mendapat ridho Allah SWT. Aamiin ya rabbal alamiin.'
        ],
        category: 'keberangkatan',
        date: new Date('2024-02-03T07:00:00'),
        image: 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        views: 198
    }
])

// Computed
const allNews = computed(() => {
    return [...(props.news || []), ...dummyNews.value].sort((a, b) => new Date(b.date) - new Date(a.date))
})

const filteredNews = computed(() => {
    const filtered = activeCategory.value === 'all'
        ? allNews.value
        : allNews.value.filter(news => news.category === activeCategory.value)

    return filtered.slice(0, currentPage.value * itemsPerPage)
})

const hasMoreNews = computed(() => {
    const filtered = activeCategory.value === 'all'
        ? allNews.value
        : allNews.value.filter(news => news.category === activeCategory.value)

    return filtered.length > currentPage.value * itemsPerPage
})

// Methods
const setActiveCategory = (categoryId) => {
    activeCategory.value = categoryId
    currentPage.value = 1
}

const getNewsCount = (categoryId) => {
    if (categoryId === 'all') return allNews.value.length
    return allNews.value.filter(news => news.category === categoryId).length
}

const getCategoryName = (categoryId) => {
    const category = categories.value.find(cat => cat.id === categoryId)
    return category ? category.name : categoryId
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

const openNewsDetail = (news) => {
    selectedNews.value = news
    showNewsModal.value = true
    document.body.style.overflow = 'hidden'
}

const closeNewsDetail = () => {
    selectedNews.value = null
    showNewsModal.value = false
    document.body.style.overflow = 'auto'
}

const loadMoreNews = () => {
    currentPage.value += 1
}

const subscribeNewsletter = () => {
    alert('Fitur newsletter akan segera tersedia!')
}

// Functions for manual manipulation (accessible via console)
const addNews = (newsItem) => {
    const maxId = Math.max(...dummyNews.value.map(n => n.id), 0)
    dummyNews.value.unshift({ id: maxId + 1, ...newsItem, date: new Date() })
}

const updateNews = (id, updates) => {
    const newsIndex = dummyNews.value.findIndex(n => n.id === id)
    if (newsIndex !== -1) {
        dummyNews.value[newsIndex] = { ...dummyNews.value[newsIndex], ...updates }
    }
}

const removeNews = (id) => {
    const index = dummyNews.value.findIndex(n => n.id === id)
    if (index !== -1) {
        dummyNews.value.splice(index, 1)
    }
}

// Expose to global for manual control
if (typeof window !== 'undefined') {
    window.newsManager = {
        news: dummyNews,
        addNews,
        updateNews,
        removeNews,
        // Example usage:
        // window.newsManager.addNews({ title: 'Berita Baru', excerpt: 'Lorem ipsum...', category: 'pengumuman', content: ['Content...'], image: 'url' })
        // window.newsManager.updateNews(1, { title: 'Updated Title' })
        // window.newsManager.removeNews(1)
    }
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.7;
}
</style>
