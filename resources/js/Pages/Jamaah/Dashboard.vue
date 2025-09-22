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
                    @step-action="handleStepAction" />

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Package Info -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Paket yang Dipilih</h3>
                            <div class="flex items-start space-x-4">
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
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h3>
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
                                <div class="border-t pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Sisa Pembayaran</span>
                                        <span class="font-bold text-red-600">
                                            Rp {{ formatPrice(selectedPackage.price - (jamaah.dp_paid ||
                                            selectedPackage.dp)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Actions -->
                            <div v-if="jamaah.current_step === 2" class="mt-6 pt-6 border-t">
                                <button @click="showPaymentModal = true"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                                    Bayar DP Sekarang
                                </button>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div v-if="jamaah.current_step >= 4" class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen</h3>
                            <div class="space-y-3">
                                <div v-for="doc in documents" :key="doc.name"
                                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ doc.name }}</p>
                                            <p class="text-sm text-gray-500">{{ doc.description }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full"
                                            :class="doc.status === 'uploaded' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                                            {{ doc.status === 'uploaded' ? 'Sudah Upload' : 'Belum Upload' }}
                                        </span>
                                        <button v-if="doc.status !== 'uploaded'"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Upload
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
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
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ProgressBar from '@/Components/Jamaah/ProgressBar.vue'

const props = defineProps({
    jamaah: {
        type: Object,
        required: true
    }
})

const showPaymentModal = ref(false)

// Mock data - replace with real data from backend
const selectedPackage = {
    id: 1,
    name: 'City Tour Malaysia',
    duration: 'Pekanbaru - Kuala Lumpur - Madinah',
    price: 24500000,
    dp: 3000000,
    image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
}

const documents = ref([
    {
        name: 'KTP',
        description: 'Kartu Tanda Penduduk',
        status: 'pending'
    },
    {
        name: 'Kartu Keluarga',
        description: 'Kartu Keluarga asli',
        status: 'pending'
    },
    {
        name: 'Paspor',
        description: 'Paspor dengan masa berlaku minimal 6 bulan',
        status: 'pending'
    },
    {
        name: 'Foto 4x6',
        description: 'Foto berwarna latar belakang putih',
        status: 'pending'
    }
])

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
    return (price / 1000000).toFixed(0) + ' Juta'
}

const handleStepAction = (action) => {
    switch (action) {
        case 'pay-dp':
            showPaymentModal.value = true
            break
        case 'contact-admin':
            window.open(`https://wa.me/6281234567890?text=Halo, saya ${props.jamaah.nama_lengkap} dengan ID ${props.jamaah.id}. Saya memerlukan bantuan mengenai pendaftaran umroh.`, '_blank')
            break
        case 'view-payment-details':
            // Navigate to payment details
            break
        case 'upload-documents':
            // Navigate to document upload
            break
        case 'view-manasik-schedule':
            // Navigate to manasik schedule
            break
        case 'view-itinerary':
            // Navigate to itinerary
            break
    }
}

const submitPayment = () => {
    // Handle payment submission
    alert('Bukti pembayaran berhasil diupload. Tim kami akan memverifikasi dalam 1-2 hari kerja.')
    showPaymentModal.value = false
}
</script>
