<template>

    <Head :title="`Detail Payment Review - ${jamaahData.nama_lengkap}`" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('admin.payment.review')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Detail Payment Review - {{ jamaahData.nama_lengkap }}
                    </h2>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getStatusBadgeClass(jamaahData.status)">
                        {{ jamaahData.status_display }}
                    </span>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Personal Information -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Data Pribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.nama_lengkap || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.email || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">NIK</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.nik || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ jamaahData.jenis_kelamin === 'L' ? 'Laki-laki' : jamaahData.jenis_kelamin === 'P'
                                    ?
                                    'Perempuan' : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tempat Lahir</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.tempat_lahir || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Lahir</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.tanggal_lahir || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Usia</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.usia ? jamaahData.usia + ' tahun' :
                                    '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">No. Telepon</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.no_telepon || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pekerjaan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.pekerjaan || '-' }}</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3">
                                <label class="text-sm font-medium text-gray-500">Alamat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.alamat || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Umroh Package Information -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Paket Umroh</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Paket ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.paket_id || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Marketing</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.nama_marketing || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Rencana Keberangkatan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.rencana_keberangkatan || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Program Talangan</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="jamaahData.program_talangan ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                        {{ jamaahData.program_talangan ? 'Ya' : 'Tidak' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Sistem Pembayaran</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.sistem_pembayaran || '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Pelunasan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.tgl_pelunasan || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jumlah DP</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ formatCurrency(jamaahData.dp_paid) }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status Pembayaran</label>
                                <div class="mt-1 space-y-2">
                                    <div class="flex items-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2"
                                            :class="jamaahData.data_approved_by_cs ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                            CS: {{ jamaahData.data_approved_by_cs ? 'Approved' : 'Pending' }}
                                        </span>
                                        <span v-if="jamaahData.cs_approval_at" class="text-xs text-gray-500">
                                            {{ jamaahData.cs_approval_at }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2"
                                            :class="jamaahData.payment_approved_by_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                            Admin: {{ jamaahData.payment_approved_by_admin ? 'Approved' : 'Pending' }}
                                        </span>
                                        <span v-if="jamaahData.admin_approval_at" class="text-xs text-gray-500">
                                            {{ jamaahData.admin_approval_at }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer Proof -->
                <div class="bg-white overflow-hidden shadow rounded-lg" v-if="jamaahData.bukti_transfer">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Bukti Transfer</h3>
                        <div class="mt-1">
                            <img :src="`/storage/${jamaahData.bukti_transfer}`"
                                :alt="'Bukti transfer ' + jamaahData.nama_lengkap"
                                class="max-w-md h-auto rounded-lg shadow-md border border-gray-200"
                                @error="onImageError">
                            <div class="mt-2">
                                <a :href="`/storage/${jamaahData.bukti_transfer}`" target="_blank"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Lihat Full Size
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jamaah Rombongan -->
                <div class="bg-white overflow-hidden shadow rounded-lg"
                    v-if="jamaahData.jamaah_rombongan && jamaahData.jamaah_rombongan.length > 0">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Jamaah Rombongan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NIK</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tempat/Tgl Lahir</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            JK</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No. Telp</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(jamaah, index) in jamaahData.jamaah_rombongan" :key="index">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ jamaah.nama ||
                                            '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ jamaah.nik ||
                                            '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{
                                            jamaah.tempat_tanggal_lahir || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ jamaah.jenis_kelamin === 'L' ? 'L' : jamaah.jenis_kelamin === 'P' ? 'P' :
                                            '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ jamaah.no_telp
                                            || '-'
                                            }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Cicilan Data -->
                <div class="bg-white overflow-hidden shadow rounded-lg"
                    v-if="jamaahData.cicilan_data && jamaahData.cicilan_data.length > 0">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Data Cicilan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            DP</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tenor</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cicilan/Bulan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(cicilan, index) in jamaahData.cicilan_data" :key="index">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{
                                            formatCurrency(cicilan.dp) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ cicilan.tenor
                                            }} bulan
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{
                                            formatCurrency(cicilan.cicilan_perbulan) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Tambahan</h3>
                        <div class="space-y-4">
                            <div v-if="jamaahData.sumber_info_mmb">
                                <label class="text-sm font-medium text-gray-500">Sumber Info MMB</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.sumber_info_mmb }}</p>
                            </div>
                            <div v-if="jamaahData.kelengkapan_dokumen">
                                <label class="text-sm font-medium text-gray-500">Kelengkapan Dokumen</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{
                                    jamaahData.kelengkapan_dokumen }}
                                </p>
                            </div>
                            <div v-if="jamaahData.request_khusus">
                                <label class="text-sm font-medium text-gray-500">Request Khusus</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ jamaahData.request_khusus
                                    }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Sistem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Current Step</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.current_step }} - {{
                                    jamaahData.step_name }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Daftar</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.created_at }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Update</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.updated_at }}</p>
                            </div>
                            <div v-if="jamaahData.registration_completed_at">
                                <label class="text-sm font-medium text-gray-500">Registrasi Selesai</label>
                                <p class="mt-1 text-sm text-gray-900">{{ jamaahData.registration_completed_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white px-4 py-5 sm:p-6 shadow rounded-lg">
                    <div class="flex flex-wrap gap-4">
                        <button v-if="jamaahData.can_cs_approve && !jamaahData.data_approved_by_cs" @click="approveByCS"
                            :disabled="processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Approve oleh CS
                        </button>

                        <button v-if="jamaahData.can_admin_approve && !jamaahData.payment_approved_by_admin"
                            @click="approveByAdmin" :disabled="processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Approve Pembayaran
                        </button>

                        <button v-if="jamaahData.can_admin_approve && jamaahData.payment_approved_by_admin"
                            @click="rejectPayment" :disabled="processing"
                            class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    jamaahData: {
        type: Object,
        required: true
    }
})

const processing = ref(false)

const formatCurrency = (value) => {
    if (!value) return 'Rp 0'
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value)
}

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'completed':
            return 'bg-green-100 text-green-800'
        case 'processing':
            return 'bg-yellow-100 text-yellow-800'
        case 'pending':
            return 'bg-gray-100 text-gray-800'
        case 'rejected':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const onImageError = (event) => {
    event.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIxIDlWN0MyMSA1Ljg5NTQzIDIwLjEwNDYgNSAxOSA1SDVDMy44OTU0MyA1IDMgNS44OTU0MyAzIDdWOUgyMVoiIGZpbGw9IiNFNUU3RUIiLz4KPHBhdGggZD0iTTMgOVYxN0MzIDE4LjEwNDYgMy44OTU0MyAxOSA1IDE5SDE5QzIwLjEwNDYgMTkgMjEgMTguMTA0NiAyMSAxN1Y5SDNaIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik05IDEzSDEyVjE2SDlWMTNaIiBmaWxsPSIjRDFENUVCIi8+CjwvcGF0aD4KPC9zdmc+'
    event.target.alt = 'Gagal memuat gambar'
}

const approveByCS = () => {
    processing.value = true
    router.patch(route('cs.jamaah.approve.data', props.jamaahData.id), {}, {
        onSuccess: () => {
            // Refresh the page to show updated data
            window.location.reload()
        },
        onError: (errors) => {
            console.error('CS approval error:', errors)
            alert('Gagal approve data jamaah')
        },
        onFinish: () => {
            processing.value = false
        }
    })
}

const approveByAdmin = () => {
    processing.value = true
    router.patch(route('admin.jamaah.approve.payment', props.jamaahData.id), {}, {
        onSuccess: () => {
            // Refresh the page to show updated data
            window.location.reload()
        },
        onError: (errors) => {
            console.error('Admin approval error:', errors)
            alert('Gagal approve pembayaran')
        },
        onFinish: () => {
            processing.value = false
        }
    })
}

const rejectPayment = () => {
    if (confirm('Apakah Anda yakin ingin menolak pembayaran ini? Jamaah akan diminta upload ulang bukti transfer.')) {
        processing.value = true
        router.patch(route('admin.jamaah.reject.payment', props.jamaahData.id), {}, {
            onSuccess: () => {
                // Refresh the page to show updated data
                window.location.reload()
            },
            onError: (errors) => {
                console.error('Payment rejection error:', errors)
                alert('Gagal reject pembayaran')
            },
            onFinish: () => {
                processing.value = false
            }
        })
    }
}
</script>
