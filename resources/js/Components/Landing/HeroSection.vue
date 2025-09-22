<template>
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')">
        </div>

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/85 via-slate-800/80 to-slate-700/75"></div>

        <!-- Pattern Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="space-y-8">
                <!-- Main Headline -->
                <div class="space-y-6">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                        Umroh Terpercaya & Terjangkau
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-200 max-w-2xl mx-auto">
                        Nikmati perjalanan spiritual yang aman dan nyaman dengan bimbingan ahli berpengalaman
                    </p>
                </div>

                <!-- Countdown Section -->
                <div
                    class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 sm:p-8 max-w-4xl mx-auto border border-white border-opacity-20">
                    <div class="space-y-6">
                        <!-- Promo Title -->
                        <div class="space-y-2">
                            <h2 class="text-2xl sm:text-4xl font-bold text-yellow-400">
                                {{ countdown.title }}
                            </h2>
                            <p class="text-gray-200 text-lg">
                                {{ countdown.description }}
                            </p>
                        </div>

                        <!-- Countdown Timer -->
                        <div v-if="!isExpired" class="grid grid-cols-4 gap-4 sm:gap-6">
                            <div class="bg-red-600 rounded-xl p-4 sm:p-6 text-center border border-red-500">
                                <div class="text-2xl sm:text-4xl font-bold text-white">{{ days.toString().padStart(2,
                                    '0') }}</div>
                                <div class="text-red-100 text-sm sm:text-base font-medium">Hari</div>
                            </div>
                            <div class="bg-blue-600 rounded-xl p-4 sm:p-6 text-center border border-blue-500">
                                <div class="text-2xl sm:text-4xl font-bold text-white">{{ hours.toString().padStart(2,
                                    '0') }}</div>
                                <div class="text-blue-100 text-sm sm:text-base font-medium">Jam</div>
                            </div>
                            <div class="bg-yellow-600 rounded-xl p-4 sm:p-6 text-center border border-yellow-500">
                                <div class="text-2xl sm:text-4xl font-bold text-white">{{ minutes.toString().padStart(2,
                                    '0') }}</div>
                                <div class="text-yellow-100 text-sm sm:text-base font-medium">Menit</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 sm:p-6 text-center border border-gray-300">
                                <div class="text-2xl sm:text-4xl font-bold text-slate-900">{{
                                    seconds.toString().padStart(2, '0') }}</div>
                                <div class="text-slate-600 text-sm sm:text-base font-medium">Detik</div>
                            </div>
                        </div>

                        <!-- Expired Message -->
                        <div v-else class="text-center">
                            <h3 class="text-3xl font-bold text-red-400 mb-2">Promo Berakhir!</h3>
                            <p class="text-gray-200">Tapi jangan khawatir, masih ada paket menarik lainnya untuk Anda.
                            </p>
                        </div>

                        <!-- Key Points -->
                        <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-200">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                DP mulai 3 juta
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                                Berizin Kemenag
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                                Berpengalaman 10+ tahun
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link :href="route('jamaah.daftar')"
                        class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                    Daftar Sekarang
                    </Link>

                    <a :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`" target="_blank"
                        class="px-8 py-3 bg-yellow-500 hover:bg-yellow-600 text-slate-900 font-semibold rounded-lg transition-colors duration-200">
                        Konsultasi Gratis
                    </a>

                    <button @click="scrollToPackages"
                        class="px-8 py-3 bg-white hover:bg-gray-100 text-slate-900 font-semibold rounded-lg transition-colors duration-200">
                        Lihat Paket
                    </button>
                </div>

                <!-- Scroll Indicator -->
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                    <button @click="scrollToPackages"
                        class="text-white opacity-80 hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { useCountdown } from '@/Composables/useCountdown.js'
import { BRAND } from '@/Utils/constants.js'

const props = defineProps({
    countdown: {
        type: Object,
        required: true
    }
})

const { days, hours, minutes, seconds, isExpired } = useCountdown(props.countdown.deadline)

const scrollToPackages = () => {
    const packagesSection = document.getElementById('packages-section')
    if (packagesSection) {
        packagesSection.scrollIntoView({ behavior: 'smooth' })
    }
}
</script>
