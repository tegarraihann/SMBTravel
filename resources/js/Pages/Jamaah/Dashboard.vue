<template>

    <Head title="Dashboard Jamaah" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Dashboard Jamaah
                    </h2>
                    <p class="text-gray-600 mt-1">Selamat datang, {{ jamaah.nama_lengkap }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <Link :href="route('landing')" class="text-blue-600 hover:text-blue-800 text-sm">
                    ← Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </template> -->

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Progress Bar -->
                <ProgressBar :current-step="jamaah.current_step" :jamaah-data="jamaah"
                    @step-action="handleStepAction" @navigate-to-step="handleStepNavigation" />

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Package Info -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Paket yang Dipilih</h3>
                            <div v-if="selectedPackage.id" class="flex items-start space-x-4">
                                <img :src="selectedPackage.image" :alt="selectedPackage.name"
                                    class="w-24 h-24 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ selectedPackage.name }}</h4>
                                    <p class="text-gray-600 text-sm mt-1">{{ selectedPackage.duration }}</p>
                                    <div class="mt-2">
                                        <span class="text-lg font-bold text-blue-600">
                                            Rp {{ formatPrice(selectedPackage.price) }}
                                        </span>
                                        <span class="text-gray-500 text-sm ml-2">/ orang</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-900 mb-2">Belum Memilih Paket</h4>
                                <p class="text-gray-500 text-sm mb-4">Silakan lengkapi pendaftaran untuk memilih paket umroh</p>
                                <Link :href="route('jamaah.daftar')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Pilih Paket
                                </Link>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div v-if="selectedPackage.id" class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h3>
                                <div v-if="jamaah.program_talangan" class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    Program Talangan
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Paket</span>
                                    <span class="font-semibold">Rp {{ formatPrice(selectedPackage.price) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">DP yang Dibayar</span>
                                    <span class="font-semibold text-green-600">
                                        Rp {{ formatPrice(jamaah.dp_paid || selectedPackage.dp) }}
                                    </span>
                                </div>

                                <!-- Show installment info if program talangan -->
                                <div v-if="jamaah.program_talangan" class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-blue-700 font-medium">Pembayaran Cicilan</span>
                                        <Link :href="route('jamaah.installments')"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            Lihat Detail →
                                        </Link>
                                    </div>
                                    <p class="text-blue-600 text-xs mt-1">
                                        Sisa pembayaran akan dibagi menjadi 5 cicilan bulanan
                                    </p>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Sisa Pembayaran</span>
                                        <span class="font-bold" :class="jamaah.program_talangan ? 'text-blue-600' : 'text-red-600'">
                                            Rp {{ formatPrice(selectedPackage.price - (jamaah.dp_paid || selectedPackage.dp)) }}
                                        </span>
                                    </div>
                                    <p v-if="jamaah.program_talangan" class="text-xs text-gray-500 mt-1">
                                        Akan dibayar dengan sistem cicilan
                                    </p>
                                </div>
                            </div>

                            <!-- Payment Actions -->
                            <div v-if="jamaah.current_step === 2" class="mt-6 pt-6 border-t space-y-3">
                                <button @click="showPaymentModal = true"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                                    Bayar DP Sekarang
                                </button>

                                <!-- Show installment info notice -->
                                <div v-if="jamaah.program_talangan" class="text-center">
                                    <p class="text-xs text-gray-500">
                                        Setelah DP disetujui, jadwal cicilan akan dibuat otomatis
                                    </p>
                                </div>
                            </div>

                            <!-- Installment Quick Access -->
                            <div v-if="jamaah.program_talangan && jamaah.current_step > 2" class="mt-6 pt-6 border-t">
                                <Link :href="route('jamaah.installments')"
                                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 inline-block text-center">
                                    Kelola Pembayaran Cicilan
                                </Link>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div v-if="jamaah.current_step >= 4" class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Dokumen Persyaratan</h3>
                                <Link :href="route('jamaah.dokumen')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Kelola Semua →
                                </Link>
                            </div>

                            <div class="space-y-3">
                                <div v-for="(doc, key) in requiredDocuments" :key="key"
                                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                            :class="isDocumentUploaded(key) ? 'bg-green-100' : 'bg-gray-100'">
                                            <svg class="w-4 h-4" :class="isDocumentUploaded(key) ? 'text-green-600' : 'text-gray-400'"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path v-if="isDocumentUploaded(key)" fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"/>
                                                <path v-else fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ doc }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ getDocumentDescription(key) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full"
                                            :class="isDocumentUploaded(key) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                                            {{ isDocumentUploaded(key) ? 'Sudah Upload' : 'Belum Upload' }}
                                        </span>
                                        <button @click="openDocumentUpload(key)"
                                            v-if="!isDocumentUploaded(key)"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Upload
                                        </button>
                                        <button @click="viewDocument(key)"
                                            v-else
                                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Lihat
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t">
                                <Link :href="route('jamaah.dokumen')"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 inline-block text-center">
                                    Upload Semua Dokumen
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Installment Summary -->
                        <div v-if="jamaah.program_talangan && jamaah.current_step > 2 && installmentData" class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Cicilan Anda</h3>
                                <Link :href="route('jamaah.installments')"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Lihat Semua →
                                </Link>
                            </div>

                            <div class="space-y-4">
                                <!-- Payment Progress -->
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600">Progress Pembayaran</span>
                                        <span class="font-medium text-blue-600">{{ installmentData.payment_progress }}%</span>
                                    </div>
                                    <div class="w-full bg-white rounded-full h-2 border">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" :style="{ width: installmentData.payment_progress + '%' }"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ installmentData.paid_count }} dari {{ installmentData.total_count }} cicilan telah dibayar</p>
                                </div>

                                <!-- Next Payment -->
                                <div v-if="installmentData.next_due" class="bg-white rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Cicilan ke-{{ installmentData.next_due.installment_number }}</p>
                                            <p class="text-xs text-gray-500">Jatuh tempo {{ formatDateSimple(installmentData.next_due.due_date) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-blue-600">Rp {{ formatCurrencySimple(installmentData.next_due.amount) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <Link :href="route('jamaah.installments')"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-md transition-colors duration-200 inline-block text-center">
                                            Bayar Sekarang
                                        </Link>
                                    </div>
                                </div>

                                <!-- No Next Payment -->
                                <div v-else-if="!installmentData.is_payment_complete" class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="text-center text-gray-500">
                                        <p class="text-sm">Tidak ada cicilan yang jatuh tempo</p>
                                        <p class="text-xs">Semua cicilan sudah terbayar atau belum ada jadwal</p>
                                    </div>
                                </div>

                                <!-- Overdue Alert -->
                                <div v-if="installmentData.overdue_payments && installmentData.overdue_payments.length > 0" class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <svg class="h-4 w-4 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-2">
                                            <p class="text-sm font-medium text-red-800">{{ installmentData.overdue_payments.length }} Pembayaran Terlambat</p>
                                            <p class="text-xs text-red-600">Segera lakukan pembayaran untuk menghindari denda</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Complete -->
                                <div v-if="installmentData.is_payment_complete" class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <svg class="h-4 w-4 text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-2">
                                            <p class="text-sm font-medium text-green-800">Pembayaran Cicilan Selesai</p>
                                            <p class="text-xs text-green-600">Semua cicilan telah dibayar lunas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h3>
                            <div class="space-y-3">
                                <a :href="`https://wa.me/6281234567890?text=Halo, saya ${jamaah.nama_lengkap} dengan ID ${jamaah.id}. Saya memerlukan bantuan mengenai pendaftaran umroh.`"
                                    target="_blank"
                                    class="flex items-center space-x-3 p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">WhatsApp</p>
                                        <p class="text-sm text-gray-500">Chat langsung</p>
                                    </div>
                                </a>

                                <a href="tel:0761-12345"
                                    class="flex items-center space-x-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Telepon</p>
                                        <p class="text-sm text-gray-500">0761-12345</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Kegiatan</h3>
                            <div class="space-y-4">
                                <div v-for="event in timeline" :key="event.id" class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ event.title }}</p>
                                        <p class="text-xs text-gray-500">{{ event.date }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div v-if="showPaymentModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pembayaran Down Payment</h3>

                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-700">
                            <strong>Transfer ke:</strong><br>
                            Bank BCA<br>
                            No. Rek: 1234567890<br>
                            A.n: PT Travel Umroh SMB
                        </p>
                    </div>

                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 mb-2">Jumlah yang harus dibayar:</p>
                        <p class="text-2xl font-bold text-blue-600">
                            Rp {{ formatPrice(selectedPackage.dp) }}
                        </p>
                    </div>

                    <div class="border-t pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Bukti Pembayaran
                        </label>
                        <input type="file" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button @click="showPaymentModal = false"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg">
                        Batal
                    </button>
                    <button @click="submitPayment"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                        Upload & Konfirmasi
                    </button>
                </div>
            </div>
        </div>

        <!-- Verification Status Modal -->
        <div v-if="showVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Status Verifikasi Dokumen & Pembayaran</h3>
                        <button @click="showVerificationModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Document Verification Status -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="(jamaah.documents_verified && jamaah.data_approved_by_cs) ? 'text-green-500' : 'text-yellow-500'" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="jamaah.documents_verified && jamaah.data_approved_by_cs" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Status Dokumen & Data
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Dokumen Upload:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaah.documents_uploaded_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                                        {{ jamaah.documents_uploaded_at ? 'Sudah Upload' : 'Belum Upload' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Persetujuan CS:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaah.data_approved_by_cs ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ jamaah.data_approved_by_cs ? 'Disetujui' : 'Menunggu Persetujuan' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Verifikasi Dokumen:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaah.documents_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ jamaah.documents_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                                <p v-if="jamaah.documents_uploaded_at" class="text-xs text-gray-500">
                                    Upload: {{ new Date(jamaah.documents_uploaded_at).toLocaleDateString('id-ID') }}
                                </p>
                            </div>
                        </div>

                        <!-- Payment Verification Status -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="jamaah.payment_approved_by_admin ? 'text-green-500' : 'text-yellow-500'" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="jamaah.payment_approved_by_admin" fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Status Pembayaran DP
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Bukti Transfer:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaah.bukti_transfer ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                                        {{ jamaah.bukti_transfer ? 'Sudah Upload' : 'Belum Upload' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Jumlah DP:</span>
                                    <span class="font-medium text-gray-900">
                                        Rp {{ jamaah.dp_amount_paid ? new Intl.NumberFormat('id-ID').format(jamaah.dp_amount_paid) : (jamaah.dp_paid ? new Intl.NumberFormat('id-ID').format(jamaah.dp_paid) : '0') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Verifikasi Admin:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaah.payment_approved_by_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ jamaah.payment_approved_by_admin ? 'Disetujui' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pelunasan Status -->
                        <div v-if="jamaah.current_step >= 3" class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="jamaah.is_payment_complete ? 'text-green-500' : 'text-blue-500'" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Status Pelunasan
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Progress Pembayaran:</span>
                                    <span class="font-medium text-blue-600">{{ jamaah.payment_progress || 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-blue-600 h-2 rounded-full" :style="{ width: (jamaah.payment_progress || 0) + '%' }"></div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaah.is_payment_complete ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ jamaah.is_payment_complete ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </div>
                                <div v-if="jamaah.total_outstanding" class="flex justify-between items-center">
                                    <span class="text-gray-600">Sisa Pembayaran:</span>
                                    <span class="font-medium text-red-600">
                                        Rp {{ new Intl.NumberFormat('id-ID').format(jamaah.total_outstanding) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="showVerificationModal = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departure Documents Modal -->
        <div v-if="showDepartureDocumentsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Status Dokumen Keberangkatan</h3>
                        <button @click="showDepartureDocumentsModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <!-- Ticket Status -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="getTicketStatusColor()" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="jamaah.ticket_status === 'completed'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    <path v-else-if="jamaah.ticket_status === 'processing'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                E-Ticket Pesawat
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status Pemrosesan:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="getTicketStatusBadge()">
                                        {{ getTicketStatusText() }}
                                    </span>
                                </div>
                                <div v-if="jamaah.ticket_processed_at" class="flex justify-between items-center">
                                    <span class="text-gray-600">Diproses pada:</span>
                                    <span class="text-sm text-gray-900">
                                        {{ new Date(jamaah.ticket_processed_at).toLocaleDateString('id-ID') }}
                                    </span>
                                </div>
                                <div v-if="jamaah.ticket_file" class="flex justify-between items-center">
                                    <span class="text-gray-600">E-Ticket:</span>
                                    <a :href="route('jamaah.departure.download', 'ticket')" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Download E-Ticket
                                    </a>
                                </div>
                                <div v-else class="flex justify-between items-center">
                                    <span class="text-gray-600">E-Ticket:</span>
                                    <span class="text-sm text-gray-500">Belum tersedia</span>
                                </div>
                                <div v-if="jamaah.ticket_notes" class="mt-2">
                                    <span class="text-gray-600 text-sm">Catatan:</span>
                                    <p class="text-sm text-gray-900 mt-1 bg-gray-50 p-2 rounded">{{ jamaah.ticket_notes }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Visa Status -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="getVisaStatusColor()" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="jamaah.visa_status === 'completed'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    <path v-else-if="jamaah.visa_status === 'processing'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Visa Saudi Arabia
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status Pemrosesan:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="getVisaStatusBadge()">
                                        {{ getVisaStatusText() }}
                                    </span>
                                </div>
                                <div v-if="jamaah.visa_processed_at" class="flex justify-between items-center">
                                    <span class="text-gray-600">Diproses pada:</span>
                                    <span class="text-sm text-gray-900">
                                        {{ new Date(jamaah.visa_processed_at).toLocaleDateString('id-ID') }}
                                    </span>
                                </div>
                                <div v-if="jamaah.visa_file" class="flex justify-between items-center">
                                    <span class="text-gray-600">Dokumen Visa:</span>
                                    <a :href="route('jamaah.departure.download', 'visa')" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Download Visa
                                    </a>
                                </div>
                                <div v-else class="flex justify-between items-center">
                                    <span class="text-gray-600">Dokumen Visa:</span>
                                    <span class="text-sm text-gray-500">Belum tersedia</span>
                                </div>
                                <div v-if="jamaah.visa_notes" class="mt-2">
                                    <span class="text-gray-600 text-sm">Catatan:</span>
                                    <p class="text-sm text-gray-900 mt-1 bg-gray-50 p-2 rounded">{{ jamaah.visa_notes }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Overall Status -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gradient-to-r from-blue-50 to-purple-50">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="getOverallStatusColor()" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="isReadyForDeparture()" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Status Keseluruhan
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Kesiapan Keberangkatan:</span>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full" :class="getReadinessStatusBadge()">
                                        {{ getReadinessStatusText() }}
                                    </span>
                                </div>
                                <div v-if="jamaah.rencana_keberangkatan" class="flex justify-between items-center">
                                    <span class="text-gray-600">Rencana Keberangkatan:</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ new Date(jamaah.rencana_keberangkatan).toLocaleDateString('id-ID', {
                                            weekday: 'long',
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        }) }}
                                    </span>
                                </div>
                                <div class="mt-3 p-3 bg-white rounded-lg border">
                                    <p class="text-sm text-gray-600 mb-2">Informasi Penting:</p>
                                    <ul class="text-xs text-gray-500 space-y-1">
                                        <li v-if="!isReadyForDeparture()">• {{ getNextRequiredAction() }}</li>
                                        <li v-if="isReadyForDeparture()">• Semua dokumen sudah siap untuk keberangkatan</li>
                                        <li>• Hubungi customer service jika ada pertanyaan</li>
                                        <li v-if="jamaah.rencana_keberangkatan">• Siap-siap keberangkatan pada {{ new Date(jamaah.rencana_keberangkatan).toLocaleDateString('id-ID') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button v-if="isReadyForDeparture()" @click="downloadAllDocuments()"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Download Semua
                        </button>
                        <button @click="showDepartureDocumentsModal = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ProgressBar from '@/Components/Jamaah/ProgressBar.vue'

const props = defineProps({
    jamaah: {
        type: Object,
        required: true
    },
    selectedPackage: {
        type: Object,
        default: null
    },
    installmentData: {
        type: Object,
        default: null
    }
})

const showPaymentModal = ref(false)

// Computed property to get selected package or fallback
const selectedPackage = computed(() => {
    if (props.selectedPackage) {
        return props.selectedPackage
    }

    // Fallback data if no package selected
    return {
        id: null,
        name: 'Belum memilih paket',
        duration: '-',
        price: 0,
        dp: 0,
        image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
    }
})

// Document configuration
const requiredDocuments = ref({
    foto_ktp: 'Foto KTP',
    foto_kk: 'Foto Kartu Keluarga',
    foto_paspor: 'Foto Paspor',
    foto_diri: 'Foto 4x6'
})

const documentDescriptions = {
    foto_ktp: 'Kartu Tanda Penduduk yang masih berlaku',
    foto_kk: 'Kartu Keluarga asli',
    foto_paspor: 'Paspor dengan masa berlaku minimal 6 bulan',
    foto_diri: 'Foto berwarna latar belakang putih ukuran 4x6'
}

const timeline = ref([
    {
        id: 1,
        title: 'Pendaftaran berhasil',
        date: '22 September 2025'
    },
    {
        id: 2,
        title: 'Pembayaran DP',
        date: 'Menunggu pembayaran'
    },
    {
        id: 3,
        title: 'Manasik Umroh',
        date: 'TBD'
    },
    {
        id: 4,
        title: 'Keberangkatan',
        date: 'TBD'
    }
])

const formatPrice = (price) => {
    if (!price || price === 0) {
        return '-'
    }
    return (price / 1000000).toFixed(0) + ' Juta'
}

const formatCurrencySimple = (amount) => {
    return (amount / 1000000).toFixed(1) + ' Juta'
}

const formatDateSimple = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

const handleStepAction = (action) => {
    switch (action) {
        case 'continue-registration':
            router.visit(route('jamaah.daftar'))
            break
        case 'pay-dp':
            router.visit(route('jamaah.pembayaran'))
            break
        case 'contact-admin':
            window.open(`https://wa.me/6281234567890?text=Halo, saya ${props.jamaah.nama_lengkap} dengan ID ${props.jamaah.id}. Saya memerlukan bantuan mengenai pendaftaran umroh.`, '_blank')
            break
        case 'view-payment-details':
            router.visit(route('jamaah.pembayaran'))
            break
        case 'view-installments':
            router.visit(route('jamaah.installments'))
            break
        case 'upload-documents':
            router.visit(route('jamaah.dokumen'))
            break
        case 'pay-remaining-balance':
            router.visit(route('jamaah.installments'))
            break
        case 'check-verification-status':
            // Show detailed verification status modal or scroll to status section
            showVerificationStatus()
            break
        case 'view-manasik-schedule':
            router.visit(route('jamaah.manasik'))
            break
        case 'view-itinerary':
            // Navigate to itinerary
            break
        case 'check-departure-documents':
            showDepartureDocuments()
            break
    }
}

const handleStepNavigation = (stepId) => {
    // Update current step for testing purposes
    // Note: This is for testing only - in production, steps should be controlled by backend
    console.log(`🧪 Testing navigation to step ${stepId}`)

    // Update the jamaah data to reflect the new step
    props.jamaah.current_step = stepId

    // Navigate to appropriate page based on step
    switch (stepId) {
        case 1:
            router.visit(route('jamaah.daftar'))
            break
        case 2:
            router.visit(route('jamaah.pembayaran'))
            break
        case 3:
            router.visit(route('jamaah.installments'))
            break
        case 4:
            // Stay on dashboard for completed step
            break
        default:
            // Stay on current page
            break
    }
}

const submitPayment = () => {
    // Get file input
    const fileInput = document.querySelector('input[type="file"]')
    const file = fileInput.files[0]

    if (!file) {
        alert('Silakan pilih file bukti pembayaran terlebih dahulu')
        return
    }

    // Create form data
    const formData = new FormData()
    formData.append('bukti_transfer', file)

    // Submit to backend
    router.post(route('jamaah.pembayaran.upload'), formData, {
        onSuccess: (response) => {
            showPaymentModal.value = false
            // Refresh page to show updated step
            window.location.reload()
        },
        onError: (errors) => {
            console.error('Upload error:', errors)
            alert('Gagal upload bukti pembayaran. Silakan coba lagi.')
        }
    })
}

// Document methods
const isDocumentUploaded = (documentKey) => {
    return jamaahData.value && jamaahData.value[documentKey]
}

const getDocumentDescription = (documentKey) => {
    return documentDescriptions[documentKey] || ''
}

const openDocumentUpload = (documentKey) => {
    // Redirect to specific document upload or open modal
    window.location.href = route('jamaah.dokumen') + '#' + documentKey
}

const viewDocument = (documentKey) => {
    if (jamaahData.value && jamaahData.value[documentKey]) {
        window.open('/storage/' + jamaahData.value[documentKey], '_blank')
    }
}

// Get jamaah data from props for document checking
const jamaahData = computed(() => props.jamaah)

// Verification status modal
const showVerificationModal = ref(false)

const showVerificationStatus = () => {
    showVerificationModal.value = true
}

// Departure documents modal
const showDepartureDocumentsModal = ref(false)

const showDepartureDocuments = () => {
    showDepartureDocumentsModal.value = true
}

// Helper functions for departure documents modal
const getTicketStatusColor = () => {
    switch (props.jamaah.ticket_status) {
        case 'completed': return 'text-green-500'
        case 'processing': return 'text-blue-500'
        case 'pending': return 'text-yellow-500'
        default: return 'text-gray-500'
    }
}

const getTicketStatusBadge = () => {
    switch (props.jamaah.ticket_status) {
        case 'completed': return 'bg-green-100 text-green-800'
        case 'processing': return 'bg-blue-100 text-blue-800'
        case 'pending': return 'bg-yellow-100 text-yellow-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

const getTicketStatusText = () => {
    switch (props.jamaah.ticket_status) {
        case 'completed': return 'Selesai'
        case 'processing': return 'Sedang Diproses'
        case 'pending': return 'Menunggu Proses'
        default: return 'Belum Diproses'
    }
}

const getVisaStatusColor = () => {
    switch (props.jamaah.visa_status) {
        case 'completed': return 'text-green-500'
        case 'processing': return 'text-blue-500'
        case 'pending': return 'text-yellow-500'
        default: return 'text-gray-500'
    }
}

const getVisaStatusBadge = () => {
    switch (props.jamaah.visa_status) {
        case 'completed': return 'bg-green-100 text-green-800'
        case 'processing': return 'bg-blue-100 text-blue-800'
        case 'pending': return 'bg-yellow-100 text-yellow-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

const getVisaStatusText = () => {
    switch (props.jamaah.visa_status) {
        case 'completed': return 'Selesai'
        case 'processing': return 'Sedang Diproses'
        case 'pending': return 'Menunggu Proses'
        default: return 'Belum Diproses'
    }
}

const getOverallStatusColor = () => {
    return isReadyForDeparture() ? 'text-green-500' : 'text-blue-500'
}

const isReadyForDeparture = () => {
    return props.jamaah.ticket_status === 'completed' && props.jamaah.visa_status === 'completed'
}

const getReadinessStatusBadge = () => {
    return isReadyForDeparture() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
}

const getReadinessStatusText = () => {
    return isReadyForDeparture() ? 'Siap Berangkat' : 'Dalam Proses'
}

const getNextRequiredAction = () => {
    if (props.jamaah.ticket_status === 'pending' && props.jamaah.visa_status === 'pending') {
        return 'Menunggu pemrosesan tiket dan visa'
    } else if (props.jamaah.ticket_status === 'pending') {
        return 'Menunggu pemrosesan tiket pesawat'
    } else if (props.jamaah.visa_status === 'pending') {
        return 'Menunggu pemrosesan visa Saudi Arabia'
    } else if (props.jamaah.ticket_status === 'processing' || props.jamaah.visa_status === 'processing') {
        return 'Dokumen sedang dalam proses'
    }
    return 'Semua dokumen dalam proses'
}

const downloadAllDocuments = () => {
    const documents = []
    if (props.jamaah.ticket_file) {
        documents.push({ name: 'E-Ticket_Pesawat', url: route('jamaah.departure.download', 'ticket') })
    }
    if (props.jamaah.visa_file) {
        documents.push({ name: 'Visa_Saudi_Arabia', url: route('jamaah.departure.download', 'visa') })
    }

    if (documents.length === 0) {
        alert('Belum ada dokumen yang tersedia untuk diunduh')
        return
    }

    // Download each document
    documents.forEach((doc, index) => {
        setTimeout(() => {
            const link = document.createElement('a')
            link.href = doc.url
            link.download = doc.name
            link.target = '_blank'
            document.body.appendChild(link)
            link.click()
            document.body.removeChild(link)
        }, index * 1000) // Delay each download by 1 second
    })
}
</script>
