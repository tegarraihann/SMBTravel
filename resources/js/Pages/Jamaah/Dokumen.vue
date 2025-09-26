<template>
  <Head title="Upload Dokumen" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Upload Dokumen Persyaratan
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <ProgressBar :current-step="jamaahData.current_step" :jamaah-data="jamaahData"
                     @step-action="handleStepAction" @navigate-to-step="handleStepNavigation" />

        <!-- Access Check -->
        <div v-if="!jamaahData.can_upload_documents" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
          <div class="flex">
            <svg class="w-6 h-6 text-yellow-600 mt-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
              <h3 class="text-lg font-medium text-yellow-800">Akses Tidak Tersedia</h3>
              <p class="text-yellow-700 mt-1">
                Anda belum bisa upload dokumen. Pastikan pembayaran DP sudah diverifikasi oleh admin.
              </p>
            </div>
          </div>
        </div>

        <!-- Document Upload Form -->
        <div v-else>
          <!-- Instructions -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Petunjuk Upload Dokumen</h3>
            <ul class="space-y-2 text-blue-800">
              <li class="flex items-start">
                <span class="text-blue-600 mr-2">•</span>
                <span>Upload semua dokumen yang diperlukan dalam format JPG, PNG, atau PDF</span>
              </li>
              <li class="flex items-start">
                <span class="text-blue-600 mr-2">•</span>
                <span>Ukuran maksimal setiap file adalah 5MB</span>
              </li>
              <li class="flex items-start">
                <span class="text-blue-600 mr-2">•</span>
                <span>Pastikan foto/scan jelas dan dapat dibaca</span>
              </li>
              <li class="flex items-start">
                <span class="text-blue-600 mr-2">•</span>
                <span>Dokumen dengan tanda bintang (*) wajib diupload</span>
              </li>
            </ul>
          </div>

          <!-- Required Documents -->
          <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Dokumen Wajib</h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto KTP -->
                <DocumentUploadField
                  v-model="form.foto_ktp"
                  name="foto_ktp"
                  label="Foto KTP *"
                  :existing="jamaahData.documents.foto_ktp"
                  @upload="handleDocumentUpload"
                />

                <!-- Foto KK -->
                <DocumentUploadField
                  v-model="form.foto_kk"
                  name="foto_kk"
                  label="Foto Kartu Keluarga *"
                  :existing="jamaahData.documents.foto_kk"
                  @upload="handleDocumentUpload"
                />

                <!-- Foto Diri -->
                <DocumentUploadField
                  v-model="form.foto_diri"
                  name="foto_diri"
                  label="Foto Diri *"
                  :existing="jamaahData.documents.foto_diri"
                  @upload="handleDocumentUpload"
                />

                <!-- Foto Paspor -->
                <DocumentUploadField
                  v-model="form.foto_paspor"
                  name="foto_paspor"
                  label="Foto Paspor *"
                  :existing="jamaahData.documents.foto_paspor"
                  @upload="handleDocumentUpload"
                />
              </div>
            </div>
          </div>

          <!-- Optional Documents -->
          <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Dokumen Opsional</h3>
              <p class="text-sm text-gray-600">Upload jika tersedia</p>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Buku Nikah -->
                <DocumentUploadField
                  v-model="form.scan_buku_nikah"
                  name="scan_buku_nikah"
                  label="Scan Buku Nikah"
                  :existing="jamaahData.documents.scan_buku_nikah"
                  @upload="handleDocumentUpload"
                />

                <!-- Akta Lahir -->
                <DocumentUploadField
                  v-model="form.scan_akta_lahir"
                  name="scan_akta_lahir"
                  label="Scan Akta Lahir"
                  :existing="jamaahData.documents.scan_akta_lahir"
                  @upload="handleDocumentUpload"
                />
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
              @click="submitAllDocuments"
              :disabled="uploading || !hasRequiredDocuments"
              :class="[
                'px-6 py-3 rounded-md font-medium focus:ring-2 focus:ring-blue-500',
                hasRequiredDocuments && !uploading
                  ? 'bg-blue-600 text-white hover:bg-blue-700'
                  : 'bg-gray-300 text-gray-500 cursor-not-allowed'
              ]"
            >
              <span v-if="uploading">Menyimpan...</span>
              <span v-else>{{ hasRequiredDocuments ? 'Selesai Upload Dokumen' : 'Upload Dokumen Wajib Dulu' }}</span>
            </button>
          </div>

          <!-- Upload Status -->
          <div v-if="jamaahData.documents_uploaded_at" class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Dokumen sudah diupload</p>
                <p class="text-sm text-green-600">
                  Uploaded pada: {{ formatDate(jamaahData.documents_uploaded_at) }}
                  {{ jamaahData.documents_verified ? '• Sudah diverifikasi' : '• Menunggu verifikasi' }}
                </p>
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
import DocumentUploadField from '@/Components/DocumentUploadField.vue'
import ProgressBar from '@/Components/Jamaah/ProgressBar.vue'

const props = defineProps({
  jamaahData: {
    type: Object,
    required: true
  }
})

const form = ref({
  foto_ktp: null,
  foto_kk: null,
  foto_diri: null,
  foto_paspor: null,
  scan_buku_nikah: null,
  scan_akta_lahir: null
})

const uploading = ref(false)

// Computed
const hasRequiredDocuments = computed(() => {
  return form.value.foto_ktp &&
         form.value.foto_kk &&
         form.value.foto_diri &&
         form.value.foto_paspor
})

// Methods
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const handleDocumentUpload = async (documentName, file) => {
  if (!file) return

  const formData = new FormData()
  formData.append('document_type', documentName)
  formData.append('document_file', file)

  try {
    await router.post(route('jamaah.dokumen.upload'), formData, {
      forceFormData: true,
      preserveState: true,
      onSuccess: () => {
        console.log(`${documentName} uploaded successfully`)
      },
      onError: (errors) => {
        console.error('Upload error:', errors)
        alert('Gagal upload dokumen: ' + (errors.message || 'Terjadi kesalahan'))
      }
    })
  } catch (error) {
    console.error('Upload error:', error)
    alert('Gagal upload dokumen')
  }
}

const submitAllDocuments = async () => {
  if (!hasRequiredDocuments.value) {
    alert('Harap upload semua dokumen wajib terlebih dahulu')
    return
  }

  uploading.value = true

  try {
    await router.post(route('jamaah.dokumen.complete'), {}, {
      onSuccess: () => {
        alert('Semua dokumen berhasil diupload! Menunggu verifikasi admin.')
        router.visit(route('jamaah.dashboard'))
      },
      onError: (errors) => {
        console.error('Complete error:', errors)
        alert('Gagal menyelesaikan upload dokumen: ' + (errors.message || 'Terjadi kesalahan'))
      }
    })
  } catch (error) {
    console.error('Complete error:', error)
    alert('Gagal menyelesaikan upload dokumen')
  } finally {
    uploading.value = false
  }
}

const handleStepAction = (action) => {
  switch (action) {
    case 'continue-registration':
      router.visit(route('jamaah.daftar'))
      break
    case 'pay-dp':
      router.visit(route('jamaah.pembayaran'))
      break
    case 'upload-documents':
      // Stay on documents page
      break
    case 'view-manasik-schedule':
      router.visit(route('jamaah.manasik'))
      break
    case 'check-verification-status':
      // Stay on page to show verification status
      break
    default:
      console.log('Step action:', action)
      break
  }
}

const handleStepNavigation = (stepId) => {
  // Navigate to appropriate page for testing
  console.log(`🧪 Testing navigation to step ${stepId}`)

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
      router.visit(route('jamaah.dashboard'))
      break
    default:
      break
  }
}
</script>