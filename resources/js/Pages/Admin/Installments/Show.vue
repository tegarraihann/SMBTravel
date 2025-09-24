<template>
    <AdminLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Detail Cicilan - {{ jamaah.nama_lengkap }}
                            </h1>
                            <p class="text-gray-600 mt-2">
                                Kelola pembayaran cicilan jamaah
                            </p>
                        </div>
                        <button
                            @click="goBack"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700"
                        >
                            ← Kembali
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jamaah Info -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Jamaah</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ jamaah.nama_lengkap }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ jamaah.email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ jamaah.no_telepon }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Program Talangan</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span :class="jamaah.program_talangan ? 'text-green-600' : 'text-gray-600'">
                                        {{ jamaah.program_talangan ? 'Ya' : 'Tidak' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rencana Keberangkatan</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ jamaah.rencana_keberangkatan ? formatDate(jamaah.rencana_keberangkatan) : '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Step Saat Ini</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ jamaah.current_step }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Overview -->
                <div v-if="paymentData" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Progress</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ paymentData.payment_progress }}%</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Terbayar</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ paymentData.paid_count }}/{{ paymentData.total_count }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Sisa Tagihan</dt>
                                        <dd class="text-lg font-medium text-gray-900">Rp {{ formatCurrency(paymentData.total_outstanding) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h6a2 2 0 012 2v4a2 2 0 01-2 2h-6a2 2 0 01-2-2zM8 7v10a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2H10a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Status Pembayaran</dt>
                                        <dd class="text-sm font-medium">
                                            <span :class="paymentData.is_payment_complete ? 'text-green-600' : 'text-yellow-600'">
                                                {{ paymentData.is_payment_complete ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Generate Schedule Button (if no installments exist) -->
                <div v-if="paymentData && paymentData.all_installments.length === 0" class="bg-white overflow-hidden shadow rounded-lg mb-6">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Cicilan Belum Dibuat</h3>
                        <p class="text-gray-600 mb-6">
                            Klik tombol di bawah untuk membuat jadwal pembayaran cicilan untuk jamaah ini
                        </p>
                        <button
                            @click="generateSchedule"
                            :disabled="loading"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                        >
                            <span v-if="loading">Membuat Jadwal...</span>
                            <span v-else>Buat Jadwal Cicilan</span>
                        </button>
                    </div>
                </div>

                <!-- Installments Table -->
                <div v-if="paymentData && paymentData.all_installments.length > 0" class="bg-white shadow overflow-hidden rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Jadwal Pembayaran Cicilan</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cicilan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jatuh Tempo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Bayar
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="installment in paymentData.all_installments" :key="installment.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Cicilan ke-{{ installment.installment_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ formatDate(installment.due_date) }}
                                        </div>
                                        <div v-if="installment.is_overdue" class="text-xs text-red-600">
                                            Terlambat {{ Math.abs(installment.days_until_due) }} hari
                                        </div>
                                        <div v-else-if="installment.is_upcoming" class="text-xs text-yellow-600">
                                            {{ installment.days_until_due }} hari lagi
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ formatCurrency(installment.amount) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatusClass(installment.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ getStatusText(installment.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ installment.paid_at ? formatDateTime(installment.paid_at) : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ installment.notes || '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button
                                                v-if="installment.status === 'paid'"
                                                @click="approvePayment(installment)"
                                                class="text-green-600 hover:text-green-900"
                                            >
                                                Setujui
                                            </button>
                                            <button
                                                v-if="installment.status === 'paid'"
                                                @click="showRejectModal(installment)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Tolak
                                            </button>
                                            <button
                                                v-if="installment.status !== 'paid' && installment.status !== 'waived'"
                                                @click="showWaiveModal(installment)"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Bebaskan
                                            </button>
                                            <button
                                                v-if="installment.payment_proof"
                                                @click="downloadProof(installment.id)"
                                                class="text-purple-600 hover:text-purple-900"
                                            >
                                                Bukti
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Same modals as in Index.vue -->
                <!-- Reject Modal -->
                <div v-if="showRejectModalFlag" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Tolak Pembayaran Cicilan
                            </h3>
                            <form @submit.prevent="rejectPayment">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan *
                                    </label>
                                    <textarea
                                        v-model="rejectForm.reason"
                                        rows="4"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                        placeholder="Masukkan alasan penolakan..."
                                        required
                                    ></textarea>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button
                                        type="button"
                                        @click="closeRejectModal"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="loading"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50"
                                    >
                                        <span v-if="loading">Memproses...</span>
                                        <span v-else">Tolak</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Waive Modal -->
                <div v-if="showWaiveModalFlag" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Bebaskan Pembayaran Cicilan
                            </h3>
                            <form @submit.prevent="waivePayment">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Pembebasan *
                                    </label>
                                    <textarea
                                        v-model="waiveForm.reason"
                                        rows="4"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Masukkan alasan pembebasan..."
                                        required
                                    ></textarea>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button
                                        type="button"
                                        @click="closeWaiveModal"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="loading"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                                    >
                                        <span v-if="loading">Memproses...</span>
                                        <span v-else>Bebaskan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    jamaah: Object,
    paymentData: Object,
    error: String
})

const loading = ref(false)
const showRejectModalFlag = ref(false)
const showWaiveModalFlag = ref(false)
const selectedInstallment = ref(null)

const rejectForm = reactive({
    reason: ''
})

const waiveForm = reactive({
    reason: ''
})

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount)
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatDateTime = (datetime) => {
    return new Date(datetime).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getStatusClass = (status) => {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'paid': 'bg-green-100 text-green-800',
        'overdue': 'bg-red-100 text-red-800',
        'waived': 'bg-gray-100 text-gray-600'
    }
    return classes[status] || 'bg-gray-100 text-gray-600'
}

const getStatusText = (status) => {
    const texts = {
        'pending': 'Belum Dibayar',
        'paid': 'Sudah Dibayar',
        'overdue': 'Terlambat',
        'waived': 'Dibebaskan'
    }
    return texts[status] || status
}

const goBack = () => {
    router.visit('/admin/installments')
}

const generateSchedule = () => {
    if (confirm('Apakah Anda yakin ingin membuat jadwal cicilan untuk jamaah ini?')) {
        loading.value = true
        router.post(`/admin/installments/${props.jamaah.id}/generate`, {}, {
            onFinish: () => {
                loading.value = false
            }
        })
    }
}

const approvePayment = (installment) => {
    if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
        loading.value = true
        router.post(`/admin/installments/${installment.id}/approve`, {}, {
            onFinish: () => {
                loading.value = false
            }
        })
    }
}

const showRejectModal = (installment) => {
    selectedInstallment.value = installment
    rejectForm.reason = ''
    showRejectModalFlag.value = true
}

const closeRejectModal = () => {
    showRejectModalFlag.value = false
    selectedInstallment.value = null
    rejectForm.reason = ''
}

const rejectPayment = () => {
    if (!selectedInstallment.value) return

    loading.value = true
    router.post(`/admin/installments/${selectedInstallment.value.id}/reject`, {
        reason: rejectForm.reason
    }, {
        onSuccess: () => {
            closeRejectModal()
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

const showWaiveModal = (installment) => {
    selectedInstallment.value = installment
    waiveForm.reason = ''
    showWaiveModalFlag.value = true
}

const closeWaiveModal = () => {
    showWaiveModalFlag.value = false
    selectedInstallment.value = null
    waiveForm.reason = ''
}

const waivePayment = () => {
    if (!selectedInstallment.value) return

    loading.value = true
    router.post(`/admin/installments/${selectedInstallment.value.id}/waive`, {
        reason: waiveForm.reason
    }, {
        onSuccess: () => {
            closeWaiveModal()
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

const downloadProof = (installmentId) => {
    window.open(`/admin/installments/${installmentId}/download`, '_blank')
}
</script>