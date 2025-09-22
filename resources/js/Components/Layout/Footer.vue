<template>
  <footer class="bg-gray-900 text-white">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand Section -->
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center space-x-3 mb-4">
            <img
              :src="BRAND.logo"
              :alt="BRAND.name"
              class="h-10 w-auto"
            >
            <div>
              <h3 class="text-xl font-bold text-white">{{ BRAND.name }}</h3>
              <p class="text-sm text-gray-300">{{ BRAND.tagline }}</p>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">
            Terpercaya dalam melayani perjalanan umroh dengan pengalaman puluhan tahun.
            Berizin resmi dan berkomitmen memberikan pelayanan terbaik.
          </p>

          <!-- Social Media -->
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.219-.359-1.219c0-1.142.662-1.995 1.482-1.995.699 0 1.037.524 1.037 1.155 0 .703-.449 1.753-.68 2.724-.194.821.412 1.492 1.219 1.492 1.464 0 2.588-1.544 2.588-3.774 0-1.972-1.417-3.353-3.443-3.353-2.346 0-3.724 1.76-3.724 3.579 0 .709.273 1.468.614 1.881.067.082.077.154.057.238-.062.26-.199.798-.226.908-.035.146-.116.177-.268.107-1-.464-1.624-1.924-1.624-3.101 0-2.598 1.887-4.984 5.437-4.984 2.856 0 5.078 2.036 5.078 4.756 0 2.837-1.789 5.117-4.27 5.117-.833 0-1.618-.437-1.886-1.015l-.51 1.945c-.185.711-.685 1.6-1.019 2.142.767.234 1.577.357 2.419.357 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.051 3.999c2.122 0 2.383.009 3.232.047 2.236.102 3.669 1.547 3.771 3.771.038.849.047 1.11.047 3.232s-.009 2.383-.047 3.232c-.102 2.224-1.535 3.669-3.771 3.771-.849.038-1.11.047-3.232.047s-2.383-.009-3.232-.047c-2.236-.102-3.669-1.547-3.771-3.771-.038-.849-.047-1.11-.047-3.232s.009-2.383.047-3.232c.102-2.224 1.535-3.669 3.771-3.771.849-.038 1.11-.047 3.232-.047zm0-1.448c-2.156 0-2.427.009-3.275.048-2.952.135-4.654 1.838-4.789 4.789-.039.848-.048 1.119-.048 3.275s.009 2.427.048 3.275c.135 2.951 1.837 4.654 4.789 4.789.848.039 1.119.048 3.275.048s2.427-.009 3.275-.048c2.951-.135 4.654-1.838 4.789-4.789.039-.848.048-1.119.048-3.275s-.009-2.427-.048-3.275c-.135-2.951-1.838-4.654-4.789-4.789-.848-.039-1.119-.048-3.275-.048z"/>
                <path d="M12.051 7.378c-2.566 0-4.621 2.055-4.621 4.621s2.055 4.621 4.621 4.621 4.621-2.055 4.621-4.621-2.055-4.621-4.621-4.621zm0 7.621c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/>
                <circle cx="17.011" cy="6.988" r="1.44"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-lg font-semibold mb-4 text-yellow-400">Menu Utama</h4>
          <ul class="space-y-2">
            <li><Link href="/" class="text-gray-300 hover:text-white transition-colors">Beranda</Link></li>
            <li><Link href="/paket-umroh" class="text-gray-300 hover:text-white transition-colors">Paket Umroh</Link></li>
            <li><Link href="/gallery" class="text-gray-300 hover:text-white transition-colors">Gallery</Link></li>
            <li><Link href="/tentang" class="text-gray-300 hover:text-white transition-colors">Tentang Kami</Link></li>
            <li><Link href="/kontak" class="text-gray-300 hover:text-white transition-colors">Kontak</Link></li>
          </ul>
        </div>

        <!-- Services -->
        <div>
          <h4 class="text-lg font-semibold mb-4 text-yellow-400">Layanan</h4>
          <ul class="space-y-2">
            <li><Link href="/umroh-reguler" class="text-gray-300 hover:text-white transition-colors">City Tour Malaysia</Link></li>
            <li><Link href="/umroh-plus" class="text-gray-300 hover:text-white transition-colors">Plus Tha`if</Link></li>
            <li><Link href="/umroh-vip" class="text-gray-300 hover:text-white transition-colors">Plus Tha`if dan Kereta Cepat</Link></li>
            <li><Link href="/manasik" class="text-gray-300 hover:text-white transition-colors">Manasik</Link></li>
            <li><Link href="/visa" class="text-gray-300 hover:text-white transition-colors">Pengurusan Visa</Link></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h4 class="text-lg font-semibold mb-4 text-yellow-400">Hubungi Kami</h4>
          <div class="space-y-3">
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span class="text-gray-300">{{ BRAND.phone }}</span>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
              <span class="text-gray-300">{{ BRAND.whatsapp }}</span>
            </div>
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span class="text-gray-300">{{ BRAND.email }}</span>
            </div>
            <div class="flex items-start space-x-3">
              <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span class="text-gray-300">
                Jl. Parit Indah No. 151<br>
                Pekanbaru, Riau
              </span>
            </div>
          </div>

          <!-- CTA WhatsApp -->
          <div class="mt-6">
            <a
              :href="`https://wa.me/${BRAND.whatsapp.replace(/[^0-9]/g, '')}`"
              target="_blank"
              class="inline-flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
              <span>Chat WhatsApp</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="text-gray-400 text-sm">
            © {{ new Date().getFullYear() }} {{ BRAND.name }}. All rights reserved.
          </div>
          <div class="flex space-x-6 mt-4 md:mt-0">
            <Link href="/privacy" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</Link>
            <Link href="/terms" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</Link>
            <Link href="/sitemap" class="text-gray-400 hover:text-white text-sm transition-colors">Sitemap</Link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { BRAND } from '@/Utils/constants.js'
</script>
