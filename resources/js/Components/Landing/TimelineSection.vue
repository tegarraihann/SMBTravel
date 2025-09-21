<template>
  <section class="py-20 bg-gradient-to-br from-blue-50 to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
          Perjalanan
          <span class="bg-gradient-to-r from-blue-600 to-red-600 bg-clip-text text-transparent">
            Umroh Anda
          </span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Dari keberangkatan hingga kepulangan, kami akan memandu setiap langkah perjalanan spiritual Anda
        </p>
      </div>

      <!-- Timeline -->
      <div class="relative">
        <!-- Timeline Line -->
        <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 via-red-500 to-yellow-500 transform md:-translate-x-1/2"></div>

        <!-- Timeline Items -->
        <div class="space-y-12">
          <!-- Pre-Departure -->
          <div class="relative flex items-center">
            <div class="flex items-center w-full">
              <!-- Icon -->
              <div class="flex-shrink-0 w-8 h-8 md:w-12 md:h-12 bg-blue-600 rounded-full flex items-center justify-center relative z-10">
                <svg class="w-4 h-4 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>

              <!-- Content -->
              <div class="ml-6 md:ml-8 flex-1 md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                  <div class="flex items-center mb-4">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">PRE-KEBERANGKATAN</span>
                  </div>
                  <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Persiapan & Manasik</h3>
                  <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Pengurusan visa & dokumen</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Bimbingan manasik komprehensif</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Perlengkapan umroh & vaksinasi</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Departure -->
          <div class="relative flex items-center md:flex-row-reverse">
            <div class="flex items-center w-full md:flex-row-reverse">
              <!-- Icon -->
              <div class="flex-shrink-0 w-8 h-8 md:w-12 md:h-12 bg-red-600 rounded-full flex items-center justify-center relative z-10">
                <svg class="w-4 h-4 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
              </div>

              <!-- Content -->
              <div class="ml-6 md:ml-0 md:mr-8 flex-1 md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                  <div class="flex items-center mb-4">
                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">KEBERANGKATAN</span>
                  </div>
                  <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Terbang ke Tanah Suci</h3>
                  <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Antar jemput bandara</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Check-in & boarding assistance</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Pendampingan selama perjalanan</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- In Makkah -->
          <div class="relative flex items-center">
            <div class="flex items-center w-full">
              <!-- Icon -->
              <div class="flex-shrink-0 w-8 h-8 md:w-12 md:h-12 bg-yellow-600 rounded-full flex items-center justify-center relative z-10">
                <svg class="w-4 h-4 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>

              <!-- Content -->
              <div class="ml-6 md:ml-8 flex-1 md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                  <div class="flex items-center mb-4">
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">DI MAKKAH</span>
                  </div>
                  <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Ibadah Umroh</h3>
                  <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Tawaf di Masjidil Haram</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Sa'i Shafa dan Marwah</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Tahallul & city tour</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- In Madinah -->
          <div class="relative flex items-center md:flex-row-reverse">
            <div class="flex items-center w-full md:flex-row-reverse">
              <!-- Icon -->
              <div class="flex-shrink-0 w-8 h-8 md:w-12 md:h-12 bg-green-600 rounded-full flex items-center justify-center relative z-10">
                <svg class="w-4 h-4 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>

              <!-- Content -->
              <div class="ml-6 md:ml-0 md:mr-8 flex-1 md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                  <div class="flex items-center mb-4">
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">DI MADINAH</span>
                  </div>
                  <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Ziarah & Ibadah</h3>
                  <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Shalat di Masjid Nabawi</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Ziarah Raudhah & makam Rasul</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Ziarah Masjid Quba & tempat bersejarah</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Return -->
          <div class="relative flex items-center">
            <div class="flex items-center w-full">
              <!-- Icon -->
              <div class="flex-shrink-0 w-8 h-8 md:w-12 md:h-12 bg-purple-600 rounded-full flex items-center justify-center relative z-10">
                <svg class="w-4 h-4 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
              </div>

              <!-- Content -->
              <div class="ml-6 md:ml-8 flex-1 md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                  <div class="flex items-center mb-4">
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">KEPULANGAN</span>
                  </div>
                  <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Kembali ke Tanah Air</h3>
                  <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Pengurusan keberangkatan</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Penjemputan di bandara Indonesia</span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span>Follow up & after service</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Features -->
      <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Guide Berpengalaman</h3>
          <p class="text-gray-600">Tim guide profesional yang sudah puluhan kali memimpin jamaah umroh</p>
        </div>

        <div class="text-center">
          <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">24/7 Support</h3>
          <p class="text-gray-600">Bantuan dan support selama 24 jam penuh selama perjalanan umroh</p>
        </div>

        <div class="text-center">
          <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Jaminan Keamanan</h3>
          <p class="text-gray-600">Asuransi perjalanan dan jaminan keamanan penuh selama ibadah umroh</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// No props needed for this component as it's static content
</script>