<template>
  <Head title="Pembayaran DP" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Pembayaran DP Umroh
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="flex items-center text-green-600">
                <div class="flex items-center justify-center w-8 h-8 bg-green-600 rounded-full">
                  <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <span class="ml-2 text-sm font-medium">Data Diri</span>
              </div>
            </div>
            <div class="flex-1 mx-4 h-1 bg-gray-200">
              <div class="h-1 bg-blue-600 w-1/2"></div>
            </div>
            <div class="flex items-center text-blue-600">
              <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full">
                <span class="text-white text-sm font-bold">2</span>
              </div>
              <span class="ml-2 text-sm font-medium">Pembayaran DP</span>
            </div>
            <div class="flex-1 mx-4 h-1 bg-gray-200"></div>
            <div class="flex items-center text-gray-400">
              <div class="flex items-center justify-center w-8 h-8 bg-gray-300 rounded-full">
                <span class="text-white text-sm font-bold">3</span>
              </div>
              <span class="ml-2 text-sm font-medium">Upload Dokumen</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Payment Info -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h3>

              <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                  <p class="text-sm text-gray-600">Paket Umroh</p>
                  <p class="font-semibold">{{ jamaahData.paket_name || 'Paket Premium' }}</p>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                  <p class="text-sm text-gray-600">Jumlah DP yang harus dibayar</p>
                  <p class="text-xl font-bold text-green-600">Rp {{ formatCurrency(dpAmount) }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                  <h4 class="font-semibold mb-2">Rekening Tujuan:</h4>
                  <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                      <span>Bank:</span>
                      <span class="font-medium">BCA</span>
                    </div>
                    <div class="flex justify-between">
                      <span>No. Rekening:</span>
                      <span class="font-medium">1234567890</span>
                    </div>
                    <div class="flex justify-between">
                      <span>Atas Nama:</span>
                      <span class="font-medium">PT. Travel Umroh</span>
                    </div>
                  </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                  <div class="flex">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                      <h4 class="text-sm font-medium text-yellow-800">Penting!</h4>
                      <p class="text-sm text-yellow-700 mt-1">
                        Transfer sesuai nominal yang tertera. Upload bukti transfer yang jelas dan lengkap.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Upload Form -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Bukti Transfer</h3>

              <form @submit.prevent="uploadPaymentProof" class="space-y-6">
                <!-- Nominal Transfer -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nominal Yang Ditransfer *
                  </label>
                  <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                    <input
                      v-model="form.dp_amount"
                      type="number"
                      class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                      placeholder="0"
                      required
                    >
                  </div>
                  <p class="text-xs text-gray-500 mt-1">Masukkan nominal yang Anda transfer</p>
                </div>

                <!-- Upload Bukti -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Transfer *
                  </label>
                  <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <div class="text-center">
                      <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      <div class="mt-4">
                        <label class="cursor-pointer">
                          <span class="mt-2 block text-sm font-medium text-gray-900">
                            Upload bukti transfer
                          </span>
                          <input
                            ref="fileInput"
                            type="file"
                            class="sr-only"
                            accept="image/*"
                            @change="handleFileSelect"
                            required
                          >
                          <span class="mt-1 block text-xs text-gray-500">
                            PNG, JPG, JPEG up to 2MB
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div v-if="selectedFile" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex items-center">
                      <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                      <span class="ml-2 text-sm text-green-700">{{ selectedFile.name }}</span>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                  <Link
                    :href="route('jamaah.dashboard')"
                    class="text-gray-600 hover:text-gray-900"
                  >
                    ← Kembali ke Dashboard
                  </Link>

                  <button
                    type="submit"
                    :disabled="uploading"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span v-if="uploading">Uploading...</span>
                    <span v-else>Konfirmasi Pembayaran</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Payment History -->
        <div v-if="jamaahData.bukti_transfer" class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                  <p class="text-sm font-medium text-blue-800">Bukti transfer sudah diupload</p>
                  <p class="text-sm text-blue-600">Menunggu verifikasi dari admin</p>
                </div>
              </div>
            </div>
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

const props = defineProps({
  jamaahData: {
    type: Object,
    required: true
  }
})

const form = ref({
  dp_amount: 5000000, // Default DP amount
  bukti_transfer: null
})

const selectedFile = ref(null)
const uploading = ref(false)
const fileInput = ref(null)

// Computed
const dpAmount = computed(() => {
  // Calculate DP based on package or default
  return form.value.dp_amount || 5000000
})

// Methods
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount)
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate file
    if (file.size > 2 * 1024 * 1024) { // 2MB
      alert('File terlalu besar. Maksimal 2MB.')
      return
    }

    if (!file.type.startsWith('image/')) {
      alert('File harus berupa gambar.')
      return
    }

    selectedFile.value = file
    form.value.bukti_transfer = file
  }
}

const uploadPaymentProof = async () => {
  if (!form.value.bukti_transfer) {
    alert('Pilih file bukti transfer terlebih dahulu')
    return
  }

  if (!form.value.dp_amount || form.value.dp_amount <= 0) {
    alert('Masukkan nominal transfer yang valid')
    return
  }

  uploading.value = true

  const formData = new FormData()
  formData.append('bukti_transfer', form.value.bukti_transfer)
  formData.append('dp_amount', form.value.dp_amount)

  router.post(route('jamaah.pembayaran.upload'), formData, {
    forceFormData: true,
    onSuccess: (response) => {
      console.log('Upload success:', response)
      alert('Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.')
      router.visit(route('jamaah.dashboard'))
    },
    onError: (errors) => {
      console.error('Upload error:', errors)
      console.log('Full error object:', errors)
      alert('Gagal upload bukti transfer: ' + (errors.message || Object.keys(errors).join(', ') || 'Terjadi kesalahan'))
    },
    onFinish: () => {
      uploading.value = false
    }
  })
}
</script>