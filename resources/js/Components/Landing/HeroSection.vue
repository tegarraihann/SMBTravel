<template>
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div
      class="absolute inset-0 bg-cover bg-center bg-no-repeat"
      style="background-image: url('https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')"
    ></div>

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-blue-700/70 to-red-600/80"></div>

    <!-- Pattern Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="space-y-8">
        <!-- Main Headline -->
        <div class="space-y-4">
          <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-white leading-tight">
            <span class="block">Wujudkan</span>
            <span class="block bg-gradient-to-r from-yellow-400 to-yellow-300 bg-clip-text text-transparent">
              Impian Umroh
            </span>
            <span class="block">Anda</span>
          </h1>
          <p class="text-xl sm:text-2xl text-gray-200 max-w-3xl mx-auto leading-relaxed">
            Bergabunglah dengan ribuan jamaah yang telah merasakan pengalaman spiritual bersama
            <span class="text-yellow-400 font-semibold">{{ BRAND.name }}</span>
          </p>
        </div>

        <!-- Countdown Section -->
        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 sm:p-8 max-w-4xl mx-auto border border-white border-opacity-20">
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
              <div class="bg-red-600 rounded-xl p-4 sm:p-6 text-center border-2 border-red-400">
                <div class="text-2xl sm:text-4xl font-bold text-white">{{ days.toString().padStart(2, '0') }}</div>
                <div class="text-red-100 text-sm sm:text-base font-medium">Hari</div>
              </div>
              <div class="bg-blue-600 rounded-xl p-4 sm:p-6 text-center border-2 border-blue-400">
                <div class="text-2xl sm:text-4xl font-bold text-white">{{ hours.toString().padStart(2, '0') }}</div>
                <div class="text-blue-100 text-sm sm:text-base font-medium">Jam</div>
              </div>
              <div class="bg-yellow-600 rounded-xl p-4 sm:p-6 text-center border-2 border-yellow-400">
                <div class="text-2xl sm:text-4xl font-bold text-white">{{ minutes.toString().padStart(2, '0') }}</div>
                <div class="text-yellow-100 text-sm sm:text-base font-medium">Menit</div>
              </div>
              <div class="bg-green-600 rounded-xl p-4 sm:p-6 text-center border-2 border-green-400">
                <div class="text-2xl sm:text-4xl font-bold text-white">{{ seconds.toString().padStart(2, '0') }}</div>
                <div class="text-green-100 text-sm sm:text-base font-medium">Detik</div>
              </div>
            </div>

            <!-- Expired Message -->
            <div v-else class="text-center">
              <h3 class="text-3xl font-bold text-red-400 mb-2">Promo Berakhir!</h3>
              <p class="text-gray-200">Tapi jangan khawatir, masih ada paket menarik lainnya untuk Anda.</p>
            </div>

            <!-- Features Highlight -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
              <div class="space-y-2">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                  </svg>
                </div>
                <h4 class="font-semibold text-white">DP Terjangkau</h4>
                <p class="text-gray-200 text-sm">Mulai dari 3 juta saja</p>
              </div>
              <div class="space-y-2">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <h4 class="font-semibold text-white">Terpercaya</h4>
                <p class="text-gray-200 text-sm">Berizin resmi Kemenag</p>
              </div>
              <div class="space-y-2">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                </div>
                <h4 class="font-semibold text-white">Berpengalaman</h4>
                <p class="text-gray-200 text-sm">Puluhan tahun melayani</p>
              </div>
            </div>
          </div>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <Link
            href="/daftar"
            class="group relative px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold text-lg rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
          >
            <span class="relative z-10 flex items-center space-x-2">
              <span>Daftar Sekarang</span>
              <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-red-500 rounded-full opacity-0 group-hover:opacity-20 transition-opacity"></div>
          </Link>

          <a
            :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
            target="_blank"
            class="group px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-lg rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
          >
            <span class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
              <span>Chat WhatsApp</span>
            </span>
          </a>

          <button
            @click="scrollToPackages"
            class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-blue-900 font-bold text-lg rounded-full transition-all duration-300"
          >
            Lihat Paket
          </button>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <button @click="scrollToPackages" class="text-white opacity-80 hover:opacity-100 transition-opacity">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 bg-blue-400 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 right-20 w-12 h-12 bg-red-400 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
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