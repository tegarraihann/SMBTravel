<template>
    <AuthenticatedLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Progress Bar -->
                <ProgressBar :current-step="3" :jamaah-data="jamaahData"
                             @step-action="handleStepAction" @navigate-to-step="handleStepNavigation" />

                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ jamaahData?.program_talangan ? 'Pembayaran Cicilan Program Talangan' : 'Pelunasan Pembayaran Umroh' }}
                        </h1>
                        <p class="text-gray-600 mt-2">
                            {{ jamaahData?.program_talangan ? 'Kelola pembayaran cicilan Anda untuk program talangan umroh' : 'Lakukan pelunasan sisa pembayaran paket umroh Anda' }}
                        </p>
                    </div>
                </div>

                <!-- Error Message -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
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

                <!-- Payment Overview -->
                <div v-if="paymentData" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Progress Pembayaran</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ paymentData.payment_progress }}%</dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" :style="{ width: paymentData.payment_progress + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Terbayar</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ paymentData.paid_count }}/{{ paymentData.total_count }} Cicilan</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                </div>

                <!-- Generate Schedule Button (if no installments exist) -->
                <div v-if="paymentData && paymentData.all_installments.length === 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Cicilan Belum Dibuat</h3>
                        <p class="text-gray-600 mb-6">
                            Klik tombol di bawah untuk membuat jadwal pembayaran cicilan otomatis
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

                <!-- Next Payment Due -->
                <div v-if="paymentData && paymentData.next_due" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Cicilan Jatuh Tempo
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Cicilan ke-{{ paymentData.next_due.installment_number }} sebesar <strong>Rp {{ formatCurrency(paymentData.next_due.amount) }}</strong></p>
                                <p>Jatuh tempo: <strong>{{ formatDate(paymentData.next_due.due_date) }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Payments Alert -->
                <div v-if="paymentData && paymentData.overdue_payments.length > 0" class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Pembayaran Terlambat
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Anda memiliki {{ paymentData.overdue_payments.length }} cicilan yang terlambat. Segera lakukan pembayaran untuk menghindari denda.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Installments Table -->
                <div v-if="paymentData && paymentData.all_installments.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Jadwal Pembayaran Cicilan</h2>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button
                                                v-if="installment.status === 'pending'"
                                                @click="showPaymentModal(installment)"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Bayar
                                            </button>
                                            <button
                                                v-if="installment.payment_proof"
                                                @click="downloadProof(installment.id)"
                                                class="text-green-600 hover:text-green-900"
                                            >
                                                Unduh Bukti
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Modal -->
                <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Bayar Cicilan ke-{{ selectedInstallment?.installment_number }}
                            </h3>
                            <form @submit.prevent="processPayment">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Pembayaran
                                    </label>
                                    <div class="text-lg font-semibold text-gray-900">
                                        Rp {{ formatCurrency(selectedInstallment?.amount) }}
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Bukti Transfer *
                                    </label>
                                    <input
                                        type="file"
                                        ref="paymentProofInput"
                                        @change="handleFileChange"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        required
                                    >
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea
                                        v-model="paymentForm.notes"
                                        rows="3"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Catatan pembayaran..."
                                    ></textarea>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button
                                        type="button"
                                        @click="closePaymentModal"
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
                                        <span v-else>Bayar</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                <svg class="w-5 h-5 mr-2" :class="jamaahData?.documents_verified ? 'text-green-500' : 'text-yellow-500'" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="jamaahData?.documents_verified" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Status Dokumen
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Dokumen Upload:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaahData?.documents_uploaded_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                                        {{ jamaahData?.documents_uploaded_at ? 'Sudah Upload' : 'Belum Upload' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Verifikasi CS:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaahData?.documents_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ jamaahData?.documents_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                                <p v-if="jamaahData?.documents_uploaded_at" class="text-xs text-gray-500">
                                    Upload: {{ new Date(jamaahData.documents_uploaded_at).toLocaleDateString('id-ID') }}
                                </p>
                            </div>
                        </div>

                        <!-- Payment Verification Status -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="jamaahData?.payment_approved_by_admin ? 'text-green-500' : 'text-yellow-500'" fill="currentColor" viewBox="0 0 20 20">
                                    <path v-if="jamaahData?.payment_approved_by_admin" fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    <path v-else fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Status Pembayaran DP
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Bukti Transfer:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaahData?.bukti_transfer ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                                        {{ jamaahData?.bukti_transfer ? 'Sudah Upload' : 'Belum Upload' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Verifikasi Admin:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="jamaahData?.payment_approved_by_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ jamaahData?.payment_approved_by_admin ? 'Disetujui' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pelunasan Status -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="paymentData?.is_payment_complete ? 'text-green-500' : 'text-blue-500'" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Status Pelunasan
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Progress Pembayaran:</span>
                                    <span class="font-medium text-blue-600">{{ paymentData?.payment_progress || 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-blue-600 h-2 rounded-full" :style="{ width: (paymentData?.payment_progress || 0) + '%' }"></div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 text-xs rounded-full" :class="paymentData?.is_payment_complete ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ paymentData?.is_payment_complete ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </div>
                                <div v-if="paymentData?.total_outstanding" class="flex justify-between items-center">
                                    <span class="text-gray-600">Sisa Pembayaran:</span>
                                    <span class="font-medium text-red-600">
                                        Rp {{ formatCurrency(paymentData.total_outstanding) }}
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
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ProgressBar from '@/Components/Jamaah/ProgressBar.vue'

const props = defineProps({
    paymentData: Object,
    jamaahData: Object,
    error: String
})

const loading = ref(false)
const showModal = ref(false)
const selectedInstallment = ref(null)
const paymentForm = ref({
    notes: ''
})

// ProgressBar handlers
const handleStepAction = (action) => {
    switch (action) {
        case 'continue-registration':
            router.visit(route('jamaah.daftar'))
            break
        case 'pay-dp':
            router.visit(route('jamaah.pembayaran'))
            break
        case 'check-verification-status':
            showVerificationStatus()
            break
        case 'pay-remaining-balance':
            // Stay on current page (installments)
            break
        case 'view-manasik-schedule':
            router.visit(route('jamaah.manasik'))
            break
        default:
            console.log('Step action:', action)
            break
    }
}

const handleStepNavigation = (stepId) => {
    console.log(`🧪 Testing navigation to step ${stepId}`)

    switch (stepId) {
        case 1:
            router.visit(route('jamaah.daftar'))
            break
        case 2:
            router.visit(route('jamaah.pembayaran'))
            break
        case 3:
            // Stay on installments page
            break
        case 4:
            router.visit(route('jamaah.dashboard'))
            break
        default:
            break
    }
}

// Verification status modal
const showVerificationModal = ref(false)

const showVerificationStatus = () => {
    showVerificationModal.value = true
}

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

const generateSchedule = async () => {
    loading.value = true
    try {
        router.post('/jamaah/installments/generate', {}, {
            onSuccess: () => {
                router.reload()
            },
            onError: (errors) => {
                console.error('Error generating schedule:', errors)
            },
            onFinish: () => {
                loading.value = false
            }
        })
    } catch (error) {
        console.error('Error:', error)
        loading.value = false
    }
}

const showPaymentModal = (installment) => {
    selectedInstallment.value = installment
    showModal.value = true
    paymentForm.value.notes = ''
}

const closePaymentModal = () => {
    showModal.value = false
    selectedInstallment.value = null
    paymentForm.value.notes = ''
}

const handleFileChange = (event) => {
    // File validation could be added here
}

const processPayment = () => {
    if (!selectedInstallment.value) return

    const formData = new FormData()
    const fileInput = document.querySelector('input[type="file"]')

    if (fileInput.files[0]) {
        formData.append('payment_proof', fileInput.files[0])
    }

    formData.append('notes', paymentForm.value.notes)

    loading.value = true

    router.post(`/jamaah/installments/${selectedInstallment.value.id}/pay`, formData, {
        onSuccess: () => {
            closePaymentModal()
            router.reload()
        },
        onError: (errors) => {
            console.error('Payment error:', errors)
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

const downloadProof = (installmentId) => {
    window.open(`/jamaah/installments/${installmentId}/download`, '_blank')
}
</script>