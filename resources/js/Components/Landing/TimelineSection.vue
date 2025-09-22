<template>
  <section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
          Perjalanan Umroh Anda
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Dari keberangkatan hingga kepulangan, kami akan memandu setiap langkah perjalanan spiritual Anda
        </p>
      </div>

      <!-- Timeline -->
      <div class="relative max-w-6xl mx-auto">
        <!-- Timeline Line - Continuous vertical line -->
        <div class="absolute left-1/2 top-0 bottom-0 w-1 bg-slate-300 transform -translate-x-1/2 hidden md:block"></div>

        <!-- Timeline Items -->
        <div class="space-y-16">
          <!-- Pre-Departure -->
          <div class="relative flex flex-col md:flex-row items-center">
            <!-- Left Image -->
            <div class="w-full md:w-5/12 mb-6 md:mb-0">
              <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img
                  src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                  alt="Bimbingan Manasik"
                  class="w-full h-64 object-cover"
                >
                <div class="absolute inset-0 bg-blue-600 bg-opacity-30"></div>
              </div>
            </div>

            <!-- Center Timeline Dot -->
            <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-16 h-16 bg-white rounded-full items-center justify-center z-20 shadow-lg border-4 border-blue-600">
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
            </div>

            <!-- Right Content -->
            <div class="w-full md:w-5/12 md:ml-auto">
              <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <div class="flex items-center mb-4">
                  <span class="bg-blue-100 text-blue-900 text-xs font-bold px-3 py-1.5 rounded-full">PRE-KEBERANGKATAN</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-4">Persiapan & Manasik</h3>
                <ul class="space-y-3 text-slate-700">
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Pengurusan visa & dokumen</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Bimbingan manasik komprehensif</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Perlengkapan umroh & vaksinasi</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Departure -->
          <div class="relative flex flex-col md:flex-row-reverse items-center">
            <!-- Right Image -->
            <div class="w-full md:w-5/12 mb-6 md:mb-0">
              <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img
                  src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                  alt="Keberangkatan Umroh"
                  class="w-full h-64 object-cover"
                >
                <div class="absolute inset-0 bg-red-600 bg-opacity-30"></div>
              </div>
            </div>

            <!-- Center Timeline Dot -->
            <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-16 h-16 bg-white rounded-full items-center justify-center z-20 shadow-lg border-4 border-red-600">
              <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
              </div>
            </div>

            <!-- Left Content -->
            <div class="w-full md:w-5/12 md:mr-auto">
              <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <div class="flex items-center mb-4">
                  <span class="bg-red-100 text-red-900 text-xs font-bold px-3 py-1.5 rounded-full">KEBERANGKATAN</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-4">Terbang ke Tanah Suci</h3>
                <ul class="space-y-3 text-slate-700">
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Antar jemput bandara</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Check-in & boarding assistance</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Pendampingan selama perjalanan</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- In Makkah -->
          <div class="relative flex flex-col md:flex-row items-center">
            <!-- Left Image -->
            <div class="w-full md:w-5/12 mb-6 md:mb-0">
              <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img
                  src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                  alt="Masjidil Haram Makkah"
                  class="w-full h-64 object-cover"
                >
                <div class="absolute inset-0 bg-yellow-600 bg-opacity-30"></div>
              </div>
            </div>

            <!-- Center Timeline Dot -->
            <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-16 h-16 bg-white rounded-full items-center justify-center z-20 shadow-lg border-4 border-yellow-600">
              <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
              </div>
            </div>

            <!-- Right Content -->
            <div class="w-full md:w-5/12 md:ml-auto">
              <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <div class="flex items-center mb-4">
                  <span class="bg-yellow-100 text-yellow-900 text-xs font-bold px-3 py-1.5 rounded-full">DI MAKKAH</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-4">Ibadah Umroh</h3>
                <ul class="space-y-3 text-slate-700">
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Tawaf di Masjidil Haram</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Sa'i Shafa dan Marwah</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Tahallul & city tour</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- In Madinah -->
          <div class="relative flex flex-col md:flex-row-reverse items-center">
            <!-- Right Image -->
            <div class="w-full md:w-5/12 mb-6 md:mb-0">
              <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img
                  src="https://images.unsplash.com/photo-1678307830479-42dc43d18e5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                  alt="Masjid Nabawi Madinah"
                  class="w-full h-64 object-cover"
                >
                <div class="absolute inset-0 bg-slate-600 bg-opacity-30"></div>
              </div>
            </div>

            <!-- Center Timeline Dot -->
            <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-16 h-16 bg-white rounded-full items-center justify-center z-20 shadow-lg border-4 border-slate-600">
              <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
            </div>

            <!-- Left Content -->
            <div class="w-full md:w-5/12 md:mr-auto">
              <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <div class="flex items-center mb-4">
                  <span class="bg-slate-200 text-slate-900 text-xs font-bold px-3 py-1.5 rounded-full">DI MADINAH</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-4">Ziarah & Ibadah</h3>
                <ul class="space-y-3 text-slate-700">
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Shalat di Masjid Nabawi</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Ziarah Raudhah & makam Rasul</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Ziarah Masjid Quba & tempat bersejarah</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Return -->
          <div class="relative flex flex-col md:flex-row items-center">
            <!-- Left Image -->
            <div class="w-full md:w-5/12 mb-6 md:mb-0">
              <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img
                  src="https://images.unsplash.com/photo-1500835556837-99ac94a94552?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                  alt="Kepulangan ke Indonesia"
                  class="w-full h-64 object-cover"
                >
                <div class="absolute inset-0 bg-blue-600 bg-opacity-30"></div>
              </div>
            </div>

            <!-- Center Timeline Dot -->
            <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-16 h-16 bg-white rounded-full items-center justify-center z-20 shadow-lg border-4 border-blue-600">
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
              </div>
            </div>

            <!-- Right Content -->
            <div class="w-full md:w-5/12 md:ml-auto">
              <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <div class="flex items-center mb-4">
                  <span class="bg-blue-100 text-blue-900 text-xs font-bold px-3 py-1.5 rounded-full">KEPULANGAN</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-4">Kembali ke Tanah Air</h3>
                <ul class="space-y-3 text-slate-700">
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Pengurusan keberangkatan</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Penjemputan di bandara Indonesia</span>
                  </li>
                  <li class="flex items-start">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-3 mt-2.5 flex-shrink-0"></span>
                    <span>Follow up & after service</span>
                  </li>
                </ul>
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