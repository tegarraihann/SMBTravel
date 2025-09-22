<template>
  <section id="packages-section" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
          Paket Umroh Kami
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Pilih paket yang sesuai dengan kebutuhan dan budget Anda. Semua paket sudah termasuk <b>visa, tiket, hotel, dan makan.</b>
        </p>
      </div>

      <!-- Packages Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div
          v-for="(pkg, index) in allPackages"
          :key="pkg.id"
          class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200"
        >
          <!-- Badge -->
          <div v-if="pkg.badge" class="absolute top-4 left-4 z-10">
            <span
              class="px-3 py-1 text-sm rounded-md font-medium text-white"
              :class="{
                'bg-red-600': pkg.badge === 'Best Seller',
                'bg-blue-600': pkg.badge === 'Popular',
                'bg-yellow-600': pkg.badge === 'Premium'
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
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute bottom-4 left-4 text-white">
              <h3 class="text-xl font-bold mb-1">{{ pkg.name }}</h3>
              <p class="text-gray-200">{{ pkg.duration }}</p>
            </div>
          </div>

          <!-- Package Content -->
          <div class="p-6">
            <!-- Description -->
            <p class="text-gray-600 mb-6">{{ pkg.description }}</p>

            <!-- Pricing -->
            <div class="mb-6">
              <div class="flex items-baseline justify-between mb-3">
                <span class="text-2xl font-bold text-gray-900">
                  Rp {{ formatPrice(pkg.price) }}
                </span>
                <span class="text-gray-500">/ orang</span>
              </div>

              <!-- DP Highlight -->
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex justify-between items-center">
                  <span class="text-sm text-blue-700">DP mulai dari:</span>
                  <span class="font-bold text-blue-600">
                    Rp {{ formatPrice(pkg.dp) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Features List -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-900 mb-3">
                Fasilitas Termasuk:
              </h4>
              <ul class="space-y-2">
                <li
                  v-for="feature in pkg.features"
                  :key="feature"
                  class="flex items-start text-gray-700"
                >
                  <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                  <span class="text-sm">{{ feature }}</span>
                </li>
              </ul>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-3">
              <button
                @click="selectPackage(pkg)"
                class="w-full text-white font-semibold py-3 rounded-lg transition-colors duration-200"
                :class="{
                  'bg-red-600 hover:bg-red-700': pkg.badge === 'Best Seller',
                  'bg-blue-600 hover:bg-blue-700': pkg.badge === 'Popular',
                  'bg-yellow-600 hover:bg-yellow-700': pkg.badge === 'Premium',
                  'bg-slate-600 hover:bg-slate-700': !pkg.badge
                }"
              >
                Pilih Paket Ini
              </button>

              <button
                @click="consultPackage(pkg)"
                class="w-full border-2 border-slate-300 hover:border-blue-600 text-slate-700 hover:text-blue-600 bg-white font-semibold py-3 rounded-lg transition-colors duration-200"
              >
                Konsultasi Gratis
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="mt-16 bg-white rounded-lg border border-gray-200 p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <div class="space-y-3">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <div class="w-6 h-6 bg-red-600 rounded-full"></div>
            </div>
            <h3 class="text-lg font-semibold text-slate-900">Terdaftar Resmi</h3>
            <p class="text-slate-600">Berizin Kementerian Agama RI</p>
          </div>

          <div class="space-y-3">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <div class="w-6 h-6 bg-blue-600 rounded-full"></div>
            </div>
            <h3 class="text-lg font-semibold text-slate-900">Cicilan 0%</h3>
            <p class="text-slate-600">Pembayaran bertahap tanpa bunga</p>
          </div>

          <div class="space-y-3">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <div class="w-6 h-6 bg-yellow-600 rounded-full"></div>
            </div>
            <h3 class="text-lg font-semibold text-slate-900">Berpengalaman</h3>
            <p class="text-slate-600">Melayani jamaah sejak 2010</p>
          </div>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="mt-16 text-center">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-8 text-white">
          <h3 class="text-2xl font-bold mb-4">Butuh Bantuan Memilih Paket?</h3>
          <p class="text-lg mb-6 text-blue-100">Konsultasi gratis dengan tim kami untuk mendapatkan rekomendasi terbaik</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a
              :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
              target="_blank"
              class="bg-yellow-500 hover:bg-yellow-600 text-slate-900 px-6 py-3 rounded-lg font-semibold transition-colors duration-200"
            >
              Chat WhatsApp
            </a>
            <a
              :href="`tel:${BRAND.phone}`"
              class="bg-white hover:bg-gray-100 text-blue-600 px-6 py-3 rounded-lg font-semibold transition-colors duration-200"
            >
              Telepon Langsung
            </a>
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
  packages: {
    type: Array,
    required: false,
    default: () => []
  }
})

// Data paket umroh yang bisa dirubah secara manual
const dummyPackages = ref([
  {
    id: 1,
    name: 'City Tour Malaysia',
    duration: 'Pekanbaru - Kuala Lumpur - Madinah',
    price: 24500000,
    dp: 3000000, // 5 juta
    badge: 'Best Seller',
    image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    description: 'lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    features: [
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
    ]
  },
  {
    id: 2,
    name: 'Plus Tha`if',
    duration: 'Pekanbaru - Kuala Lumpur - Madinah',
    price: 34500000, // 32 juta
    dp: 3000000, // 8 juta
    badge: 'Popular',
    image: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    description: 'lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    features: [
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
    ]
  },
  {
    id: 3,
    name: 'Plus Tha`if dan Kereta Cepat',
    duration: 'Pekanbaru - Kuala Lumpur - Madinah',
    price: 41000000, // 45 juta
    dp: 3000000, // 12 juta
    badge: 'Premium',
    image: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    description: 'lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    features: [
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,',
      'lorem ipsum dolor sit amet,'
    ]
  }
])

// Hanya gunakan dummy data yang baru, hapus data lama
const allPackages = computed(() => {
  return dummyPackages.value
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

// Function untuk update paket secara manual (bisa dipanggil dari console)
const updatePackage = (id, updates) => {
  const packageIndex = dummyPackages.value.findIndex(pkg => pkg.id === id)
  if (packageIndex !== -1) {
    dummyPackages.value[packageIndex] = { ...dummyPackages.value[packageIndex], ...updates }
  }
}

const addPackage = (newPackage) => {
  const maxId = Math.max(...dummyPackages.value.map(pkg => pkg.id), 0)
  dummyPackages.value.push({ id: maxId + 1, ...newPackage })
}

const removePackage = (id) => {
  const index = dummyPackages.value.findIndex(pkg => pkg.id === id)
  if (index !== -1) {
    dummyPackages.value.splice(index, 1)
  }
}

// Expose functions ke global untuk debugging/testing
if (typeof window !== 'undefined') {
  window.umrohPackages = {
    packages: dummyPackages,
    updatePackage,
    addPackage,
    removePackage,
    // Contoh penggunaan:
    // window.umrohPackages.updatePackage(1, { price: 30000000, name: 'Paket Umroh Ekonomi Plus' })
    // window.umrohPackages.addPackage({ name: 'Paket Baru', price: 20000000, duration: '10 Hari', dp: 5000000, badge: 'New', image: 'url', description: 'desc', features: ['feature1'] })
    // window.umrohPackages.removePackage(1)
  }
}
</script>
