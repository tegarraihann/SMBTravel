<template>
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
          Testimoni
          <span class="bg-gradient-to-r from-blue-600 to-red-600 bg-clip-text text-transparent">
            Jamaah
          </span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
          Dengarkan pengalaman spiritual jamaah SMB Travel yang telah menunaikan ibadah umroh
        </p>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
          <div class="text-center">
            <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-2">1000+</div>
            <div class="text-gray-600 font-medium">Jamaah Terlayani</div>
          </div>
          <div class="text-center">
            <div class="text-3xl sm:text-4xl font-bold text-red-600 mb-2">15+</div>
            <div class="text-gray-600 font-medium">Tahun Pengalaman</div>
          </div>
          <div class="text-center">
            <div class="text-3xl sm:text-4xl font-bold text-yellow-600 mb-2">4.9</div>
            <div class="text-gray-600 font-medium">Rating Kepuasan</div>
          </div>
          <div class="text-center">
            <div class="text-3xl sm:text-4xl font-bold text-green-600 mb-2">100%</div>
            <div class="text-gray-600 font-medium">Keberangkatan</div>
          </div>
        </div>
      </div>

      <!-- Testimonials Carousel -->
      <div class="relative">
        <!-- Main Testimonial Display -->
        <div class="bg-gradient-to-br from-blue-50 to-red-50 rounded-2xl p-8 mb-8">
          <div class="max-w-4xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
              <!-- Profile Image -->
              <div class="flex-shrink-0">
                <div class="relative">
                  <img
                    :src="currentTestimonial.image"
                    :alt="currentTestimonial.name"
                    class="w-24 h-24 lg:w-32 lg:h-32 rounded-full object-cover shadow-xl border-4 border-white"
                  >
                  <!-- Verified Badge -->
                  <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Testimonial Content -->
              <div class="flex-1 text-center lg:text-left">
                <!-- Quote Icon -->
                <div class="mb-6">
                  <svg class="w-12 h-12 text-blue-300 mx-auto lg:mx-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                  </svg>
                </div>

                <!-- Rating Stars -->
                <div class="flex justify-center lg:justify-start mb-4">
                  <div class="flex space-x-1">
                    <svg v-for="i in currentTestimonial.rating" :key="i" class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                  </div>
                </div>

                <!-- Testimonial Text -->
                <blockquote class="text-lg lg:text-xl text-gray-700 italic mb-6 leading-relaxed">
                  "{{ currentTestimonial.comment }}"
                </blockquote>

                <!-- Author Info -->
                <div class="border-t border-gray-200 pt-4">
                  <div class="font-bold text-gray-900 text-lg">{{ currentTestimonial.name }}</div>
                  <div class="text-gray-600">{{ currentTestimonial.location }}</div>
                  <div class="text-sm text-gray-500 mt-1">{{ formatDate(currentTestimonial.date) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-center items-center space-x-4 mb-8">
          <button
            @click="previousTestimonial"
            class="p-3 bg-white shadow-lg rounded-full hover:bg-gray-50 transition-colors border border-gray-200"
          >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>

          <!-- Dots Indicator -->
          <div class="flex space-x-2">
            <button
              v-for="(testimonial, index) in testimonials"
              :key="testimonial.id"
              @click="setCurrentTestimonial(index)"
              class="w-3 h-3 rounded-full transition-colors"
              :class="{
                'bg-blue-600': index === currentIndex,
                'bg-gray-300': index !== currentIndex
              }"
            ></button>
          </div>

          <button
            @click="nextTestimonial"
            class="p-3 bg-white shadow-lg rounded-full hover:bg-gray-50 transition-colors border border-gray-200"
          >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

        <!-- All Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="(testimonial, index) in testimonials"
            :key="testimonial.id"
            @click="setCurrentTestimonial(index)"
            class="bg-white rounded-xl shadow-lg p-6 cursor-pointer transition-all duration-300 hover:shadow-xl border-2"
            :class="{
              'border-blue-500 ring-2 ring-blue-200': index === currentIndex,
              'border-gray-200 hover:border-blue-300': index !== currentIndex
            }"
          >
            <!-- Mini Profile -->
            <div class="flex items-center mb-4">
              <img
                :src="testimonial.image"
                :alt="testimonial.name"
                class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-gray-200"
              >
              <div>
                <div class="font-semibold text-gray-900">{{ testimonial.name }}</div>
                <div class="text-sm text-gray-600">{{ testimonial.location }}</div>
              </div>
            </div>

            <!-- Mini Rating -->
            <div class="flex mb-3">
              <svg v-for="i in testimonial.rating" :key="i" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>

            <!-- Mini Comment -->
            <p class="text-gray-700 text-sm line-clamp-3">
              "{{ testimonial.comment }}"
            </p>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="mt-16 text-center">
        <div class="bg-gradient-to-r from-blue-600 to-red-600 rounded-2xl p-8 text-white">
          <h3 class="text-2xl sm:text-3xl font-bold mb-4">
            Bergabunglah dengan Ribuan Jamaah Puas Lainnya
          </h3>
          <p class="text-lg opacity-90 mb-6 max-w-2xl mx-auto">
            Rasakan pengalaman spiritual yang tak terlupakan bersama SMB Travel.
            Dapatkan pelayanan terbaik dan bimbingan manasik yang komprehensif.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a
              :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
              target="_blank"
              class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-full font-bold transition-all duration-300 transform hover:scale-105"
            >
              📝 Daftar Umroh Sekarang
            </a>
            <button
              @click="scrollToPackages"
              class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-full font-bold transition-all duration-300"
            >
              📋 Lihat Semua Paket
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { BRAND } from '@/Utils/constants.js'

const props = defineProps({
  testimonials: {
    type: Array,
    required: true
  }
})

const currentIndex = ref(0)
let autoPlayInterval = null

const currentTestimonial = computed(() => {
  return props.testimonials[currentIndex.value] || props.testimonials[0]
})

const nextTestimonial = () => {
  currentIndex.value = (currentIndex.value + 1) % props.testimonials.length
}

const previousTestimonial = () => {
  currentIndex.value = currentIndex.value === 0
    ? props.testimonials.length - 1
    : currentIndex.value - 1
}

const setCurrentTestimonial = (index) => {
  currentIndex.value = index
  // Reset auto-play timer when manually selecting
  stopAutoPlay()
  startAutoPlay()
}

const startAutoPlay = () => {
  autoPlayInterval = setInterval(() => {
    nextTestimonial()
  }, 5000) // Change every 5 seconds
}

const stopAutoPlay = () => {
  if (autoPlayInterval) {
    clearInterval(autoPlayInterval)
    autoPlayInterval = null
  }
}

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('id-ID', options)
}

const scrollToPackages = () => {
  const packagesSection = document.getElementById('packages-section')
  if (packagesSection) {
    packagesSection.scrollIntoView({ behavior: 'smooth' })
  }
}

onMounted(() => {
  startAutoPlay()
})

onUnmounted(() => {
  stopAutoPlay()
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>