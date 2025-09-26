<template>
    <AdminLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Manajemen Cicilan Program Talangan</h1>
                    <p class="text-gray-600 mt-2">Kelola pembayaran cicilan jamaah program talangan</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6" v-if="statistics">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Cicilan</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ statistics.total_installments }}</dd>
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
                                        <dt class="text-sm font-medium text-gray-500 truncate">Sudah Dibayar</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ statistics.paid_installments }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Menunggu</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ statistics.pending_installments }}</dd>
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
                                        <dt class="text-sm font-medium text-gray-500 truncate">Terlambat</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ statistics.overdue_installments }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filter & Pencarian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    v-model="filters.status"
                                    @change="applyFilters"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="paid">Sudah Dibayar</option>
                                    <option value="overdue">Terlambat</option>
                                    <option value="waived">Dibebaskan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Jamaah</label>
                                <input
                                    type="text"
                                    v-model="filters.search"
                                    @input="debounceSearch"
                                    placeholder="Nama jamaah..."
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>

                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        v-model="filters.overdue"
                                        @change="applyFilters"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Hanya Terlambat</span>
                                </label>
                            </div>

                            <div class="flex items-end">
                                <button
                                    @click="resetFilters"
                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Installments Table -->
                <div class="bg-white shadow overflow-hidden rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Cicilan</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jamaah
                                    </th>
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
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="installment in installments.data" :key="installment.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ installment.jamaah_profile?.nama_lengkap_bin_binti }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ installment.jamaah_profile?.user?.email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            Cicilan ke-{{ installment.installment_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ formatDate(installment.due_date) }}
                                        </div>
                                        <div v-if="isOverdue(installment.due_date, installment.status)" class="text-xs text-red-600">
                                            Terlambat {{ getOverdueDays(installment.due_date) }} hari
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ formatCurrency(installment.amount) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatusClass(installment)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ getStatusText(installment) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button
                                                v-if="installment.status === 'paid' && !installment.approved_at"
                                                @click="approvePayment(installment)"
                                                class="text-green-600 hover:text-green-900 text-xs px-2 py-1 border border-green-600 rounded hover:bg-green-50"
                                            >
                                                Setujui
                                            </button>
                                            <button
                                                v-if="installment.status === 'paid' && !installment.approved_at"
                                                @click="showRejectModal(installment)"
                                                class="text-red-600 hover:text-red-900 text-xs px-2 py-1 border border-red-600 rounded hover:bg-red-50"
                                            >
                                                Tolak

                                            </button>
                                            <span
                                                v-if="installment.status === 'paid' && installment.approved_at"
                                                class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded"
                                            >
                                                Disetujui
                                            </span>
                                            <button
                                                v-if="installment.status !== 'paid' && installment.status !== 'waived'"
                                                @click="showWaiveModal(installment)"
                                                class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 border border-blue-600 rounded hover:bg-blue-50"
                                            >
                                                Bebaskan
                                            </button>
                                            <button
                                                v-if="installment.payment_proof"
                                                @click="showProofModal(installment)"
                                                class="text-purple-600 hover:text-purple-900 text-xs px-2 py-1 border border-purple-600 rounded hover:bg-purple-50"
                                            >
                                                Lihat Bukti
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="installments.links" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a v-if="installments.prev_page_url" :href="installments.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                                <a v-if="installments.next_page_url" :href="installments.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing {{ installments.from }} to {{ installments.to }} of {{ installments.total }} results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                        <template v-for="(link, index) in installments.links" :key="index">
                                            <component
                                                :is="link.url ? 'a' : 'span'"
                                                :href="link.url"
                                                :class="[
                                                    'relative inline-flex items-center px-2 py-2 border text-sm font-medium',
                                                    link.active
                                                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                                    index === 0 ? 'rounded-l-md' : '',
                                                    index === installments.links.length - 1 ? 'rounded-r-md' : ''
                                                ]"
                                                v-html="link.label"
                                            />
                                        </template>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                        <span v-else>Tolak</span>
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

                <!-- Payment Proof Modal -->
                <div v-if="showProofModalFlag && selectedInstallment" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-10 mx-auto p-5 border max-w-2xl shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Bukti Pembayaran Cicilan
                                </h3>
                                <button
                                    @click="closeProofModal"
                                    class="text-gray-400 hover:text-gray-600"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Installment Info -->
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Jamaah:</span>
                                        <span class="text-gray-900">{{ selectedInstallment.jamaah_profile?.nama_lengkap_bin_binti }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Cicilan:</span>
                                        <span class="text-gray-900">ke-{{ selectedInstallment.installment_number }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Jumlah:</span>
                                        <span class="text-gray-900">Rp {{ formatCurrency(selectedInstallment.amount) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Jatuh Tempo:</span>
                                        <span class="text-gray-900">{{ formatDate(selectedInstallment.due_date) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Proof Image -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Bukti Transfer:</h4>
                                <div class="border rounded-lg overflow-hidden">
                                    <img
                                        :src="`/storage/${selectedInstallment.payment_proof}`"
                                        :alt="`Bukti pembayaran cicilan ${selectedInstallment.installment_number}`"
                                        class="w-full max-h-96 object-contain bg-gray-100"
                                        @error="$event.target.src='/images/no-image.png'"
                                    >
                                </div>
                            </div>

                            <!-- Payment Notes -->
                            <div v-if="selectedInstallment.notes" class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Catatan Pembayaran:</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ selectedInstallment.notes }}</p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center">
                                <button
                                    @click="downloadProof(selectedInstallment.id)"
                                    class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download
                                </button>

                                <div class="flex space-x-2">
                                    <button
                                        v-if="selectedInstallment.status === 'paid' && !selectedInstallment.approved_at"
                                        @click="showRejectModal(selectedInstallment); closeProofModal()"
                                        class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100"
                                    >
                                        Tolak
                                    </button>
                                    <button
                                        v-if="selectedInstallment.status === 'paid' && !selectedInstallment.approved_at"
                                        @click="approvePayment(selectedInstallment); closeProofModal()"
                                        class="px-4 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100"
                                    >
                                        Setujui
                                    </button>
                                    <span
                                        v-if="selectedInstallment.status === 'paid' && selectedInstallment.approved_at"
                                        class="px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-md"
                                    >
                                        ✅ Sudah Disetujui
                                    </span>
                                    <button
                                        @click="closeProofModal"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                                    >
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    installments: Object,
    statistics: Object,
    filters: Object
})

// Handle flash messages
const { $page } = usePage()

// Show flash messages on component mount
onMounted(() => {
    if ($page.props.flash.success) {
        alert('✅ ' + $page.props.flash.success)
    }
    if ($page.props.flash.error) {
        alert('❌ ' + $page.props.flash.error)
    }
})

const loading = ref(false)
const showRejectModalFlag = ref(false)
const showWaiveModalFlag = ref(false)
const showProofModalFlag = ref(false)
const selectedInstallment = ref(null)
const searchTimeout = ref(null)

const filters = reactive({
    status: props.filters?.status || '',
    search: props.filters?.search || '',
    overdue: props.filters?.overdue || false
})

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
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const getStatusClass = (installment) => {
    const status = installment.status

    if (status === 'paid') {
        return installment.approved_at ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800'
    }

    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'overdue': 'bg-red-100 text-red-800',
        'waived': 'bg-gray-100 text-gray-600'
    }
    return classes[status] || 'bg-gray-100 text-gray-600'
}

const getStatusText = (installment) => {
    const status = installment.status

    if (status === 'paid') {
        return installment.approved_at ? 'Disetujui' : 'Menunggu Persetujuan'
    }

    const texts = {
        'pending': 'Menunggu',
        'overdue': 'Terlambat',
        'waived': 'Dibebaskan'
    }
    return texts[status] || status
}

const isOverdue = (dueDate, status) => {
    return status === 'pending' && new Date(dueDate) < new Date()
}

const getOverdueDays = (dueDate) => {
    const today = new Date()
    const due = new Date(dueDate)
    const diffTime = Math.abs(today - due)
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
}

const applyFilters = () => {
    router.get('/admin/installments', filters, {
        preserveState: true,
        preserveScroll: true
    })
}

const debounceSearch = () => {
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value)
    }

    searchTimeout.value = setTimeout(() => {
        applyFilters()
    }, 500)
}

const resetFilters = () => {
    filters.status = ''
    filters.search = ''
    filters.overdue = false
    applyFilters()
}

const approvePayment = (installment) => {
    if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
        loading.value = true
        router.post(route('admin.installments.approve', installment.id), {}, {
            onError: (errors) => {
                console.error('Approve payment error:', errors)
                alert('❌ Gagal menyetujui pembayaran: ' + (errors.message || 'Unknown error'))
            },
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
    router.post(route('admin.installments.reject', selectedInstallment.value.id), {
        reason: rejectForm.reason
    }, {
        onSuccess: () => {
            closeRejectModal()
        },
        onError: (errors) => {
            console.error('Reject payment error:', errors)
            alert('❌ Gagal menolak pembayaran: ' + (errors.message || 'Unknown error'))
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
    router.post(route('admin.installments.waive', selectedInstallment.value.id), {
        reason: waiveForm.reason
    }, {
        onSuccess: () => {
            closeWaiveModal()
        },
        onError: (errors) => {
            console.error('Waive payment error:', errors)
            alert('❌ Gagal membebaskan pembayaran: ' + (errors.message || 'Unknown error'))
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

const showProofModal = (installment) => {
    selectedInstallment.value = installment
    showProofModalFlag.value = true
}

const closeProofModal = () => {
    showProofModalFlag.value = false
    selectedInstallment.value = null
}

const downloadProof = (installmentId) => {
    window.open(route('admin.installments.download', installmentId), '_blank')
}
</script>
