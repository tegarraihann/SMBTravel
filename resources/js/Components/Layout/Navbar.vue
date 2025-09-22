<template>
    <nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link href="/" class="flex items-center space-x-3">
                    <img :src="BRAND.logo" :alt="BRAND.name" class="h-10 w-auto">
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-slate-800">{{ BRAND.name }}</h1>
                        <p class="text-xs text-gray-600">{{ BRAND.tagline }}</p>
                    </div>
                    </Link>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <Link href="/" class="text-gray-600 hover:text-slate-800 px-3 py-2 transition-colors"
                        :class="{ 'text-slate-800 font-semibold': isActive('/') }">
                    Beranda
                    </Link>
                    <Link href="/paket-umroh" class="text-gray-600 hover:text-slate-800 px-3 py-2 transition-colors"
                        :class="{ 'text-slate-800 font-semibold': isActive('/paket-umroh') }">
                    Paket Umroh
                    </Link>
                    <Link href="/gallery" class="text-gray-600 hover:text-slate-800 px-3 py-2 transition-colors"
                        :class="{ 'text-slate-800 font-semibold': isActive('/gallery') }">
                    Gallery
                    </Link>
                    <Link href="/tentang" class="text-gray-600 hover:text-slate-800 px-3 py-2 transition-colors"
                        :class="{ 'text-slate-800 font-semibold': isActive('/tentang') }">
                    Tentang Kami
                    </Link>
                    <Link href="/kontak" class="text-gray-600 hover:text-slate-800 px-3 py-2 transition-colors"
                        :class="{ 'text-slate-800 font-semibold': isActive('/kontak') }">
                    Kontak
                    </Link>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-3">
                    <Link href="/login"
                        class="text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg font-medium transition-colors">
                    Masuk
                    </Link>
                    <Link href="/register"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    Daftar Akun
                    </Link>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="toggleMobileMenu" class="text-gray-600 hover:text-slate-800 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="showMobileMenu" class="md:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link href="/" class="block px-3 py-2 text-gray-600 hover:text-slate-800" @click="closeMobileMenu">
                    Beranda
                    </Link>
                    <Link href="/paket-umroh" class="block px-3 py-2 text-gray-600 hover:text-slate-800"
                        @click="closeMobileMenu">
                    Paket Umroh
                    </Link>
                    <Link href="/gallery" class="block px-3 py-2 text-gray-600 hover:text-slate-800"
                        @click="closeMobileMenu">
                    Gallery
                    </Link>
                    <Link href="/tentang" class="block px-3 py-2 text-gray-600 hover:text-slate-800"
                        @click="closeMobileMenu">
                    Tentang Kami
                    </Link>
                    <Link href="/kontak" class="block px-3 py-2 text-gray-600 hover:text-slate-800"
                        @click="closeMobileMenu">
                    Kontak
                    </Link>

                    <!-- Mobile Auth Buttons -->
                    <div class="border-t border-gray-200 pt-4 pb-3 space-y-3">
                        <Link href="/login"
                            class="block text-gray-600 hover:text-blue-600 px-3 py-2 mx-3 text-center font-medium"
                            @click="closeMobileMenu">
                        Login
                        </Link>
                        <Link :href="route('jamaah.daftar')"
                            class="block bg-blue-600 text-white px-4 py-2 rounded-lg mx-3 text-center font-semibold"
                            @click="closeMobileMenu">
                        Register
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
