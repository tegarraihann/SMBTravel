<template>
  <section id="packages-section" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
          Paket Umroh
          <span class="bg-gradient-to-r from-red-600 to-blue-600 bg-clip-text text-transparent">
            Terbaik
          </span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Pilih paket yang sesuai dengan kebutuhan spiritual Anda. Semua paket sudah termasuk
          <span class="font-semibold text-blue-600">visa, tiket, hotel, dan makan</span>
        </p>
      </div>

      <!-- Packages Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-6">
        <div
          v-for="(pkg, index) in packages"
          :key="pkg.id"
          class="relative bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl"
          :class="{
            'ring-4 ring-yellow-400 ring-opacity-60': pkg.badge === 'Best Seller',
            'lg:scale-110 lg:z-10': pkg.badge === 'Popular'
          }"
        >
          <!-- Badge -->
          <div class="absolute top-6 left-6 z-20">
            <span
              class="inline-block px-4 py-2 text-sm font-bold rounded-full"
              :class="{
                'bg-red-500 text-white': pkg.badge === 'Best Seller',
                'bg-blue-500 text-white': pkg.badge === 'Popular',
                'bg-yellow-500 text-white': pkg.badge === 'Premium'
              }"
            >
              {{ pkg.badge }}
            </span>
          </div>

          <!-- Package Image -->
          <div class="relative h-48 overflow-hidden">
            <img
              :src="pkg.image"
              :alt="pkg.name"
              class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/60 to-red-600/60"></div>
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="text-center text-white">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </div>
                <h3 class="text-2xl font-bold">{{ pkg.name }}</h3>
                <p class="text-blue-100">{{ pkg.duration }}</p>
              </div>
            </div>
            <!-- Decorative Pattern -->
            <div class="absolute bottom-0 left-0 right-0 h-6 bg-white" style="clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 100%)"></div>
          </div>

          <!-- Package Content -->
          <div class="p-8">
            <!-- Description -->
            <p class="text-gray-600 mb-6 text-center">{{ pkg.description }}</p>

            <!-- Pricing -->
            <div class="text-center mb-8">
              <div class="mb-4">
                <span class="text-4xl font-bold text-gray-900">
                  Rp {{ formatPrice(pkg.price) }}
                </span>
                <span class="text-gray-500 text-lg ml-2">/ orang</span>
              </div>

              <!-- DP Highlight -->
              <div class="bg-gradient-to-r from-green-100 to-blue-100 rounded-xl p-4 mb-4">
                <p class="text-sm text-gray-600 mb-1">DP mulai dari:</p>
                <span class="text-2xl font-bold text-green-600">
                  Rp {{ formatPrice(pkg.dp) }}
                </span>
                <p class="text-xs text-gray-500 mt-1">Cicilan tersedia</p>
              </div>
            </div>

            <!-- Features List -->
            <div class="mb-8">
              <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Fasilitas Termasuk:
              </h4>
              <ul class="space-y-3">
                <li
                  v-for="feature in pkg.features"
                  :key="feature"
                  class="flex items-center text-gray-700"
                >
                  <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span class="text-sm">{{ feature }}</span>
                </li>
              </ul>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-3">
              <button
                @click="selectPackage(pkg)"
                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                :class="{
                  'from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700': pkg.badge === 'Best Seller',
                  'from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800': pkg.badge === 'Popular'
                }"
              >
                <span class="flex items-center justify-center space-x-2">
                  <span>Pilih Paket Ini</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                  </svg>
                </span>
              </button>

              <button
                @click="consultPackage(pkg)"
                class="w-full bg-white border-2 border-gray-300 hover:border-blue-500 text-gray-700 hover:text-blue-600 font-semibold py-3 rounded-xl transition-all duration-300"
              >
                <span class="flex items-center justify-center space-x-2">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                  </svg>
                  <span>Konsultasi Gratis</span>
                </span>
              </button>
            </div>
          </div>

          <!-- Best Value Badge for Popular Package -->
          <div v-if="pkg.badge === 'Popular'" class="absolute -top-4 left-1/2 transform -translate-x-1/2">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
              🌟 PALING DIMINATI
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="mt-16 bg-white rounded-2xl p-8 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <div class="space-y-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.018.887l2.706-2.706a.75.75 0 00-1.06-1.06l-2.707 2.706a.75.75 0 101.061 1.06z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Terdaftar Resmi</h3>
            <p class="text-gray-600">Berizin Kementerian Agama RI dengan sertifikat UMROH/HAJI</p>
          </div>

          <div class="space-y-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Cicilan 0%</h3>
            <p class="text-gray-600">Sistem pembayaran bertahap tanpa bunga hingga keberangkatan</p>
          </div>

          <div class="space-y-4">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
              <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Garansi Kepuasan</h3>
            <p class="text-gray-600">Jaminan pelayanan terbaik atau uang kembali 100%</p>
          </div>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="mt-16 text-center">
        <div class="bg-gradient-to-r from-blue-600 to-red-600 rounded-2xl p-8 text-white">
          <h3 class="text-3xl font-bold mb-4">Masih Bingung Pilih Paket?</h3>
          <p class="text-xl mb-6 opacity-90">Konsultasi GRATIS dengan tim ahli kami untuk mendapatkan rekomendasi paket terbaik</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a
              :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
              target="_blank"
              class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-full font-bold transition-all duration-300 transform hover:scale-105"
            >
              💬 Chat WhatsApp Sekarang
            </a>
            <a
              :href="`tel:${BRAND.phone}`"
              class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-full font-bold transition-all duration-300"
            >
              📞 Telepon Langsung
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { BRAND } from '@/Utils/constants.js'

const props = defineProps({
  packages: {
    type: Array,
    required: true
  }
})

const formatPrice = (price) => {
  return (price / 1000000).toFixed(0) + ' Juta'
}

const selectPackage = (pkg) => {
  // Navigate to booking form or modal
  const message = `Halo, saya tertarik dengan paket ${pkg.name} (${pkg.duration}). Bisakah Anda memberikan informasi lebih detail?`
  const whatsappUrl = `https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`
  window.open(whatsappUrl, '_blank')
}

const consultPackage = (pkg) => {
  const message = `Halo, saya ingin konsultasi tentang paket ${pkg.name}. Mohon bantuan untuk memberikan rekomendasi yang sesuai kebutuhan saya.`
  const whatsappUrl = `https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`
  window.open(whatsappUrl, '_blank')
}
</script>