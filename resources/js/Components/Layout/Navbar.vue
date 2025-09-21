<template>
  <nav class="bg-white shadow-lg border-b-4 border-red-600 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <Link href="/" class="flex items-center space-x-3">
            <img
              :src="BRAND.logo"
              :alt="BRAND.name"
              class="h-10 w-auto"
            >
            <div class="hidden sm:block">
              <h1 class="text-xl font-bold text-blue-700">{{ BRAND.name }}</h1>
              <p class="text-xs text-gray-600">{{ BRAND.tagline }}</p>
            </div>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <Link
            href="/"
            class="text-gray-700 hover:text-blue-600 px-3 py-2 transition-colors"
            :class="{ 'text-blue-600 font-semibold': isActive('/') }"
          >
            Beranda
          </Link>
          <Link
            href="/paket-umroh"
            class="text-gray-700 hover:text-blue-600 px-3 py-2 transition-colors"
            :class="{ 'text-blue-600 font-semibold': isActive('/paket-umroh') }"
          >
            Paket Umroh
          </Link>
          <Link
            href="/gallery"
            class="text-gray-700 hover:text-blue-600 px-3 py-2 transition-colors"
            :class="{ 'text-blue-600 font-semibold': isActive('/gallery') }"
          >
            Gallery
          </Link>
          <Link
            href="/tentang"
            class="text-gray-700 hover:text-blue-600 px-3 py-2 transition-colors"
            :class="{ 'text-blue-600 font-semibold': isActive('/tentang') }"
          >
            Tentang Kami
          </Link>
          <Link
            href="/kontak"
            class="text-gray-700 hover:text-blue-600 px-3 py-2 transition-colors"
            :class="{ 'text-blue-600 font-semibold': isActive('/kontak') }"
          >
            Kontak
          </Link>
        </div>

        <!-- CTA Buttons -->
        <div class="hidden md:flex items-center space-x-4">
          <!-- WhatsApp Button -->
          <a
            :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
            target="_blank"
            class="flex items-center space-x-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
            <span class="hidden lg:block">WhatsApp</span>
          </a>

          <!-- Primary CTA -->
          <Link
            href="/daftar"
            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
          >
            Daftar Sekarang
          </Link>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden">
          <button
            @click="toggleMobileMenu"
            class="text-gray-700 hover:text-blue-600 focus:outline-none"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-show="showMobileMenu" class="md:hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <Link
            href="/"
            class="block px-3 py-2 text-gray-700 hover:text-blue-600"
            @click="closeMobileMenu"
          >
            Beranda
          </Link>
          <Link
            href="/paket-umroh"
            class="block px-3 py-2 text-gray-700 hover:text-blue-600"
            @click="closeMobileMenu"
          >
            Paket Umroh
          </Link>
          <Link
            href="/gallery"
            class="block px-3 py-2 text-gray-700 hover:text-blue-600"
            @click="closeMobileMenu"
          >
            Gallery
          </Link>
          <Link
            href="/tentang"
            class="block px-3 py-2 text-gray-700 hover:text-blue-600"
            @click="closeMobileMenu"
          >
            Tentang Kami
          </Link>
          <Link
            href="/kontak"
            class="block px-3 py-2 text-gray-700 hover:text-blue-600"
            @click="closeMobileMenu"
          >
            Kontak
          </Link>

          <!-- Mobile CTA Buttons -->
          <div class="border-t border-gray-200 pt-4 pb-3 space-y-3">
            <a
              :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
              target="_blank"
              class="flex items-center space-x-2 bg-green-500 text-white px-4 py-2 rounded-lg mx-3"
              @click="closeMobileMenu"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
              <span>WhatsApp</span>
            </a>
            <Link
              href="/daftar"
              class="block bg-red-600 text-white px-4 py-2 rounded-lg mx-3 text-center font-semibold"
              @click="closeMobileMenu"
            >
              Daftar Sekarang
            </Link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { BRAND } from '@/Utils/constants.js'

const showMobileMenu = ref(false)
const page = usePage()

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
}

const closeMobileMenu = () => {
  showMobileMenu.value = false
}

const isActive = (route) => {
  return page.url.value === route
}
</script>