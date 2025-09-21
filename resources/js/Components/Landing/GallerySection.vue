<template>
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
          Galeri
          <span class="bg-gradient-to-r from-red-600 to-blue-600 bg-clip-text text-transparent">
            Perjalanan
          </span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
          Saksikan dokumentasi perjalanan spiritual jamaah SMB Travel di Tanah Suci
        </p>

        <!-- Filter Buttons -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
          <button
            @click="setActiveFilter('all')"
            class="px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="{
              'bg-blue-600 text-white shadow-lg': activeFilter === 'all',
              'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'all'
            }"
          >
            <span class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
              <span>Semua ({{ gallery.length }})</span>
            </span>
          </button>
          <button
            @click="setActiveFilter('makkah')"
            class="px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="{
              'bg-red-600 text-white shadow-lg': activeFilter === 'makkah',
              'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'makkah'
            }"
          >
            <span class="flex items-center space-x-2">
              <span>🕋</span>
              <span>Makkah ({{ filteredGallery('makkah').length }})</span>
            </span>
          </button>
          <button
            @click="setActiveFilter('madinah')"
            class="px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="{
              'bg-green-600 text-white shadow-lg': activeFilter === 'madinah',
              'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'madinah'
            }"
          >
            <span class="flex items-center space-x-2">
              <span>🕌</span>
              <span>Madinah ({{ filteredGallery('madinah').length }})</span>
            </span>
          </button>
          <button
            @click="setActiveFilter('group')"
            class="px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="{
              'bg-yellow-600 text-white shadow-lg': activeFilter === 'group',
              'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'group'
            }"
          >
            <span class="flex items-center space-x-2">
              <span>👥</span>
              <span>Jamaah ({{ filteredGallery('group').length }})</span>
            </span>
          </button>
          <button
            @click="setActiveFilter('journey')"
            class="px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="{
              'bg-purple-600 text-white shadow-lg': activeFilter === 'journey',
              'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'journey'
            }"
          >
            <span class="flex items-center space-x-2">
              <span>✈️</span>
              <span>Perjalanan ({{ filteredGallery('journey').length }})</span>
            </span>
          </button>
        </div>
      </div>

      <!-- Gallery Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="(item, index) in displayedGallery"
          :key="item.id"
          @click="openLightbox(index)"
          class="group relative bg-gray-100 rounded-xl overflow-hidden cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl"
          :style="{ aspectRatio: getAspectRatio(index) }"
        >
          <!-- Image/Video Placeholder -->
          <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-red-600 flex items-center justify-center">
            <div class="text-center text-white">
              <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg v-if="item.type === 'image'" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                </svg>
              </div>
              <h4 class="text-lg font-bold mb-2">{{ item.title }}</h4>
              <span class="text-sm opacity-75 uppercase tracking-wider">{{ item.category }}</span>
            </div>
          </div>

          <!-- Hover Overlay -->
          <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
            <div class="transform scale-0 group-hover:scale-100 transition-transform duration-300">
              <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Type Badge -->
          <div class="absolute top-4 right-4">
            <span v-if="item.type === 'video'" class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold flex items-center space-x-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
              </svg>
              <span>VIDEO</span>
            </span>
            <span v-else class="bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-bold flex items-center space-x-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
              </svg>
              <span>FOTO</span>
            </span>
          </div>
        </div>
      </div>

      <!-- Load More Button -->
      <div v-if="displayedGallery.length < currentFilteredGallery.length" class="text-center mt-12">
        <button
          @click="loadMore"
          class="bg-gradient-to-r from-blue-600 to-red-600 hover:from-blue-700 hover:to-red-700 text-white font-bold px-8 py-4 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
        >
          <span class="flex items-center space-x-2">
            <span>Lihat Lebih Banyak</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
          </span>
        </button>
      </div>

      <!-- CTA Section -->
      <div class="mt-16 text-center">
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-8">
          <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
            Jadilah Bagian dari Perjalanan Spiritual Berikutnya
          </h3>
          <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
            Bergabunglah dengan ribuan jamaah yang telah merasakan pengalaman tak terlupakan bersama SMB Travel
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a
              :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
              target="_blank"
              class="bg-gradient-to-r from-blue-600 to-red-600 hover:from-blue-700 hover:to-red-700 text-white px-8 py-4 rounded-full font-bold transition-all duration-300 transform hover:scale-105"
            >
              📞 Konsultasi Gratis
            </a>
            <button
              @click="scrollToPackages"
              class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-8 py-4 rounded-full font-bold transition-all duration-300"
            >
              📋 Lihat Paket Umroh
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Lightbox Modal -->
    <div
      v-if="showLightbox"
      @click="closeLightbox"
      class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
    >
      <div class="relative max-w-4xl max-h-full" @click.stop>
        <!-- Close Button -->
        <button
          @click="closeLightbox"
          class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        <!-- Navigation Buttons -->
        <button
          v-if="currentLightboxIndex > 0"
          @click="previousImage"
          class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 transition-colors"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>

        <button
          v-if="currentLightboxIndex < displayedGallery.length - 1"
          @click="nextImage"
          class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 transition-colors"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>

        <!-- Content -->
        <div class="bg-white rounded-lg overflow-hidden">
          <!-- Media Display -->
          <div class="aspect-video bg-gradient-to-br from-blue-600 to-red-600 flex items-center justify-center">
            <div class="text-center text-white">
              <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg v-if="currentLightboxItem.type === 'image'" class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M21 19V5c0-1.1-.9-2-2-2H5c0-1.1-.9-2-2-2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <svg v-else class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                </svg>
              </div>
              <h3 class="text-2xl font-bold mb-2">{{ currentLightboxItem.title }}</h3>
              <p class="opacity-75">Placeholder for {{ currentLightboxItem.type === 'image' ? 'Image' : 'Video' }}</p>
            </div>
          </div>

          <!-- Details -->
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ currentLightboxItem.title }}</h3>
            <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
              {{ getCategoryLabel(currentLightboxItem.category) }}
            </span>
          </div>
        </div>

        <!-- Counter -->
        <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 text-white text-sm">
          {{ currentLightboxIndex + 1 }} / {{ displayedGallery.length }}
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
    required: true
  }
})

const activeFilter = ref('all')
const showLightbox = ref(false)
const currentLightboxIndex = ref(0)
const itemsPerPage = 8
const currentPage = ref(1)

const filteredGallery = (category) => {
  if (category === 'all') return props.gallery
  return props.gallery.filter(item => item.category === category)
}

const currentFilteredGallery = computed(() => {
  return filteredGallery(activeFilter.value)
})

const displayedGallery = computed(() => {
  const endIndex = currentPage.value * itemsPerPage
  return currentFilteredGallery.value.slice(0, endIndex)
})

const currentLightboxItem = computed(() => {
  return displayedGallery.value[currentLightboxIndex.value] || {}
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
  currentLightboxIndex.value = index
  showLightbox.value = true
  document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
  showLightbox.value = false
  document.body.style.overflow = 'auto'
}

const nextImage = () => {
  if (currentLightboxIndex.value < displayedGallery.value.length - 1) {
    currentLightboxIndex.value += 1
  }
}

const previousImage = () => {
  if (currentLightboxIndex.value > 0) {
    currentLightboxIndex.value -= 1
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